@extends('adminlte::page')

@section('title', 'Laporan Transaksi')

@section('content_header')
    <h1>Laporan Transaksi Penjualan</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline d-print-none">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Laporan</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.index') }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <div class="form-group mb-md-0">
                                <label>Dari Tanggal:</label>
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-md-0">
                                <label>Sampai Tanggal:</label>
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Tampilkan</button>
                            <a href="{{ route('laporan.index') }}" class="btn btn-secondary">Reset</a>
                            <button type="button" onclick="window.print()" class="btn btn-success float-right"><i class="fas fa-print"></i> Cetak</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-striped text-nowrap mb-0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>No Order</th>
                            <th>Tanggal Transaksi</th>
                            <th>Nama Pelanggan</th>
                            <th>Status</th>
                            <th class="text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan as $item)
                            <tr>
                                <td class="font-weight-bold">{{ $item->no_order }}</td>
                                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $item->nama_pelanggan }}</td>
                                <td>
                                    @if($item->status_pembayaran == 'Lunas')
                                        <span class="badge badge-success">Lunas</span>
                                    @elseif($item->status_pembayaran == 'Pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @else
                                        <span class="badge badge-danger">Batal</span>
                                    @endif
                                </td>
                                <td class="text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">Tidak ada data transaksi pada rentang tanggal tersebut.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-light">
                            <th colspan="4" class="text-right text-uppercase">Total Pendapatan (Hanya Lunas):</th>
                            <th class="text-right text-success font-weight-bold h5 mb-0">
                                Rp {{ number_format($total_pendapatan, 0, ',', '.') }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Mengatur tampilan saat dicetak (Ctrl+P) */
    @media print {
        .main-footer, .content-header { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        .badge { border: 1px solid #000; color: #000 !important; }
    }
</style>
@stop