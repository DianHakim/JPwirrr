@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h4 class="fw-bold mb-4">Transaksi Baru</h4>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-4">

            <form action="{{ route('transactions.store') }}" method="POST" id="trxForm">
                @csrf

                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th width="150px">Qty</th>
                            <th width="200px">Harga</th>
                            <th width="200px">Subtotal</th>
                            <th width="80px">#</th>
                        </tr>
                    </thead>

                    <tbody id="itemsBody">
                        <tr>
                            <td>
                                <select class="form-select productSelect" name="items[0][product_id]" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach ($products as $p)
                                        <option value="{{ $p->id }}" data-price="{{ $p->prd_price }}">
                                            {{ $p->prd_name }} - Rp {{ number_format($p->prd_price, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input type="number" min="1" class="form-control qtyInput" 
                                       name="items[0][qty]" value="1" required>
                            </td>

                            <td>
                                <input type="number" class="form-control priceInput" 
                                       name="items[0][price]" readonly>
                            </td>

                            <td>
                                <input type="number" class="form-control subtotalInput"
                                       name="items[0][subtotal]" readonly>
                            </td>

                            <td>
                                <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <button type="button" id="addRow" class="btn btn-primary mb-3">Tambah Baris</button>

                <div class="text-end">
                    <h4>Total: <span id="totalText">Rp 0</span></h4>
                </div>

                <div class="mb-3">
                    <label>Metode Pembayaran</label>
                    <select class="form-select" name="payment_method">
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success w-100 py-2">Simpan Transaksi</button>
            </form>
        </div>
    </div>
</div>

<script>
let row = 1;

// =========================
// TAMBAH BARIS
// =========================
document.getElementById('addRow').addEventListener('click', () => {
    const body = document.getElementById('itemsBody');
    const firstRow = body.children[0];
    const newRow = firstRow.cloneNode(true);

    // Reset semua input
    newRow.querySelector('.productSelect').selectedIndex = 0;
    newRow.querySelector('.qtyInput').value = 1;
    newRow.querySelector('.priceInput').value = "";
    newRow.querySelector('.subtotalInput').value = "";

    // Update name index
    newRow.querySelectorAll('select, input').forEach(el => {
        const name = el.getAttribute('name');
        el.setAttribute('name', name.replace(/\[\d+\]/, `[${row}]`));
    });

    body.appendChild(newRow);
    row++;
});

// =========================
// HITUNG TOTAL
// =========================
function calcAll() {
    let total = 0;

    document.querySelectorAll('#itemsBody tr').forEach((tr) => {

        const select = tr.querySelector('.productSelect');
        let price = 0;

        if (select && select.selectedIndex > 0) {
            price = parseInt(select.selectedOptions[0].dataset.price || 0);
        }

        let qty = parseInt(tr.querySelector('.qtyInput').value || 0);

        tr.querySelector('.priceInput').value = price;
        tr.querySelector('.subtotalInput').value = price * qty;

        total += price * qty;
    });

    document.getElementById('totalText').textContent = 
        "Rp " + new Intl.NumberFormat().format(total);
}

// Listener supaya auto hitung
document.addEventListener('input', calcAll);
document.addEventListener('change', calcAll);

// =========================
// HAPUS BARIS
// =========================
document.addEventListener('click', function(e){
    if(e.target.classList.contains('removeRow')){
        const rows = document.querySelectorAll('#itemsBody tr');

        if(rows.length > 1){
            e.target.closest('tr').remove();
            calcAll();
        }
    }
});
</script>
@endsection
