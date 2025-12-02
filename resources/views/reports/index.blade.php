@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h2 class="fw-bold mb-4">Laporan Penjualan</h2>

    <form method="GET" class="mb-4 d-flex gap-3 align-items-end">

        {{-- FILTER PER BULAN --}}
        <div>
            <label class="form-label">Per Bulan</label>
            <select name="month" id="monthSelect" class="form-select" style="width:150px">
                <option value="">-- Pilih Bulan --</option>
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                    </option>
                @endfor
            </select>
        </div>

        {{-- FILTER PER TAHUN --}}
        <div>
            <label class="form-label">Per Tahun</label>
            <select name="year" id="yearSelect" class="form-select" style="width:150px">
                <option value="">-- Pilih Tahun --</option>
                @for ($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>
        </div>

        <button class="btn btn-primary">Filter</button>

        {{-- EXPORT PDF --}}
        <a href="{{ route('reports.pdf', [
                'month' => request('month'),
                'year'  => request('year')
            ]) }}"
            class="btn btn-danger">
            Export PDF
        </a>
    </form>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body">

            <h5>
                Total Pendapatan:
                <strong>Rp {{ number_format($totalIncome, 0, ',', '.') }}</strong>
            </h5>

            <hr>

            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 140px">Tanggal</th>
                        <th style="width: 200px">Kode Transaksi</th>
                        <th>Produk</th>
                        <th style="width: 150px">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $r)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($r->dtr_period)->format('d M Y') }}</td>
                            <td>{{ $r->transaction->trs_code }}</td>
                            <td>{{ $r->product->prd_name ?? $r->product_name }}</td>
                            <td>Rp {{ number_format($r->dtr_subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Tidak ada data laporan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>

{{-- AUTO CLEAR BULAN KETIKA PILIH TAHUN --}}
<script>
    document.getElementById('yearSelect').addEventListener('change', function () {
        if (this.value !== "") {
            document.getElementById('monthSelect').value = "";
        }
    });
</script>
@endsection

