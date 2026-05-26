<?php

namespace App\Http\Controllers\Auth; // <-- Ini bagian yang sudah diperbaiki

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Ambil data user yang baru saja login
        $user = $request->user();

        // Pengalihan mutlak berdasarkan role tanpa menggunakan intended()
        if ($user->role && strtolower($user->role) === 'admin') {
            return redirect('/admin/dashboard');
        }

        // Jika bukan admin, maka diarahkan ke halaman toko utama
        return redirect('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}