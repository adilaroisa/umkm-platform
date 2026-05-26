@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h4 class="font-weight-bold text-dark">Dashboard</h4>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success shadow-sm">
                <div class="inner">
                    <h3><sup style="font-size: 20px">Rp</sup>{{ number_format($total_pendapatan, 0, ',', '.') }}</h3>
                    <p>Total Penjualan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <a href="{{ route('laporan.index') }}" class="small-box-footer">Detail Laporan <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary shadow-sm">
                <div class="inner">
                    <h3>{{ $total_pesanan }}</h3>
                    <p>Total Pesanan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="{{ route('pesanan.index') }}" class="small-box-footer">Kelola Pesanan <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-info shadow-sm">
                <div class="inner">
                    <h3>{{ $total_produk }}</h3>
                    <p>Total Produk</p>
                </div>
                <div class="icon">
                    <i class="fas fa-box"></i>
                </div>
                <a href="{{ route('produk.index') }}" class="small-box-footer">Kelola Produk <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary shadow-sm">
                <div class="inner">
                    <h3>{{ $total_pengguna }}</h3>
                    <p>Pelanggan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('pengguna.index') }}" class="small-box-footer">Kelola Pengguna <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white border-0" style="border-radius: 12px 12px 0 0;">
                    <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-chart-line text-primary mr-2"></i> Penjualan Bulanan</h3>
                </div>
                <div class="card-body">
                    <div style="height: 250px;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white border-0" style="border-radius: 12px 12px 0 0;">
                    <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-chart-bar text-primary mr-2"></i> Produk Terlaris</h3>
                </div>
                <div class="card-body p-0 text-dark">
                    <ul class="list-group list-group-flush">
                        @forelse($produk_terlaris as $produk)
                            <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold text-dark">
                                {{ $produk->nama_produk }}
                                <span class="badge badge-primary badge-pill py-1 px-2">{{ $produk->terjual }} Terjual</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">Belum ada data produk.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-0" style="border-radius: 12px 12px 0 0;">
                    <h3 class="card-title font-weight-bold text-dark">Pesanan Terbaru</h3>
                </div>
                <div class="card-body table-responsive p-0 text-dark">
                    <table class="table table-hover text-nowrap mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>No. Order</th>
                                <th>Pelanggan</th>
                                <th>Status</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesanan_terbaru as $pesanan)
                                <tr>
                                    <td class="text-muted font-weight-bold">#{{ $pesanan->no_order }}</td>
                                    <td class="font-weight-bold">{{ $pesanan->nama_pelanggan }}</td>
                                    <td>
                                        @if($pesanan->status_pembayaran == 'Lunas')
                                            <span class="badge badge-success px-2 py-1">Lunas</span>
                                        @elseif($pesanan->status_pembayaran == 'Pending')
                                            <span class="badge badge-warning px-2 py-1">Pending</span>
                                        @else
                                            <span class="badge badge-danger px-2 py-1">Batal</span>
                                        @endif
                                    </td>
                                    <td class="text-right font-weight-bold">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada pesanan masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .btn-logout {
            background-color: #ef4444 !important;
            color: #ffffff !important;
            border-radius: 8px;
            font-weight: 700;
            padding: 6px 18px !important;
            margin-top: 2px;
            border: none;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.3);
        }
        
        .btn-logout:hover {
            background-color: #dc2626 !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 12px rgba(220, 38, 38, 0.4);
        }
    </style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Tombol Logout
        let logoutLink = $('a[href$="logout"]');
        logoutLink.addClass('btn-logout');
        logoutLink.html('<i class="fas fa-sign-out-alt mr-2"></i>Logout');

        // Konfigurasi Chart.js Dinamis
        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Total Penjualan (Rp)',
                    data: {!! json_encode($chart_data) !!}, // INJEKSI DATA ASLI DATABASE
                    borderColor: '#007bff', 
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#007bff',
                    pointRadius: 4,
                    fill: true,
                    lineTension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { display: true },
                scales: {
                    xAxes: [{ gridLines: { display: false } }],
                    yAxes: [{
                        gridLines: { color: '#eaeaea', zeroLineColor: '#eaeaea' },
                        ticks: { 
                            beginAtZero: true,
                            callback: function(value, index, values) {
                                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }]
                }
            }
        });
    });
</script>
@stop