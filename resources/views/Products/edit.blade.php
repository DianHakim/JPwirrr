@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h4 class="fw-bold mb-3">Edit Produk</h4>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="prd_name" value="{{ old('prd_name', $product->prd_name) }}" class="form-control" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="prd_price" value="{{ old('prd_price', $product->prd_price) }}" class="form-control" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Warna</label>
                <input type="text" name="prd_color" value="{{ old('prd_color', $product->prd_color) }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Ukuran</label>
                <input type="text" name="prd_size" value="{{ old('prd_size', $product->prd_size) }}" class="form-control">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="pdc_id" class="form-select" required>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $cat->id == $product->pdc_id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Foto Produk</label><br>
            @if($product->prd_photo)
                <img src="{{ asset('storage/'.$product->prd_photo) }}" width="80" height="80" class="rounded mb-2"><br>
            @endif
            <input type="file" name="prd_photo" class="form-control" accept="image/*">
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-success">Perbarui</button>
        </div>
    </form>
</div>
@endsection
