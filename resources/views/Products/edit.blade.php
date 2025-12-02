@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="fw-bold mb-3">Edit Produk</h1>

    {{-- ===========================
        MODAL DUPLIKAT PRODUK
    ============================ --}}
    @if ($errors->has('duplicate'))
    <div class="modal fade show" id="duplicateModal" tabindex="-1"
        style="display:block; background:rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow">

                <div class="modal-header bg-danger text-white rounded-top-4">
                    <h5 class="modal-title fw-bold">Produk Duplikat</h5>
                </div>

                <div class="modal-body">
                    <p class="mb-0">{{ $errors->first('duplicate') }}</p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger"
                        onclick="document.getElementById('duplicateModal').style.display='none'">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="prd_name" value="{{ old('prd_name', $product->prd_name) }}" class="form-control" required>
        </div>

        <div class="row">
            <div class="mb-3">
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