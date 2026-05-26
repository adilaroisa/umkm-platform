<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    public function index()
    {
        // Mencari riwayat pesanan khusus untuk user yang sedang login
        $pesanan = Order::where('nama_pelanggan', Auth::user()->name)
                        ->latest()
                        ->get();
                        
        return view('pesanan', compact('pesanan'));
    }
}