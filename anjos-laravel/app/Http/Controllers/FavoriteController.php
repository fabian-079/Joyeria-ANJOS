<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver tus favoritos');
        }

        $favorites = Favorite::with('product')->where('user_id', Auth::id())->get();
        return view('favoritos', compact('favorites'));
    }

    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para agregar a favoritos');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $favorite = Favorite::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return redirect()->back()->with('success', 'Producto removido de favoritos');
        } else {
            Favorite::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id
            ]);
            return redirect()->back()->with('success', 'Producto agregado a favoritos');
        }
    }

    public function remove(Favorite $favorite)
    {
        if ($favorite->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'No tienes permisos para eliminar este favorito');
        }

        $favorite->delete();
        return redirect()->back()->with('success', 'Producto removido de favoritos');
    }
}