@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="fw-bold mb-3">Daftar Transaksi</h1>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between mb-3">
        {{-- Search --}}
        <form action="{{ route('transactions.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" value="{{ request('search') }}"
                class="form-control me-2" placeholder="Cari transaksi...">
            <button class="btn btn-outline-secondary">Cari</button>
        </form>

        {{-- Tombol Tambah Transaksi --}}
        <a href="{{ route('transactions.create') }}" class="btn btn-success px-4">
            <i class="bi bi-plus-circle me-1"></i> Tambah Transaksi
        </a>
    </div>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-4">
            <table class="table table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Kasir</th>
                        <th>Total</th>
                        <th>Metode</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($transactions as $t)
                    <tr>
                        <td>{{ $t->id }}</td>
                        <td>{{ $t->user->name }}</td>
                        <td>Rp {{ number_format($t->trs_total,0,',','.') }}</td>
                        <td>{{ $t->payment_method }}</td>
                        <td>{{ $t->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <a href="{{ route('transactions.show', $t->id) }}"
                                class="btn btn-sm btn-info text-white px-3">Detail</a>

                            <a href="{{ route('transactions.print', $t->id) }}"
                                class="btn btn-sm btn-dark px-3" target="_blank">Print</a>

                            <form action="{{ route('transactions.destroy', $t->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Hapus transaksi ini?')"
                                    class="btn btn-sm btn-danger px-3">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-muted">Belum ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection