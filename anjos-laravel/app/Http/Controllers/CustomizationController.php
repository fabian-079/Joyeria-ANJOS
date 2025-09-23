<?php

namespace App\Http\Controllers;

use App\Models\Customization;
use App\Models\CartItem;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomizationController extends Controller
{
    public function __construct()
    {
        // Constructor vacío - las verificaciones de rol se hacen en los métodos individuales
    }

    public function index()
    {
        $customizations = Customization::with('user')->get();
        return view('personalizacion.index', compact('customizations'));
    }

    public function create()
    {
        return view('personalizacion.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jewelry_type' => 'required|string|max:255',
            'design' => 'required|string|max:255',
            'stones' => 'required|string|max:255',
            'finish' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'material' => 'required|string|max:255',
            'engraving' => 'nullable|string|max:255',
            'special_instructions' => 'nullable|string|max:1000'
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['estimated_price'] = $this->calculateEstimatedPrice($request);

        Customization::create($data);

        return redirect()->route('personalizacion.index')->with('success', 'Solicitud de personalización enviada exitosamente');
    }

    public function show(Customization $customization)
    {
        $customization->load('user');
        return view('personalizacion.show', compact('customization'));
    }

    public function edit(Customization $customization)
    {
        // Verificar que el usuario sea admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return view('personalizacion.edit', compact('customization'));
    }

    public function update(Request $request, Customization $customization)
    {
        // Verificar que el usuario sea admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $request->validate([
            'jewelry_type' => 'required|string|max:255',
            'design' => 'required|string|max:255',
            'stones' => 'required|string|max:255',
            'finish' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'material' => 'required|string|max:255',
            'engraving' => 'nullable|string|max:255',
            'special_instructions' => 'nullable|string|max:1000',
            'estimated_price' => 'nullable|numeric|min:0',
            'status' => 'required|string|max:255'
        ]);

        $customization->update($request->all());

        return redirect()->route('personalizacion.index')->with('success', 'Personalización actualizada exitosamente');
    }

    public function destroy(Customization $customization)
    {
        // Verificar que el usuario sea admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $customization->delete();

        return redirect()->route('personalizacion.index')->with('success', 'Personalización eliminada exitosamente');
    }

    public function addToCart(Customization $customization)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para agregar al carrito');
        }

        // Crear un producto temporal para la personalización
        $tempProduct = [
            'name' => 'Personalización: ' . $customization->jewelry_type,
            'description' => $customization->special_instructions,
            'price' => $customization->estimated_price,
            'quantity' => 1
        ];

        // Aquí podrías crear un sistema de productos temporales o manejar las personalizaciones de manera diferente
        return redirect()->route('carrito')->with('success', 'Personalización agregada al carrito');
    }

    public function addToFavorites(Customization $customization)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para agregar a favoritos');
        }

        $favorite = Favorite::where('user_id', Auth::id())
            ->where('customization_id', $customization->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return redirect()->back()->with('success', 'Personalización removida de favoritos');
        } else {
            Favorite::create([
                'user_id' => Auth::id(),
                'customization_id' => $customization->id
            ]);
            return redirect()->back()->with('success', 'Personalización agregada a favoritos');
        }
    }

    private function calculateEstimatedPrice(Request $request)
    {
        $basePrice = 500000; // Precio base

        // Ajustes según material
        $materialMultiplier = [
            'oro 18k' => 2.5,
            'oro 14k' => 2.0,
            'plata sterling' => 1.0,
            'acero' => 0.5
        ];

        $multiplier = $materialMultiplier[$request->material] ?? 1.0;

        // Ajustes según piedras
        if ($request->stones !== 'ninguna') {
            $multiplier += 0.5;
        }

        return $basePrice * $multiplier;
    }
}









