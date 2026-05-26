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

        // Mengambil 5 pesanan terbaru
        $pesanan_terbaru = Order::latest()->take(5)->get();

        // Mengambil 5 produk terlaris berdasarkan kolom terjual
        $produk_terlaris = Product::orderBy('terjual', 'desc')->take(5)->get();

        // LOGIKA GRAFIK: Ambil akumulasi nominal pesanan 'Lunas' per bulan di tahun ini
        $penjualan_bulanan = Order::where('status_pembayaran', 'Lunas')
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) as bulan, SUM(total_harga) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->all();

        // Susun data ke dalam array 12 bulan (jika bulan kosong, otomatis diisi 0)
        $chart_data = [];
        for ($m = 1; $m <= 12; $m++) {
            $chart_data[] = $penjualan_bulanan[$m] ?? 0;
        }

        return view('admin.dashboard', compact(
            'total_produk', 
            'total_pengguna', 
            'total_pesanan', 
            'total_pendapatan',
            'pesanan_terbaru',
            'produk_terlaris',
            'chart_data' // Kirim data grafik ke view
        ));
    }
}