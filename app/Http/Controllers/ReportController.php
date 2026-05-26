<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        // Jika ada filter tanggal yang dikirim
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('created_at', '>=', $request->start_date)
                  ->whereDate('created_at', '<=', $request->end_date);
        }

        // Ambil data yang sudah difilter
        $laporan = $query->latest()->get();
        
        // Hitung total uang masuk (hanya yang statusnya Lunas)
        $total_pendapatan = $query->where('status_pembayaran', 'Lunas')->sum('total_harga');

        return view('admin.laporan', compact('laporan', 'total_pendapatan'));
    }
}