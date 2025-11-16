@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h2 class="fw-bold mb-4">Laporan Penjualan</h2>

    <form method="GET" class="mb-4 d-flex gap-3 align-items-end">
        <div>
            <label class="form-label">Pilih Bulan</label>
            <input type="month" name="month" value="{{ $month }}" class="form-control" style="width:200px">
        </div>

        <button class="btn btn-primary">Filter</button>

        <a href="{{ route('reports.pdf', ['month' => $month]) }}" 
           class="btn btn-danger">
            Export PDF
        </a>
    </form>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body">

            <h5>
                Total Pendapatan: 
                <strong>Rp {{ number_format($totalIncome, 0, ',', '.') }}</strong>
            </h5>

            <hr>

            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 140px">Tanggal</th>
                        <th style="width: 160px">Kode Transaksi</th>
                        <th>Produk</th>
                        <th style="width: 150px">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $r)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($r->dtr_period)->format('d M Y') }}</td>
                        <td>{{ $r->transaction->trs_code }}</td>
                        <td>{{ $r->product->prd_name ?? '-' }}</td>
                        <td>Rp {{ number_format($r->dtr_subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            Tidak ada data laporan pada bulan ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
