<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Pembayaran - Kedai UMKM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top" style="background-color: #4f46e5;">
        <div class="container">
            <a class="navbar-brand font-weight-bold" href="#">Metode Pembayaran</a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card border-0 shadow-sm p-3" style="border-radius: 16px;">
                    <div class="card-body text-center">
                        <i class="fas fa-wallet fa-4x text-primary mb-3" style="color: #4f46e5 !important;"></i>
                        <h4 class="font-weight-bold text-dark">Selesaikan Pembayaran</h4>
                        <p class="text-muted">Pesanan Anda **{{ $order->no_order }}** telah berhasil dicatat. Silakan klik tombol di bawah untuk memilih metode pembayaran.</p>
                        
                        <hr class="my-4">
                        
                        <div class="d-flex justify-content-between mb-4">
                            <span class="font-weight-bold text-muted">Total Tagihan:</span>
                            <h3 class="font-weight-bold text-primary">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</h3>
                        </div>

                        <button id="pay-button" class="btn text-white w-100 font-weight-bold py-3" style="background-color: #4f46e5; border-radius: 12px; font-size: 1.1rem;">
                            Pilih Metode Pembayaran <i class="fas fa-credit-card ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    window.location.href = "{{ route('pesanan.saya') }}";
                },
                onPending: function(result){
                    window.location.href = "{{ route('pesanan.saya') }}";
                },
                onError: function(result){
                    alert("Pembayaran gagal, silakan coba lagi.");
                },
                onClose: function(){
                    alert('Anda menutup halaman pembayaran sebelum selesai.');
                }
            });
        });
    </script>
</body>
</html>