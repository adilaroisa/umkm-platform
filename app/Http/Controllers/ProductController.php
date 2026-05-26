<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // READ: Menampilkan halaman dan data produk
    public function index()
    {
        $produk = Product::latest()->get();
        return view('admin.produk', compact('produk'));
    }

    // CREATE: Menyimpan data produk baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required',
            'kategori' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
        ]);

        Product::create($request->all());
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
    }

    // UPDATE: Menyimpan perubahan data produk
    public function update(Request $request, $id)
    {
        $produk = Product::findOrFail($id);
        $produk->update($request->all());
        return redirect()->back()->with('success', 'Produk berhasil diperbarui!');
    }

    // DELETE: Menghapus data produk
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }
}