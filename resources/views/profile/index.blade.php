@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">

    <h4 class="fw-bold mb-4">Profil Akun</h4>

    {{-- ALERT SUCCESS --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- ALERT ERROR --}}
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">

        {{-- UPDATE PROFIL --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">Informasi Akun</h5>

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Perbarui Profil</button>
                    </form>

                </div>
            </div>
        </div>

        {{-- UPDATE PASSWORD --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">Ganti Password</h5>

                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Password Lama</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="confirm_new_password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-warning w-100">Perbarui Password</button>
                    </form>

                </div>
            </div>
        </div>

    </div>

</div>
@endsection
