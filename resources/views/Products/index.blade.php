@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="fw-bold mb-3">Produk</h1>

    <div class="d-flex justify-content-between align-items-center mb-3">

        {{-- Form search --}}
        <form action="{{ route('products.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" value="{{ $search }}" class="form-control me-2" placeholder="Cari Produk">
            <button type="submit" class="btn btn-outline-secondary">Cari</button>
        </form>

        {{-- Tombol kanan --}}
        <div class="d-flex gap-2">
            <a href="{{ route('products.stockhistory') }}" class="btn btn-dark">History Stok</a>
            <a href="{{ route('products.addstock') }}" class="btn btn-info">Tambah Stok</a>
            <a href="{{ route('products.create') }}" class="btn btn-primary">Tambah Produk</a>
        </div>

    </div>

    {{-- Alert asli (disembunyikan) --}}
    @if(session('success'))
    <div class="alert alert-success d-none">{{ session('success') }}</div>
    @endif

    {{-- TABEL PRODUK MODERN --}}
    <div class="table-responsive bg-white rounded shadow-sm p-3">
        <table class="table align-middle table-hover text-center">
            <thead class="table-light">
                <tr>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Stok</th>
                    <th>Warna</th>
                    <th>Ukuran</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th class="width: 25%">Aksi</th>
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

                    {{-- STOK DENGAN WARNA --}}
                    <td>
                        @if($product->prd_stock == 0)
                        <span class="badge bg-danger">Habis</span>
                        @elseif($product->prd_stock < 10)
                            <span class="badge bg-danger text-dark">{{ $product->prd_stock }}</span>
                            @else
                            <span class="badge bg-success">{{ $product->prd_stock }}</span>
                            @endif
                    </td>

                    <td>{{ $product->prd_color ?? '-' }}</td>
                    <td>{{ $product->prd_size ?? '-' }}</td>

                    {{-- KATEGORI DENGAN BADGE WARNA --}}
                    <td>
                        @php
                        $cat = $product->category->name ?? '-';
                        $color = match($cat) {
                        default => 'purple',
                        };
                        @endphp
                        <span class="badge bg-{{ $color }}">{{ $cat }}</span>
                    </td>

                    <td>Rp {{ number_format($product->prd_price, 0, ',', '.') }}</td>

                    <td class="text-center">
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning me-2">Edit</a>

                        {{-- TOMBOL HAPUS PAKAI MODAL --}}
                        <button class="btn btn-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteModal"
                            data-id="{{ $product->id }}">
                            Hapus
                        </button>
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

{{-- ================================================== --}}
{{-- MODAL NOTIFIKASI SUKSES (TAMBAH / HAPUS) --}}
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
                <p class="mb-0 fs-5">{{ session('success') }}</p>
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

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')

                <div class="modal-body text-center py-4">
                    <p class="fs-5">Yakin ingin menghapus produk ini?</p>
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

        // auto generate URL sesuai route web.php
        form.action = "{{ route('products.destroy', ':id') }}".replace(':id', id);
    });
</script>


{{-- CUSTOM BADGE WARNA UNGU --}}
<style>
    .badge.bg-purple {
        background-color: #6f42c1 !important;
    }
</style>

@endsection