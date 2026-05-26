<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - UMKM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="ISI_CLIENT_KEY_SINI"></script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card mx-auto shadow-sm" style="max-width: 500px;">
            <div class="card-body text-center p-5">
                <h4 class="card-title mb-4">Ringkasan Pesanan</h4>
                <p class="card-text text-muted">Total Tagihan Anda:</p>
                <h2 class="font-weight-bold text-primary mb-4">Rp 125.000</h2>
                
                <button id="pay-button" class="btn btn-primary btn-lg btn-block">
                    Bayar Sekarang
                </button>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('pay-button').onclick = function(){
            // SnapToken ini nantinya akan otomatis dibuat oleh Controller Laravel
            // Karena kita masih fokus di frontend, kita gunakan kondisi dummy
            var dummySnapToken = 'contoh_token_123'; 
            
            snap.pay(dummySnapToken, {
                onSuccess: function(result){
                    alert("Pembayaran berhasil!"); 
                    console.log(result);
                },
                onPending: function(result){
                    alert("Menunggu pembayaran Anda!"); 
                    console.log(result);
                },
                onError: function(result){
                    alert("Pembayaran gagal!"); 
                    console.log(result);
                },
                onClose: function(){
                    alert('Anda menutup pop-up sebelum menyelesaikan pembayaran');
                }
            });
        };
    </script>
</body>
</html>