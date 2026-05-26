<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        $selectedCartIds = $request->input('cart_ids');

        if (empty($selectedCartIds)) {
            return redirect()->back()->with('error', 'Pilih minimal satu menu jajanan untuk dicheckout!');
        }

        $carts = Cart::where('user_id', Auth::id())
                     ->whereIn('id', $selectedCartIds)
                     ->with('product')
                     ->get();

        $total_harga = 0;
        $item_details = [];

        foreach ($carts as $cart) {
            if ($cart->jumlah > $cart->product->stok) {
                return redirect()->back()->with('error', 'Stok ' . $cart->product->nama_produk . ' tidak mencukupi!');
            }
            
            $subtotal = $cart->product->harga * $cart->jumlah;
            $total_harga += $subtotal;

            $item_details[] = [
                'id' => $cart->product_id,
                'price' => $cart->product->harga,
                'quantity' => $cart->jumlah,
                'name' => $cart->product->nama_produk
            ];
        }

        $no_order = 'ORD-' . time();

        $order = Order::create([
            'no_order' => $no_order,
            'nama_pelanggan' => Auth::user()->name,
            'total_harga' => $total_harga,
            'status_pembayaran' => 'Pending',
        ]);

        // TAHAP BARU: Simpan detail barang ke memori pesanan sebelum keranjang dihapus
        foreach ($carts as $cart) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'jumlah' => $cart->jumlah
            ]);
        }

        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        Config::$is3ds = env('MIDTRANS_IS_3DS', true);

        $params = [
            'transaction_details' => [
                'order_id' => $no_order,
                'gross_amount' => $total_harga,
            ],
            'item_details' => $item_details,
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'custom_expiry' => [
                'expiry_duration' => 15,
                'unit' => 'minute'
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            
            Cart::where('user_id', Auth::id())->whereIn('id', $selectedCartIds)->delete();

            return view('checkout_payment', compact('order', 'snapToken'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Midtrans Error: ' . $e->getMessage());
        }
    }

    public function repay($id)
    {
        $order = Order::findOrFail($id);

        if ($order->nama_pelanggan !== Auth::user()->name) {
            return redirect()->back()->with('error', 'Akses tidak sah.');
        }

        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        Config::$is3ds = env('MIDTRANS_IS_3DS', true);

        $params = [
            'transaction_details' => [
                'order_id' => $order->no_order . '-' . time(), 
                'gross_amount' => $order->total_harga,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'custom_expiry' => [
                'expiry_duration' => 15,
                'unit' => 'minute'
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return view('checkout_payment', compact('order', 'snapToken'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghubungkan ke Midtrans: ' . $e->getMessage());
        }
    }

    public function webhook(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            $asli_no_order = substr($request->order_id, 0, 14); 
            $order = Order::where('no_order', 'like', $asli_no_order . '%')->first();

            if ($order) {
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    
                    // PENTING: Cegah perhitungan ganda jika Midtrans mengirim info lunas dua kali
                    if ($order->status_pembayaran == 'Pending') {
                        $order->update(['status_pembayaran' => 'Lunas']);
                        
                        // LOGIKA BARU: Cari daftar barang di pesanan ini, kurangi stok, dan tambah terjual!
                        $details = OrderDetail::where('order_id', $order->id)->get();
                        foreach ($details as $detail) {
                            $produk = Product::find($detail->product_id);
                            if ($produk) {
                                $produk->decrement('stok', $detail->jumlah);
                                $produk->increment('terjual', $detail->jumlah);
                            }
                        }
                    }
                } elseif ($request->transaction_status == 'cancel' || $request->transaction_status == 'deny' || $request->transaction_status == 'expire') {
                    $order->update(['status_pembayaran' => 'Batal']);
                }
            }
        }

        return response()->json(['message' => 'Berhasil ditangkap']);
    }
}