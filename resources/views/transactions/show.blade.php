@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">

    <h1 class="fw-bold mb-3">Detail Transaksi #{{ $transaction->trs_code }}</h1>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-4">

            {{-- INFO TRANSAKSI --}}
            <div class="mb-3">
                <strong>Kasir:</strong> {{ $transaction->user->name }} <br>
                <strong>Tanggal:</strong> {{ $transaction->created_at->format('d M Y H:i') }} <br>
                <strong>Metode Pembayaran:</strong> {{ strtoupper($transaction->payment_method) }} <br>
                <strong>Tunai:</strong> Rp {{ number_format($transaction->cash ?? 0, 0, ',', '.') }} <br>
                <strong>Kembalian:</strong> Rp {{ number_format($transaction->change ?? 0, 0, ',', '.') }}
            </div>

            {{-- DETAIL PRODUK --}}
            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        function rupiah($num) { return 'Rp ' . number_format($num, 0, ',', '.'); }
                    @endphp

                    @foreach ($transaction->details as $d)
                        <tr>
                            <td>{{ $d->product->prd_name ?? $d->product_name }}</td>
                            <td>{{ $d->qty }}</td>
                            <td>{{ rupiah($d->price_at_sale) }}</td>
                            <td>{{ rupiah($d->subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @php
                $subtotal = $transaction->trs_subtotal;
                $discount = $transaction->trs_discount ?? 0;
            @endphp

            <div class="mt-3 text-end">

                <h5 class="mb-1">
                    Subtotal: <span class="fw-bold">{{ rupiah($subtotal) }}</span>
                </h5>

                {{-- DISKON --}}
                @if($discount > 0)
                    <h5 class="mb-1">
                        Diskon:
                        <span class="text-danger fw-bold">- {{ rupiah($discount) }}</span>
                    </h5>
                @else
                    <h5 class="mb-1">
                        Diskon: <span class="fw-bold">Tidak Ada</span>
                    </h5>
                @endif

                <h4 class="mt-3">
                    Total Bayar: 
                    <span class="text-success fw-bold">{{ rupiah($transaction->trs_total) }}</span>
                </h4>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('transactions.print-pdf', $transaction->id) }}" class="btn btn-dark px-4 me-2" target="_blank">Print</a>
                <a href="{{ route('transactions.index') }}" class="btn btn-secondary px-4">Kembali</a>
            </div>

        </div>
    </div>

</div>
@endsection
