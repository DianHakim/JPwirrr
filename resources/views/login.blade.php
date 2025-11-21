<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>JP Wear Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
        }

        .login-card {
            max-width: 420px;
            margin: 80px auto;
            padding: 40px 35px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            background-color: #fff;
        }

        .logo-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 12px;
        }

        .btn-login {
            width: 100%;
            background-color: #1E2A78;
            color: white;
            font-weight: 600;
        }

        .btn-login:hover {
            background-color: #F1C40F;
        }
    </style>
</head>

<body>
    <div class="login-card text-center">

        <div class="mb-3">
            <img src="{{ asset('img/logo.png') }}" alt="JP Wear Logo" class="logo-img mb-2">
        </div>

        {{-- Error Alert --}}
        @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login.process') }}">
            @csrf

            <div class="text-start mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input type="email" class="form-control" name="email" id="email"
                    placeholder="Masukan Email" required />
            </div>

            <div class="text-start mb-3">
                <label for="password" class="form-label fw-semibold">Password</label>
                <input type="password" class="form-control" name="password" id="password"
                    placeholder="Masukan Password" required />
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" />
                    <label class="form-check-label" for="remember">Ingat perangkat ini</label>
                </div>
            </div>

            <button type="submit" class="btn btn-login py-2">Login</button>
        </form>

    </div>
</body>

</html>