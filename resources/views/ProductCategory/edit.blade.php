@extends('layouts.app')

@section('content')
<div class="container-fluid bg-light-purple min-vh-100 px-4">
    <h1 class="fw-bold mb-3">Edit Kategori</h1>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('productcategory.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama kategori</label>
                    <input type="text" name="name" class="form-control rounded-3" value="{{ $category->name }}" required>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('productcategory.index') }}" class="btn btn-secondary rounded-pill px-4">Kembali</a>
                    <button type="submit" class="btn btn-success rounded-pill px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
