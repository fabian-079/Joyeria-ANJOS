<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\CartItem;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        // Constructor vacío - las verificaciones de rol se hacen en los métodos individuales
    }

    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        // Filtros multicriterio
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('material')) {
            $query->where('material', 'like', '%' . $request->material . '%');
        }

        if ($request->filled('color')) {
            $query->where('color', 'like', '%' . $request->color . '%');
        }

        if ($request->filled('finish')) {
            $query->where('finish', 'like', '%' . $request->finish . '%');
        }

        if ($request->filled('stones')) {
            $query->where('stones', 'like', '%' . $request->stones . '%');
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('stock')) {
            if ($request->stock === 'available') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock === 'out_of_stock') {
                $query->where('stock', 0);
            }
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        // Verificar que el usuario sea admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $categories = Category::where('is_active', true)->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Verificar que el usuario sea admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        // Debug temporal - verificar si el archivo se está enviando
        if ($request->hasFile('image')) {
            \Log::info('Archivo de imagen recibido:', [
                'name' => $request->file('image')->getClientOriginalName(),
                'size' => $request->file('image')->getSize(),
                'mime' => $request->file('image')->getMimeType(),
                'extension' => $request->file('image')->getClientOriginalExtension()
            ]);
        } else {
            \Log::info('No se recibió archivo de imagen');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'material' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'finish' => 'nullable|string|max:255',
            'stones' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'is_active' => 'boolean'
        ];

        // Solo validar imagen si se envía
        if ($request->hasFile('image')) {
            $rules['image'] = 'image|mimes:jpeg,jpg,png,gif,webp|max:5120';
        }

        $request->validate($rules);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Producto creado exitosamente');
    }

    public function edit(Product $product)
    {
        // Verificar que el usuario sea admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $categories = Category::where('is_active', true)->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        // Verificar que el usuario sea admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'material' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'finish' => 'nullable|string|max:255',
            'stones' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'is_active' => 'boolean'
        ];

        // Solo validar imagen si se envía
        if ($request->hasFile('image')) {
            $rules['image'] = 'image|mimes:jpeg,jpg,png,gif,webp|max:5120';
        }

        $request->validate($rules);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Producto actualizado exitosamente');
    }

    public function destroy(Product $product)
    {
        // Verificar que el usuario sea admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Producto eliminado exitosamente');
    }

    public function show(Product $product)
    {
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('producto-detalle', compact('product', 'relatedProducts'));
    }

    public function addToCart(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para agregar productos al carrito');
        }

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->back()->with('success', 'Producto agregado al carrito');
    }

    public function addToFavorites(Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para agregar a favoritos');
        }

        $favorite = Favorite::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return redirect()->back()->with('success', 'Producto removido de favoritos');
        } else {
            Favorite::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id
            ]);
            return redirect()->back()->with('success', 'Producto agregado a favoritos');
        }
    }
}


