<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil total data dari masing-masing tabel
        $total_produk = Product::count();
        $total_pengguna = User::where('role', 'customer')->count();
        $total_pesanan = Order::count();
        
        // Menghitung total uang dari pesanan yang sudah 'Lunas'
        $total_pendapatan = Order::where('status_pembayaran', 'Lunas')->sum('total_harga');

        // Mengambil 5 pesanan terbaru untuk ditampilkan di tabel ringkasan
        $pesanan_terbaru = Order::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'total_produk', 
            'total_pengguna', 
            'total_pesanan', 
            'total_pendapatan',
            'pesanan_terbaru'
        ));
    }
}