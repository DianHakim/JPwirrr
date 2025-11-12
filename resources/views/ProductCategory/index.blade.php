@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
        <h4 class="fw-bold mb-3">Kategori</h4>

        <div class="d-flex justify-content-between align-items-center mb-3">
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
            @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            <table class="table align-middle text-center table-hover">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 10%">#</th>
                        <th scope="col">Nama</th>
                        <th scope="col" style="width: 25%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $index => $category)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $category->name }}</td>
                        <td>
                            <a href="{{ route('productcategory.edit', $category->id) }}" class="btn btn-sm btn-warning px-3 me-2">Edit</a>
                            <form action="{{ route('productcategory.destroy', $category->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Yakin mau hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger px-3">Hapus</button>
                            </form>
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
@endsection