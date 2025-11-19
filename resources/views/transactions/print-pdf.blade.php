<!DOCTYPE html>
<html>
<head>
    <title>Cetak Struk</title>
    <style>
        body { font-family: monospace; font-size: 14px; width: 280px; margin: 0 auto; }
        .center { text-align: center; }
        .line { border-bottom: 1px solid #000; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="center">
        <img src="{{ public_path('img/logo.png') }}" width="90" style="margin-bottom:10px;">
        <div class="line"></div>
    </div>

    <table style="margin-bottom:5px; font-size:13px;">
        <tr><td>Kode transaksi :</td><td class="right">{{ $transaction->trs_code }}</td></tr>
        <tr><td>Tanggal :</td><td class="right">{{ $transaction->created_at->format('d-m-Y H:i') }}</td></tr>
        <tr><td>Kasir :</td><td class="right">{{ $transaction->user->name }}</td></tr>
    </table>

    <div class="line"></div>

    <table style="font-size:13px; margin-bottom:5px;">
        @foreach($transaction->details as $d)
        <tr>
            <td>{{ $d->qty }} x</td>
            <td>{{ $d->product->prd_name ?? $d->product_name }}</td>
            <td class="right">Rp {{ number_format($d->subtotal,0,',','.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <table style="font-size:13px;">
        <tr>
            <td>Subtotal :</td>
            <td class="right">Rp {{ number_format($transaction->trs_subtotal,0,',','.') }}</td>
        </tr>

        <tr>
            <td>Diskon :</td>
            <td class="right">
                @if($transaction->trs_discount > 0)
                    - Rp {{ number_format($transaction->trs_discount,0,',','.') }}
                @else
                    Rp 0
                @endif
            </td>
        </tr>

        <tr class="bold">
            <td>Total :</td>
            <td class="right">Rp {{ number_format($transaction->trs_total,0,',','.') }}</td>
        </tr>

        <tr>
            <td>Tunai :</td>
            <td class="right">Rp {{ number_format($transaction->cash ?? 0,0,',','.') }}</td>
        </tr>

        <tr>
            <td>Kembali :</td>
            <td class="right">Rp {{ number_format($transaction->change ?? 0,0,',','.') }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="center" style="font-size:12px;">
        Terima kasih telah berbelanja di JP Wear.<br>
        Follow Instagram kami @jpwear.id untuk info promo & koleksi baru!
    </div>
</body>
</html>
