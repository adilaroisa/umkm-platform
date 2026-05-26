<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - Kedai UMKM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background-color: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .order-card { border-radius: 16px; border: 1px solid #f1f5f9; transition: all 0.3s ease; }
        .order-card:hover { box-shadow: 0 8px 20px rgba(0,0,0,0.06); transform: translateY(-2px); }
        .modal-content { border-radius: 20px; border: none; }
        
        /* Desain Filter Tab Modern */
        .filter-container { overflow-x: auto; white-space: nowrap; padding-bottom: 10px; }
        .filter-btn {
            background-color: #ffffff; color: #64748b; border: 1px solid #e2e8f0;
            padding: 8px 20px; border-radius: 50px; font-weight: 600; font-size: 0.95rem;
            margin-right: 10px; transition: all 0.2s ease; outline: none !important; box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .filter-btn:hover { background-color: #f1f5f9; color: #4f46e5; }
        .filter-btn.active {
            background: linear-gradient(135deg, #4f46e5 0%, #0ea5e9 100%);
            color: white; border-color: transparent; box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
        }
        /* Sembunyikan scrollbar untuk tampilan mobile yang bersih */
        .filter-container::-webkit-scrollbar { display: none; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top border-bottom-0" style="background-color: #4f46e5;">
        <div class="container">
            <a class="navbar-brand font-weight-bold" href="/"><i class="fas fa-arrow-left mr-2"></i>Kembali ke Kedai</a>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <h3 class="font-weight-bold text-dark mb-4"><i class="fas fa-history mr-2" style="color: #4f46e5;"></i>Riwayat Pesanan Saya</h3>

        @if(session('error'))
            <div class="alert alert-danger border-0 mb-4 shadow-sm" style="border-left: 4px solid #ef4444; border-radius: 12px;">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif

        <div class="filter-container mb-4">
            <button class="filter-btn active" data-filter="all"><i class="fas fa-list mr-1"></i> Semua</button>
            <button class="filter-btn" data-filter="Pending"><i class="fas fa-clock mr-1"></i> Belum Bayar</button>
            <button class="filter-btn" data-filter="Lunas"><i class="fas fa-check-circle mr-1"></i> Selesai</button>
            <button class="filter-btn" data-filter="Batal"><i class="fas fa-times-circle mr-1"></i> Dibatalkan</button>
        </div>

        <div class="row">
            <div class="col-lg-10 mx-auto" id="order-container">
                @forelse($pesanan as $item)
                    <div class="card order-card order-item bg-white mb-4 shadow-sm" data-status="{{ $item->status_pembayaran }}">
                        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted small"><i class="fas fa-calendar-alt mr-1"></i> {{ $item->created_at->format('d M Y, H:i') }}</span>
                            
                            @if($item->status_pembayaran == 'Lunas')
                                <span class="badge badge-success px-3 py-2 rounded-pill shadow-sm"><i class="fas fa-check-circle mr-1"></i> Lunas</span>
                            @elseif($item->status_pembayaran == 'Pending')
                                <span class="badge badge-warning px-3 py-2 rounded-pill shadow-sm text-dark"><i class="fas fa-clock mr-1"></i> Belum Bayar</span>
                            @else
                                <span class="badge badge-danger px-3 py-2 rounded-pill shadow-sm"><i class="fas fa-times-circle mr-1"></i> Dibatalkan</span>
                            @endif
                        </div>
                        
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded p-3 text-white mr-3" style="background: linear-gradient(45deg, #4f46e5, #0ea5e9); border-radius: 12px;">
                                    <i class="fas fa-receipt fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted small mb-1">Nomor Pesanan</h6>
                                    <h5 class="font-weight-bold text-dark mb-0">{{ $item->no_order }}</h5>
                                </div>
                                <div class="ml-auto text-right">
                                    <h6 class="text-muted small mb-1">Total Tagihan</h6>
                                    <h4 class="font-weight-bold mb-0" style="color: #4f46e5;">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-light border-0 pb-3 pt-3" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-secondary font-weight-bold mr-2 px-3" style="border-radius: 8px;" data-toggle="modal" data-target="#detailModal-{{ $item->id }}">
                                    <i class="fas fa-info-circle mr-1"></i> Lihat Detail
                                </button>

                                @if($item->status_pembayaran == 'Pending')
                                    <a href="{{ route('checkout.repay', $item->id) }}" class="btn text-white font-weight-bold px-4 shadow-sm" style="background-color: #4f46e5; border-radius: 8px;">
                                        Bayar Sekarang <i class="fas fa-wallet ml-1"></i>
                                    </a>
                                @elseif($item->status_pembayaran == 'Lunas')
                                    <button class="btn btn-secondary font-weight-bold px-4" style="border-radius: 8px;" disabled>
                                        <i class="fas fa-check mr-1"></i> Selesai
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="detailModal-{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content shadow">
                                <div class="modal-header border-bottom-0 pt-4 px-4">
                                    <h5 class="modal-title font-weight-bold text-dark"><i class="fas fa-file-invoice mr-2 text-primary"></i>Rincian Transaksi</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body px-4 pb-4">
                                    <div class="mb-3">
                                        <span class="text-muted small d-block">Nomor Pesanan:</span>
                                        <strong class="text-dark" style="font-size: 1.1rem;">{{ $item->no_order }}</strong>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-muted small d-block">Nama Pemesan:</span>
                                        <span class="font-weight-bold text-dark">{{ $item->nama_pelanggan }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-muted small d-block">Waktu Transaksi:</span>
                                        <span class="text-dark">{{ $item->created_at->format('d F Y - H:i:s') }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-muted small d-block">Status Pembayaran:</span>
                                        <span class="font-weight-bold {{ $item->status_pembayaran == 'Lunas' ? 'text-success' : ($item->status_pembayaran == 'Pending' ? 'text-warning' : 'text-danger') }}">
                                            <i class="fas {{ $item->status_pembayaran == 'Lunas' ? 'fa-check-circle' : ($item->status_pembayaran == 'Pending' ? 'fa-clock' : 'fa-times-circle') }} mr-1"></i>{{ $item->status_pembayaran }}
                                        </span>
                                    </div>
                                    
                                    <hr class="my-3">
                                    
                                    <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background-color: #f1f5f9;">
                                        <span class="font-weight-bold text-muted">Total Tagihan</span>
                                        <h4 class="font-weight-bold mb-0 text-primary">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                                <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                                    <button type="button" class="btn btn-light w-100 font-weight-bold py-2" style="border-radius: 8px;" data-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-receipt fa-4x mb-3" style="color: #cbd5e1;"></i>
                        <h5 class="text-dark font-weight-bold">Belum ada riwayat pesanan</h5>
                        <p class="text-muted">Kamu belum pernah memesan jajanan.</p>
                        <a href="/" class="btn text-white mt-2 px-4 shadow-sm" style="background-color: #4f46e5; border-radius: 10px;">Mulai Jajan</a>
                    </div>
                @endforelse

                <div class="text-center py-5 text-muted" id="empty-state" style="display: none;">
                    <i class="fas fa-box-open fa-4x mb-3" style="color: #cbd5e1;"></i>
                    <h5 class="text-dark font-weight-bold">Tidak ada pesanan</h5>
                    <p class="text-muted">Belum ada pesanan dengan status ini.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('.filter-btn').click(function() {
                // Ubah gaya tombol aktif
                $('.filter-btn').removeClass('active');
                $(this).addClass('active');

                // Ambil nilai filter
                let filterValue = $(this).data('filter');
                let visibleCount = 0;

                // Animasi menyembunyikan/menampilkan kartu berdasarkan status
                if(filterValue === 'all') {
                    $('.order-item').fadeIn(300);
                    visibleCount = $('.order-item').length;
                } else {
                    $('.order-item').hide();
                    let matchedItems = $('.order-item[data-status="' + filterValue + '"]');
                    matchedItems.fadeIn(300);
                    visibleCount = matchedItems.length;
                }

                // Tampilkan pesan kosong jika tidak ada yang cocok
                if(visibleCount === 0 && $('.order-item').length > 0) {
                    $('#empty-state').delay(100).fadeIn(300);
                } else {
                    $('#empty-state').hide();
                }
            });
        });
    </script>
</body>
</html>