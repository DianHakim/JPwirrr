@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="fw-bold mb-3">Tambah Produk</h1>

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

    {{-- ===========================
        FORM TAMBAH PRODUK
    ============================ --}}
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white p-4 rounded shadow-sm">

        @csrf

        {{-- NAMA --}}
        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="prd_name" class="form-control"
                   required value="{{ old('prd_name') }}">
        </div>

        {{-- STOK & HARGA --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="prd_stock" class="form-control"
                       required value="{{ old('prd_stock') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="prd_price" class="form-control"
                       required value="{{ old('prd_price') }}">
            </div>
        </div>

        {{-- WARNA & UKURAN --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Warna</label>
                <input type="text" name="prd_color" class="form-control"
                       value="{{ old('prd_color') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Ukuran</label>
                <input type="text" name="prd_size" class="form-control"
                       value="{{ old('prd_size') }}">
            </div>
        </div>

        {{-- KATEGORI --}}
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="pdc_id" class="form-select" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ old('pdc_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- FOTO --}}
        <div class="mb-3">
            <label class="form-label">Foto Produk</label>
            <input type="file" name="prd_photo" class="form-control" accept="image/*">
        </div>

        {{-- BUTTON --}}
        <div class="d-flex justify-content-between">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>

    </form>
</div>
@endsection
