<!DOCTYPE html>
<html>
<head>
    <title>Struk Transaksi</title>
    <style>
        body { font-family: monospace; font-size: 14px; }
        .center { text-align: center; }
        .line { border-bottom: 1px dashed #000; margin: 8px 0; }
        table { width: 100%; }
    </style>
</head>
<body onload="window.print()">

    <div class="center">
        <strong>TOKO ANDA</strong><br>
        Jl. Contoh Alamat No.1<br>
        -------------------------------
    </div>

    <p>ID: {{ $transaction->id }} <br>
       Tanggal: {{ $transaction->created_at->format('d/m/Y H:i') }} <br>
       Kasir: {{ $transaction->user->name }}</p>

    <div class="line"></div>

    <table>
        @foreach($transaction->details as $d)
        <tr>
            <td>{{ $d->product->name }}</td>
            <td class="center">{{ $d->qty }}x</td>
            <td style="text-align:right">Rp {{ number_format($d->subtotal,0,',','.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <p><strong>Total: Rp {{ number_format($transaction->trs_total,0,',','.') }}</strong></p>

    <div class="center">Terima Kasih!</div>

</body>
</html>
