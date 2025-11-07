<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JP Wear</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- ✅ Navbar with Login button -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light px-4">
        <a class="navbar-brand fw-bold">JP Wear</a>

        <div class="ms-auto">
            <a href="{{ url('/login') }}" class="btn btn-primary">
                Login
            </a>
        </div>
    </nav>

    <!-- ✅ Content -->
    <div class="container text-center mt-5">
        <h1>Welcome to JP Wear</h1>
        <p class="text-muted">Sistem Kasir Digital</p>
    </div>

</body>
</html>
