<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver el carrito');
        }

        $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();
        $subtotal = $cartItems->sum('subtotal');
        $tax = $subtotal * 0.19; // IVA 19%
        $total = $subtotal + $tax;

        return view('carrito', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cartItem->product->stock
        ]);

        if ($cartItem->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'No tienes permisos para modificar este item');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return redirect()->back()->with('success', 'Cantidad actualizada');
    }

    public function remove(CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'No tienes permisos para eliminar este item');
        }

        $cartItem->delete();

        return redirect()->back()->with('success', 'Producto removido del carrito');
    }

    public function checkout(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:500'
        ]);

        $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('carrito')->with('error', 'El carrito está vacío');
        }

        DB::beginTransaction();

        try {
            $subtotal = $cartItems->sum('subtotal');
            $tax = $subtotal * 0.19;
            $total = $subtotal + $tax;

            $order = Order::create([
                'order_number' => 'ORD-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'user_id' => Auth::id(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->billing_address,
                'phone' => $request->phone,
                'notes' => $request->notes
            ]);

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price
                ]);

                // Reducir stock
                $cartItem->product->decrement('stock', $cartItem->quantity);
            }

            // Limpiar carrito
            CartItem::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('orders.show', $order)->with('success', 'Pedido realizado exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error al procesar el pedido');
        }
    }
}









