@extends('layouts.app')

@section('content')
<div class="container-fluid bg-light-purple min-vh-100 p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-3">Riwayat Stok</h1>
        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-lg">Kembali</a>
    </div>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-4">

            <table class="table table-hover text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Id</th>
                        <th>Produk</th>
                        <th>Sebelum</th>
                        <th>Sesudah</th>
                        <th>Keterangan</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->product->prd_name }}</td>
                        <td>{{ $log->before }}</td>
                        <td>{{ $log->after }}</td>
                        <td>{{ $log->description }}</td>
                        <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-muted">Belum ada riwayat stok</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>

            <div class="mt-3">
                {{ $logs->links() }}
            </div>

        </div>
    </div>

</div>
@endsection