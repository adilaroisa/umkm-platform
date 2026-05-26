<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerOrderController;

Route::get('/', function () {
    return view('welcome');
});

// --- RUTE DASHBOARD ADMIN ---
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// --- RUTE CRUD PRODUK ---
Route::get('/admin/produk', [ProductController::class, 'index'])->name('produk.index');
Route::post('/admin/produk', [ProductController::class, 'store'])->name('produk.store');
Route::put('/admin/produk/{id}', [ProductController::class, 'update'])->name('produk.update');
Route::delete('/admin/produk/{id}', [ProductController::class, 'destroy'])->name('produk.destroy');

// --- RUTE CRUD PENGGUNA ---
Route::get('/admin/pengguna', [UserController::class, 'index'])->name('pengguna.index');
Route::post('/admin/pengguna', [UserController::class, 'store'])->name('pengguna.store');
Route::put('/admin/pengguna/{id}', [UserController::class, 'update'])->name('pengguna.update');
Route::delete('/admin/pengguna/{id}', [UserController::class, 'destroy'])->name('pengguna.destroy');

// --- RUTE CRUD PESANAN ---
Route::get('/admin/pesanan', [OrderController::class, 'index'])->name('pesanan.index');
Route::put('/admin/pesanan/{id}', [OrderController::class, 'update'])->name('pesanan.update');
Route::delete('/admin/pesanan/{id}', [OrderController::class, 'destroy'])->name('pesanan.destroy');

// --- RUTE LAPORAN TRANSAKSI ---
Route::get('/admin/laporan', [ReportController::class, 'index'])->name('laporan.index');

// Rute Logout Sapu Jagat
Route::get('/logout', [AuthenticatedSessionController::class, 'destroy']);

// RUTE KERANJANG & PESANAN (Hanya untuk pelanggan yang sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang', [CartController::class, 'store'])->name('cart.store');
    Route::put('/keranjang/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    
    Route::get('/pesanan-saya', [CustomerOrderController::class, 'index'])->name('pesanan.saya');
    Route::get('/pesanan-saya/{id}/bayar', [CheckoutController::class, 'repay'])->name('checkout.repay');
});

// RUTE WEBHOOK MIDTRANS (Sangat Penting: Wajib di luar middleware auth)
Route::post('/midtrans/webhook', [CheckoutController::class, 'webhook']);

require __DIR__.'/auth.php';