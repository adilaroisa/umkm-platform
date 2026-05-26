<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // READ: Menampilkan daftar pesanan
    public function index()
    {
        $pesanan = Order::latest()->get();
        return view('admin.pesanan', compact('pesanan'));
    }

    // UPDATE: Memperbarui status pesanan (Lunas/Pending/Batal)
    public function update(Request $request, $id)
    {
        $pesanan = Order::findOrFail($id);
        
        $request->validate([
            'status_pembayaran' => 'required|in:Pending,Lunas,Batal',
        ]);

        $pesanan->update([
            'status_pembayaran' => $request->status_pembayaran
        ]);

        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui!');
    }

    // DELETE: Menghapus data pesanan
    public function destroy($id)
    {
        Order::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data pesanan berhasil dihapus!');
    }
}