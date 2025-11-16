@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    
    <h2 class="fw-bold mb-4">Detail Laporan Transaksi</h2>

    <a href="{{ route('reports.index') }}" class="btn btn-secondary mb-3">
        ← Kembali
    </a>

    @php
        $trx = $data->first()->transaction;
    @endphp

    <div class="card border-0 shadow rounded-4 mb-4">
        <div class="card-body">

            <h5 class="fw-bold">Informasi Transaksi</h5>
            <hr>

            <div class="row mb-2">
                <div class="col-md-4"><strong>Kode Transaksi:</strong></div>
                <div class="col-md-8">{{ $trx->trs_code }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4"><strong>Tanggal:</strong></div>
                <div class="col-md-8">{{ \Carbon\Carbon::parse($trx->trs_date)->format('d M Y') }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4"><strong>Total Pembayaran:</strong></div>
                <div class="col-md-8">Rp {{ number_format($trx->trs_total, 0, ',', '.') }}</div>
            </div>

        </div>
    </div>

    <div class="card border-0 shadow rounded-4">
        <div class="card-body">
            <h5 class="fw-bold">Detail Produk</h5>
            <hr>

            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th style="width: 140px">Subtotal</th>
                        <th style="width: 120px">Tanggal Laporan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $item->product->prd_name ?? '-' }}</td>
                            <td>Rp {{ number_format($item->dtr_subtotal, 0, ',', '.') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->dtr_period)->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection
