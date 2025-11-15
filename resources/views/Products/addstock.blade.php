@extends('layouts.app')

@section('content')
<div class="container p-4">

    <h1 class="fw-bold mb-3">Tambah Stok Produk</h1>

    <div class="card shadow p-4">
        <form action="{{ route('products.addstock.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Pilih Produk</label>
                <select name="product_id" class="form-control" required>
                    <option value=""> Pilih Produk </option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">
                            {{ $product->prd_name }} (Stok: {{ $product->prd_stock }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Jumlah yang Ditambahkan</label>
                <input type="number" name="amount" class="form-control" min="1" required>
            </div>

            <button type="submit" class="btn btn-success px-4">Tambah Stok</button>
        </form>
    </div>

</div>
@endsection
