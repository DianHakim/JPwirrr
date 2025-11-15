@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="fw-bold mb-3">Kategori</h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
        {{-- Search --}}
        <form action="{{ route('productcategory.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control form-control-sm me-2"
                placeholder="Cari kategori..."
                value="{{ request('search') }}">
            <button type="submit" class="btn btn-outline-success">Cari</button>
        </form>

        <a href="{{ route('productcategory.create') }}" class="btn btn-primary ms-3">Tambah Kategori</a>
    </div>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-4">

            {{-- Alert asli disembunyikan --}}
            @if(session('success'))
            <div class="alert alert-success d-none">{{ session('success') }}</div>
            @endif

            <table class="table align-middle table-hover text-center">
                <thead class="table-light">
                    <tr>
                        <th style="width: 10%">ID</th>
                        <th>Nama Kategori</th>
                        <th style="width: 25%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $category->name }}</td>

                        <td>
                            <a href="{{ route('productcategory.edit', $category->id) }}"
                                class="btn btn-sm btn-warning px-3 me-2">Edit</a>

                            <button class="btn btn-sm btn-danger px-3"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal"
                                data-id="{{ $category->id }}">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-muted">Belum ada kategori</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>


{{-- ================================================== --}}
{{-- MODAL NOTIFIKASI SUKSES --}}
{{-- ================================================== --}}
@if(session('success'))
<div class="modal fade" id="notifModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Berhasil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="fs-5 mb-0">{{ session('success') }}</p>
            </div>
            <div class="modal-footer border-0">
                <button class="btn btn-success w-100" data-bs-dismiss="modal">Oke</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        new bootstrap.Modal(document.getElementById('notifModal')).show();
    });
</script>
@endif


{{-- ================================================== --}}
{{-- MODAL KONFIRMASI HAPUS --}}
{{-- ================================================== --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- PERHATIKAN: ID FORM SUDAH BENAR --}}
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')

                <div class="modal-body text-center py-4">
                    <p class="fs-5">Yakin ingin menghapus kategori ini?</p>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    const deleteModal = document.getElementById('deleteModal');

    deleteModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const form = document.getElementById('deleteForm');

        form.action = "{{ route('productcategory.destroy', ':id') }}".replace(':id', id);
    });
</script>

@endsection
