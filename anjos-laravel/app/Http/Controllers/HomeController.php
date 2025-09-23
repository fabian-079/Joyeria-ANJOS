<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_featured', true)
            ->where('is_active', true)
            ->limit(8)
            ->get();

        return view('inicio', compact('featuredProducts'));
    }

    public function catalogo(Request $request)
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

        // Obtener valores únicos para filtros
        $materials = Product::where('is_active', true)->distinct()->pluck('material')->filter();
        $colors = Product::where('is_active', true)->distinct()->pluck('color')->filter();
        $finishes = Product::where('is_active', true)->distinct()->pluck('finish')->filter();
        $stones = Product::where('is_active', true)->distinct()->pluck('stones')->filter();

        return view('catalogo', compact('products', 'categories', 'materials', 'colors', 'finishes', 'stones'));
    }

    public function producto(Product $product)
    {
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('producto-detalle', compact('product', 'relatedProducts'));
    }
}









