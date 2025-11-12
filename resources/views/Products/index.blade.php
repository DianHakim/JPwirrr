@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h4 class="fw-bold mb-3">Produk</h4>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <form action="{{ route('products.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" value="{{ $search }}" class="form-control me-2" placeholder="Cari Produk">
            <button type="submit" class="btn btn-outline-success">Cari</button>
        </form>
        <a href="{{ route('products.create') }}" class="btn btn-primary">Tambah Produk</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive bg-white rounded shadow-sm p-3">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Stok</th>
                    <th>Warna</th>
                    <th>Ukuran</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                <tr>
                    <td>
                        @if($product->prd_photo)
                        <img src="{{ asset('storage/'.$product->prd_photo) }}" width="50" height="50" class="rounded">
                        @else
                        <img src="https://via.placeholder.com/50" class="rounded" alt="no image">
                        @endif
                    </td>
                    <td>{{ $product->prd_name }}</td>
                    <td>{{ $product->prd_stock }}</td>
                    <td>{{ $product->prd_color ?? '-' }}</td>
                    <td>{{ $product->prd_size ?? '-' }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td>Rp {{ number_format($product->prd_price, 0, ',', '.') }}</td>
                    <td class="text-end">
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus produk ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">Tidak ada produk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $products->links() }}
    </div>
</div>
@endsection