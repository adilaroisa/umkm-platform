<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::where('user_id', Auth::id())->with('product')->get();
        return view('cart', compact('carts'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) { return redirect()->route('login'); }

        $request->validate(['product_id' => 'required|exists:products,id']);

        $cart = Cart::where('user_id', Auth::id())->where('product_id', $request->product_id)->first();

        if ($cart) {
            if ($cart->jumlah < $cart->product->stok) {
                $cart->increment('jumlah');
            } else {
                return redirect()->back()->with('error', 'Stok sudah maksimal.');
            }
        } else {
            Cart::create(['user_id' => Auth::id(), 'product_id' => $request->product_id, 'jumlah' => 1]);
        }

        return redirect()->back()->with('success', 'Menu berhasil ditambahkan!');
    }

    // UPDATE: Menerima ketikan angka manual dari input box
    public function update(Request $request, $id)
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $request->validate(['jumlah' => 'required|integer|min:1']);

        if ($request->jumlah > $cart->product->stok) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi! Maksimal: ' . $cart->product->stok);
        }

        $cart->update(['jumlah' => $request->jumlah]);
        return redirect()->back();
    }

    public function destroy($id)
    {
        Cart::where('user_id', Auth::id())->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Menu dihapus dari keranjang.');
    }
}