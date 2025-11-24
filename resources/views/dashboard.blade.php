@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">

    <h1 class="fw-bold mb-4">Dashboard</h1>

    {{-- ====== STATISTICS CARDS ====== --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow border-0 rounded-4 p-3">
                <h6 class="text-secondary">📦Total Produk</h6>
                <h2 class="fw-bold">{{ $totalProducts }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow border-0 rounded-4 p-3">
                <h6 class="text-secondary">📄Total Transaksi</h6>
                <h2 class="fw-bold">{{ $totalTransactions }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow border-0 rounded-4 p-3">
                <h6 class="text-secondary">💰Pendapatan</h6>
                <h2 class="fw-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>

    {{-- ====== MAIN CHARTS ====== --}}
    <div class="row">
        <style>
            .chart-wrapper {
                height: 280px;
                position: relative;
            }
            .pie-wrapper {
                height: 260px;
                position: relative;
            }
        </style>

        <div class="col-lg-8 mb-4">
            <div class="card shadow border-0 rounded-4 p-4">
                <h5 class="mb-3 fw-bold">Grafik Penjualan Bulanan</h5>
                <div class="chart-wrapper">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow border-0 rounded-4 p-4">
                <h5 class="fw-bold mb-3">Kategori Produk</h5>
                <div class="pie-wrapper">
                    <canvas id="kategoriChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ====== TOP PRODUCTS ====== --}}
    <div class="card shadow border-0 rounded-4 p-4 mt-4">
        <h5 class="fw-bold mb-3">Produk Terlaris</h5>

        <table class="table table-borderless align-middle">
            <thead class="table-light">
                <tr>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Terjual</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($topProducts as $item)
                    <tr>
                        <td>{{ $item->product->prd_name }}</td>
                        <td>{{ $item->product->category->name ?? '-' }}</td>
                        <td>{{ $item->total_qty }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const salesLabels = @json(array_keys($salesMonthly));
    const salesData   = @json(array_values($salesMonthly));

    const kategoriLabels = @json($kategoriChart->pluck('name'));
    const kategoriCounts = @json($kategoriChart->pluck('products_count'));

    // GRAFIK PENJUALAN
    new Chart(document.getElementById('salesChart'), {
        type: 'bar',
        data: {
            labels: salesLabels,
            datasets: [{
                label: 'Total Penjualan',
                data: salesData,
                backgroundColor: 'rgba(54,162,235,0.4)',
                borderColor: 'rgba(54,162,235,1)',
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // PIE CHART
    new Chart(document.getElementById('kategoriChart'), {
        type: 'pie',
        data: {
            labels: kategoriLabels,
            datasets: [{
                data: kategoriCounts,
                backgroundColor: [
                    'rgba(255,99,132,0.6)',
                    'rgba(54,162,235,0.6)',
                    'rgba(255,206,86,0.6)',
                    'rgba(75,192,192,0.6)',
                    'rgba(153,102,255,0.6)',
                    'rgba(255,159,64,0.6)',
                ]
            }]
        },
        options: {
            maintainAspectRatio: false
        }
    });
</script>
@endsection
