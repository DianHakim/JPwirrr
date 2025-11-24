@extends('layouts.app')

@section('content')
<div class="container-fluid bg-light-purple min-vh-100 px-4">
    <h1 class="fw-bold mb-3">Tambah Kategori</h1>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-4">

            {{-- FORM --}}
            <form action="{{ route('productcategory.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama kategori</label>
                    <input 
                        type="text" 
                        name="name" 
                        class="form-control rounded-3 @error('name') is-invalid @enderror"
                        placeholder="Masukkan nama kategori"
                        value="{{ old('name') }}"
                        required
                    >
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('productcategory.index') }}" class="btn btn-secondary rounded-pill px-4">Kembali</a>
                    <button type="submit" class="btn btn-success rounded-pill px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DUPLIKAT --}}
@if($errors->has('name'))
<div class="modal fade show" id="duplicateModal" tabindex="-1" style="display:block; background:rgba(0,0,0,.5);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header bg-danger text-white rounded-top-4">
                <h5 class="modal-title fw-bold">Kategori Sudah Ada</h5>
            </div>
            <div class="modal-body">
                <p class="mb-0">Nama kategori <b>"{{ old('name') }}"</b> sudah terdaftar. Silakan gunakan nama lain.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeDuplicate()">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function closeDuplicate() {
        document.getElementById('duplicateModal').style.display = 'none';
    }
</script>
@endif
@endsection
