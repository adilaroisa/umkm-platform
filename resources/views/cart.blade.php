<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - Kedai UMKM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background-color: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .cart-item { border-radius: 16px; border: 1px solid #f1f5f9; }
        .checkout-card { border-radius: 16px; border: 1px solid #f1f5f9; position: sticky; top: 80px; }
        .custom-cb { width: 22px; height: 22px; cursor: pointer; }
        
        /* Tambahan CSS untuk Tombol Plus/Minus dan Input */
        .btn-qty { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; padding: 0; }
        .input-qty { width: 50px; text-align: center; border-radius: 8px; border: 1px solid #cbd5e1; font-weight: bold; }
        
        /* Menghilangkan panah naik-turun bawaan browser pada input number */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top" style="background-color: #4f46e5;">
        <div class="container">
            <a class="navbar-brand font-weight-bold" href="/"><i class="fas fa-arrow-left mr-2"></i>Lanjut Jajan</a>
            <div class="navbar-nav ml-auto">
                <a class="nav-item nav-link text-white font-weight-bold" href="{{ route('pesanan.saya') }}">
                    <i class="fas fa-history mr-1"></i> Pesanan Saya
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <h4 class="font-weight-bold text-dark mb-4">
            Keranjang Kamu <i class="fas fa-shopping-cart ml-2" style="color: #4f46e5;"></i>
        </h4>
        
        @if(session('error'))
            <div class="alert alert-danger border-0 mb-4 shadow-sm" style="border-left: 4px solid #ef4444; border-radius: 12px;">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-8 mb-4">
                    @forelse($carts as $item)
                        <div class="card cart-item bg-white mb-3 shadow-sm">
                            <div class="card-body p-3 d-flex align-items-center">
                                
                                <div class="mr-3">
                                    <input type="checkbox" name="cart_ids[]" value="{{ $item->id }}" class="custom-cb cart-checkbox" data-price="{{ $item->product->harga }}" data-id="{{ $item->id }}">
                                </div>
                                
                                <div class="flex-grow-1">
                                    <h6 class="font-weight-bold text-dark mb-1">{{ $item->product->nama_produk }}</h6>
                                    <div class="text-muted small">Rp {{ number_format($item->product->harga, 0, ',', '.') }} / porsi</div>
                                </div>
                                
                                <div class="d-flex align-items-center mx-3 bg-light rounded px-2 py-1">
                                    <button type="button" class="btn btn-sm btn-light text-muted btn-qty border shadow-sm btn-minus" data-id="{{ $item->id }}">
                                        <i class="fas fa-minus" style="font-size: 0.75rem;"></i>
                                    </button>
                                    
                                    <input type="number" id="qty-{{ $item->id }}" class="input-qty mx-2 py-1 border-0 bg-white" value="{{ $item->jumlah }}" min="1" data-id="{{ $item->id }}">
                                    
                                    <button type="button" class="btn btn-sm btn-white text-dark btn-qty border shadow-sm btn-plus" data-id="{{ $item->id }}">
                                        <i class="fas fa-plus" style="font-size: 0.75rem;"></i>
                                    </button>
                                </div>
                                
                                <div class="text-right mx-3" style="min-width: 110px;">
                                    <div class="font-weight-bold text-primary" style="font-size: 1.1rem;">
                                        Rp <span id="subtotal-{{ $item->id }}">{{ number_format($item->product->harga * $item->jumlah, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                
                                <div>
                                    <a href="#" class="btn btn-light text-danger btn-sm hapus-btn" data-id="{{ $item->id }}" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-shopping-basket fa-3x mb-3"></i>
                            <h5>Keranjang belanja kosong.</h5>
                        </div>
                    @endforelse
                </div>
                
                <div class="col-lg-4">
                    <div class="card checkout-card bg-white shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="font-weight-bold text-dark mb-4">Ringkasan Pesanan</h5>
                            <div class="d-flex justify-content-between mb-4">
                                <span class="font-weight-bold text-muted">Total Pilihan:</span>
                                <span class="font-weight-bold text-primary" style="font-size: 1.4rem;">Rp <span id="total-tagihan">0</span></span>
                            </div>
                            
                            <button type="submit" class="btn text-white w-100 font-weight-bold py-3 shadow-sm" style="background-color: #4f46e5; border-radius: 12px;">
                                Lanjut Pilih Pembayaran <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        
        <form id="form-hapus" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        // Fungsi menghitung total tagihan dari checkbox yang dipilih
        function hitungTotal() {
            let total = 0;
            $('.cart-checkbox:checked').each(function() {
                let id = $(this).data('id');
                let price = $(this).data('price');
                let qty = parseInt($('#qty-' + id).val()) || 1;
                total += price * qty;
            });
            $('#total-tagihan').text(new Intl.NumberFormat('id-ID').format(total));
        }

        // Fungsi reusable untuk update database via AJAX
        function updateKuantitasDB(id, qty) {
            $.ajax({
                url: '/keranjang/' + id,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT',
                    jumlah: qty
                },
                success: function() {
                    let price = $('.cart-checkbox[data-id="'+id+'"]').data('price');
                    $('#subtotal-' + id).text(new Intl.NumberFormat('id-ID').format(price * qty));
                    hitungTotal();
                }
            });
        }

        // Pemicu saat checkbox dicentang atau dihilangkan centangnya
        $('.cart-checkbox').on('change', function() {
            hitungTotal();
        });

        // Event saat tombol MINUS diklik
        $('.btn-minus').on('click', function() {
            let id = $(this).data('id');
            let input = $('#qty-' + id);
            let qty = parseInt(input.val());
            
            if(qty > 1) {
                qty = qty - 1;
                input.val(qty);
                updateKuantitasDB(id, qty);
            }
        });

        // Event saat tombol PLUS diklik
        $('.btn-plus').on('click', function() {
            let id = $(this).data('id');
            let input = $('#qty-' + id);
            let qty = parseInt(input.val()) + 1;
            
            input.val(qty);
            updateKuantitasDB(id, qty);
        });

        // Event saat angka diketik secara manual
        $('.input-qty').on('change keyup', function() {
            let id = $(this).data('id');
            let qty = parseInt($(this).val());
            
            if(qty < 1 || isNaN(qty)) { 
                qty = 1; 
                $(this).val(1); 
            }

            updateKuantitasDB(id, qty);
        });

        // Logika penghapusan item menggunakan form tersembunyi
        $('.hapus-btn').on('click', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let form = $('#form-hapus');
            form.attr('action', '/keranjang/' + id);
            form.submit();
        });

        // Hitung total saat halaman pertama kali dimuat
        $(document).ready(function() {
            hitungTotal();
        });
    </script>
</body>
</html>