<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - {{ $month }}</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th {
            padding: 6px;
            background: #eee;
        }

        td {
            padding: 5px;
        }

        .summary {
            margin-top: 20px;
            font-size: 14px;
        }

    </style>
</head>

<body>

    <div class="title">Laporan Penjualan<br>Bulan: {{ $month }}</div>

    <div class="summary">
        <strong>Total Pendapatan: Rp {{ number_format($totalIncome, 0, ',', '.') }}</strong>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kode Transaksi</th>
                <th>Produk</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reports as $r)
                <tr>
                    <td>{{ $r->dtr_period }}</td>
                    <td>{{ $r->transaction->trs_code }}</td>
                    <td>{{ $r->product->prd_name ?? $r->product_name }}</td>
                    <td>Rp {{ number_format($r->dtr_subtotal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
