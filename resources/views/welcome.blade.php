<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kedai UMKM Mandiri</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background-color: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* Product Card UI */
        .product-card { border-radius: 20px; border: 1px solid #f1f5f9; overflow: hidden; transition: all 0.3s ease; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.05); }
        .product-img { width: 100%; height: 180px; object-fit: cover; }
        
        /* Hero Banner */
        .hero-genz { 
            background: linear-gradient(135deg, #4f46e5 0%, #0ea5e9 100%); 
            padding: 3.5rem 1rem; 
            border-bottom-left-radius: 2.5rem; 
            border-bottom-right-radius: 2.5rem; 
        }
        
        /* NEW: Sidebar Kategori ala Aplikasi Modern */
        .sidebar-kategori { 
            background-color: #ffffff; 
            border-radius: 20px; 
            padding: 24px 16px; 
            border: 1px solid #f1f5f9;
        }
        .kategori-link { 
            display: flex; 
            align-items: center; 
            padding: 12px 20px; 
            color: #64748b; 
            font-weight: 600; 
            border-radius: 12px; 
            margin-bottom: 8px; 
            transition: all 0.2s ease; 
            background-color: transparent;
            text-decoration: none !important;
        }
        .kategori-link:hover { 
            background-color: #f8fafc; 
            color: #4f46e5; 
            transform: translateX(4px); 
        }
        .kategori-link.active { 
            background: linear-gradient(135deg, #4f46e5 0%, #0ea5e9 100%); 
            color: white; 
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25); 
        }
        .kategori-icon {
            width: 24px;
            text-align: center;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top border-bottom-0" style="background-color: #4f46e5;">
        <div class="container">
            <a class="navbar-brand font-weight-bold" href="/"><i class="fas fa-utensils mr-2"></i>Kedai UMKM</a>
            <div class="navbar-nav ml-auto align-items-center">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a class="nav-item nav-link text-white font-weight-bold mr-3" href="/admin/dashboard">Dashboard</a>
                    @else
                        @php 
                            $carts = \App\Models\Cart::where('user_id', Auth::id())->with('product')->get();
                            $cartCount = $carts->sum('jumlah');
                            $cartTotal = $carts->sum(function($c) { return $c->jumlah * $c->product->harga; });
                        @endphp
                        
                        <a class="nav-item nav-link text-white mr-3 position-relative d-flex align-items-center" href="{{ route('cart.index') }}">
                            <div class="position-relative mr-2">
                                <i class="fas fa-shopping-basket fa-lg"></i>
                                @if($cartCount > 0)
                                    <span class="badge badge-danger badge-pill position-absolute" style="top: -8px; right: -12px; border: 2px solid #4f46e5;">{{ $cartCount }}</span>
                                @endif
                            </div>
                            <span class="font-weight-bold bg-white text-primary px-2 py-1 rounded-pill" style="font-size: 0.85rem;">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                        </a>
                        <a class="nav-item nav-link text-white mr-3 font-weight-bold" href="{{ route('pesanan.saya') }}">Pesanan Saya</a>
                    @endif
                    <form action="/logout" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm font-weight-bold" style="border-radius: 8px;">Keluar</button>
                    </form>
                @else
                    <a class="btn btn-light text-primary btn-sm font-weight-bold px-4 shadow-sm" style="border-radius: 8px;" href="/login">Masuk</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="hero-genz text-white mb-5 shadow-sm">
        <div class="container text-center">
            <span class="badge px-3 py-2 mb-3 rounded-pill" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(5px); border: 1px solid rgba(255,255,255,0.3);">🔥 Terlaris di Kota Ini</span>
            <h1 class="font-weight-bold mb-3" style="font-size: 2.8rem; letter-spacing: -1px;">Pesan Jajanan Favoritmu!</h1>
            <p class="mx-auto" style="opacity: 0.9; max-width: 600px;">Lihat deskripsi menu untuk info topping. Tinggal tap, bayar, dan jajanan siap diproses!</p>
        </div>
    </div>

    <div class="container mb-5">
        @if(session('success'))
            <div class="alert alert-success shadow-sm border-0 mb-4" style="border-left: 4px solid #10b981; border-radius: 12px;">
                <i class="fas fa-check-circle mr-2 text-success"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="sidebar-kategori shadow-sm position-sticky" style="top: 90px;">
                    <h6 class="font-weight-bold text-dark mb-3 px-2 text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Kategori Menu</h6>
                    
                    <a href="?kategori=Semua" class="kategori-link {{ request('kategori') == 'Semua' || !request('kategori') ? 'active' : '' }}">
                        <i class="fas fa-th-large kategori-icon mr-2"></i> Semua Menu
                    </a>
                    <a href="?kategori=Makanan" class="kategori-link {{ request('kategori') == 'Makanan' ? 'active' : '' }}">
                        <i class="fas fa-hamburger kategori-icon mr-2"></i> Makanan
                    </a>
                    <a href="?kategori=Minuman" class="kategori-link {{ request('kategori') == 'Minuman' ? 'active' : '' }}">
                        <i class="fas fa-coffee kategori-icon mr-2"></i> Minuman
                    </a>
                    <a href="?kategori=Adds On" class="kategori-link {{ request('kategori') == 'Adds On' ? 'active' : '' }}">
                        <i class="fas fa-plus-circle kategori-icon mr-2"></i> Adds On
                    </a>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="font-weight-bold text-dark mb-0">
                        {{ request('kategori') && request('kategori') != 'Semua' ? 'Menu ' . request('kategori') : 'Semua Menu' }}
                    </h4>
                </div>

                <div class="row">
                    @php 
                        $query = \App\Models\Product::latest();
                        if(request('kategori') && request('kategori') != 'Semua') {
                            $query->where('kategori', request('kategori'));
                        }
                        $products = $query->get(); 
                    @endphp
                    
                    @forelse($products as $product)
                        <div class="col-md-4 mb-4">
                            <div class="card product-card h-100 bg-white">
                                @if($product->foto)
                                    <img src="{{ asset('storage/' . $product->foto) }}" class="product-img" alt="{{ $product->nama_produk }}">
                                @else
                                    <div class="text-center py-5 d-flex flex-column justify-content-center align-items-center" style="background: linear-gradient(45deg, #f8fafc, #e2e8f0); color: #94a3b8; height: 180px;">
                                        <i class="fas fa-image fa-3x mb-2"></i>
                                        <span class="small font-weight-bold">Tanpa Foto</span>
                                    </div>
                                @endif
                                
                                <div class="card-body d-flex flex-column p-4">
                                    <span class="badge mb-2 align-self-start px-2 py-1" style="background-color: #e0e7ff; color: #4f46e5; border-radius: 8px;">{{ $product->kategori }}</span>
                                    <h6 class="font-weight-bold text-dark mb-2" style="font-size: 1.1rem;">{{ $product->nama_produk }}</h6>
                                    
                                    <p class="text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.5;">
                                        {{ $product->deskripsi ?? 'Belum ada deskripsi untuk menu ini.' }}
                                    </p>
                                    
                                    <h5 class="font-weight-bold mb-4 mt-auto" style="color: #0ea5e9;">Rp {{ number_format($product->harga, 0, ',', '.') }}</h5>
                                    
                                    <div>
                                        @if($product->stok > 0)
                                            <form action="{{ route('cart.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <button type="submit" class="btn text-white btn-sm w-100 font-weight-bold py-2 shadow-sm" style="background-color: #4f46e5; border-radius: 10px;">
                                                    <i class="fas fa-plus mr-1"></i> Keranjang
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-light text-muted btn-sm w-100 font-weight-bold py-2" style="border-radius: 10px;" disabled>Habis Terjual</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <div class="p-5 bg-white shadow-sm" style="border-radius: 20px;">
                                <i class="fas fa-box-open fa-4x mb-3 text-light"></i>
                                <h5 class="text-dark font-weight-bold">Yah, menu kosong.</h5>
                                <p class="text-muted mb-0">Belum ada menu di kategori ini.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>