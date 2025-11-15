@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">

    <h4 class="fw-bold mb-3">Detail Transaksi #{{ $transaction->id }}</h4>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-4">

            <div class="mb-3">
                <strong>Kasir:</strong> {{ $transaction->user->name }} <br>
                <strong>Tanggal:</strong> {{ $transaction->created_at->format('d M Y H:i') }} <br>
                <strong>Metode Pembayaran:</strong> {{ strtoupper($transaction->payment_method) }}
            </div>

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
                    @foreach ($transaction->details as $d)
                    <tr>
                        <td>{{ $d->product->name }}</td>
                        <td>{{ $d->qty }}</td>
                        <td>Rp {{ number_format($d->price_at_sale, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <h4 class="text-end mt-3">Total: 
                <span class="text-success">Rp {{ number_format($transaction->trs_total,0,',','.') }}</span>
            </h4>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('transactions.print', $transaction->id) }}" 
                   class="btn btn-dark px-4 me-2" target="_blank">Print</a>

                <a href="{{ route('transactions.index') }}" class="btn btn-secondary px-4">Kembali</a>
            </div>

        </div>
    </div>

</div>
@endsection
