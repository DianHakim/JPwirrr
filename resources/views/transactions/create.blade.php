@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="fw-bold mb-4">Transaksi Baru</h1>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-4">

            <form action="{{ route('transactions.store') }}" method="POST" id="trxForm" class="needs-validation" novalidate>
                @csrf

                <!-- HIDDEN DISCOUNT & CASH FIELDS -->
                <input type="hidden" name="trs_discount" id="trs_discount" value="0">
                <input type="hidden" name="cash" id="cashInput" value="0">

                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th width="150px">Qty</th>
                            <th width="200px">Harga</th>
                            <th width="200px">Subtotal</th>
                            <th width="80px">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="itemsBody">
                        <tr>
                            <td>
                                <select class="form-select productSelect" name="items[0][product_id]" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach ($products as $p)
                                    <option
                                        value="{{ $p->id }}"
                                        data-price="{{ $p->prd_price }}"
                                        data-stock="{{ $p->prd_stock }}">
                                        {{ $p->prd_name }} - Rp {{ number_format($p->prd_price, 0, ',', '.') }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Pilih produk terlebih dahulu.</div>
                            </td>

                            <td>
                                <input type="number" min="1" class="form-control qtyInput"
                                    name="items[0][qty]" value="1" required>
                                <div class="invalid-feedback">Isi qty minimal 1.</div>
                            </td>

                            <td>
                                <input type="number" class="form-control priceInput"
                                    name="items[0][price]" readonly>
                            </td>

                            <td>
                                <input type="number" class="form-control subtotalInput" readonly>
                            </td>

                            <td class="text-center">
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
                    <select class="form-select" name="payment_method" required>
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>
                <div class="text-end mt-3">
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary me-2">
                        Kembali
                    </a>
                    <button type="button" class="btn btn-success" id="openPayModal">
                        Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================== MODAL PEMBAYARAN ================== -->
<div class="modal fade" id="payModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header">
                <h5 class="modal-title">Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <label class="form-label fw-bold">Subtotal:</label>
                <div class="mb-2"><strong id="modalSubtotal" data-value="0">Rp 0</strong></div>

                <!-- DISKON -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Diskon</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input discountMode" type="radio" name="discount_mode" id="discount_none" value="none" checked>
                            <label class="form-check-label" for="discount_none">Tidak</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input discountMode" type="radio" name="discount_mode" id="discount_percent_radio" value="percent">
                            <label class="form-check-label" for="discount_percent_radio">Persen (%)</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input discountMode" type="radio" name="discount_mode" id="discount_nominal_radio" value="nominal">
                            <label class="form-check-label" for="discount_nominal_radio">Nominal (Rp)</label>
                        </div>
                    </div>

                    <div class="mt-2 d-flex gap-2">
                        <input type="number" class="form-control" id="discountPercent" placeholder="0 %" min="0" max="100" disabled>
                        <input type="number" class="form-control" id="discountNominal" placeholder="Rp 0" min="0" disabled>
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Total Setelah Diskon</label>
                    <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background:#f8f9fa;">
                        <div id="finalTotalText" class="fw-bold">Rp 0</div>
                        <div id="appliedDiscountText" class="text-muted">Diskon: Rp 0</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Uang Diterima</label>
                    <input type="number" class="form-control" id="payInput" placeholder="Masukkan nominal" min="0" required>
                    <div id="payError" class="text-danger mt-2 d-none">Nominal kurang dari total!</div>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-semibold">Kembalian</label>
                    <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background:#f8f9fa;">
                        <div id="changeText" class="fw-bold">Rp 0</div>
                        <div id="changeStatus" class="badge bg-secondary">Belum bayar</div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" id="confirmPayBtn">Konfirmasi Pembayaran</button>
            </div>
        </div>
    </div>
</div>

<!-- ================== SCRIPT ================== -->
<script>
    function formatRupiah(num) {
        if (!num || isNaN(num)) return 'Rp 0';
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
    }

    function toNumber(v) {
        const n = parseInt(v, 10);
        return isNaN(n) ? 0 : n;
    }

    let row = 1;

    document.getElementById('addRow').addEventListener('click', () => {
        const body = document.getElementById('itemsBody');
        const firstRow = body.children[0];
        const newRow = firstRow.cloneNode(true);
        newRow.querySelector('.productSelect').selectedIndex = 0;
        newRow.querySelector('.qtyInput').value = 1;
        newRow.querySelector('.priceInput').value = "";
        newRow.querySelector('.subtotalInput').value = "";
        newRow.querySelectorAll('select, input').forEach(el => {
            const name = el.getAttribute('name');
            if (name) el.setAttribute('name', name.replace(/\[\d+\]/, `[${row}]`));
        });
        body.appendChild(newRow);
        row++;
        calcAll();
    });

    function calcAll() {
        let total = 0;
        document.querySelectorAll('#itemsBody tr').forEach((tr) => {
            const select = tr.querySelector('.productSelect');
            let price = 0;
            if (select && select.selectedIndex > 0) {
                price = toNumber(select.selectedOptions[0].dataset.price);
            }
            let qty = toNumber(tr.querySelector('.qtyInput').value);
            if (qty < 1) {
                qty = 1;
                tr.querySelector('.qtyInput').value = 1;
            }
            tr.querySelector('.priceInput').value = price;
            tr.querySelector('.subtotalInput').value = price * qty;
            total += price * qty;
        });
        document.getElementById('totalText').textContent = formatRupiah(total);
        document.getElementById('modalSubtotal').dataset.value = total;
        document.getElementById('modalSubtotal').innerText = formatRupiah(total);
        recalcDiscountPreview();
    }

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('qtyInput')) {
            if (toNumber(e.target.value) < 1) e.target.value = 1;
            calcAll();
        }
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('productSelect')) {
            const tr = e.target.closest("tr");
            const selected = e.target.selectedOptions[0];
            if (!selected || selected.value === "") {
                tr.querySelector('.priceInput').value = "";
                tr.querySelector('.subtotalInput').value = "";
                calcAll();
                return;
            }
            let duplicate = false;
            document.querySelectorAll('.productSelect').forEach(s => {
                if (s !== e.target && s.value === e.target.value) duplicate = true;
            });
            if (duplicate) {
                alert('Produk sudah dipilih di baris lain!');
                e.target.selectedIndex = 0;
                tr.querySelector('.priceInput').value = "";
                tr.querySelector('.subtotalInput').value = "";
                calcAll();
                return;
            }
            const stock = toNumber(selected.getAttribute('data-stock') || 0);
            if (stock === 0) {
                alert('Stok produk habis!');
                e.target.selectedIndex = 0;
                tr.querySelector('.priceInput').value = "";
                tr.querySelector('.subtotalInput').value = "";
                calcAll();
                return;
            }
            tr.querySelector('.qtyInput').value = 1;
            tr.querySelector('.priceInput').value = selected.dataset.price;
            tr.querySelector('.subtotalInput').value = selected.dataset.price;
            calcAll();
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('removeRow')) {
            const rows = document.querySelectorAll('#itemsBody tr');
            if (rows.length > 1) {
                e.target.closest('tr').remove();
                calcAll();
            }
        }
    });

    // ================= MODAL LOGIC =================
    const payInput = document.getElementById('payInput');
    const payError = document.getElementById('payError');
    const changeText = document.getElementById('changeText');
    const changeStatus = document.getElementById('changeStatus');
    const modalSubtotalEl = document.getElementById('modalSubtotal');
    const discountNoneRadio = document.getElementById('discount_none');
    const discountPercentRadio = document.getElementById('discount_percent_radio');
    const discountNominalRadio = document.getElementById('discount_nominal_radio');
    const discountPercentInput = document.getElementById('discountPercent');
    const discountNominalInput = document.getElementById('discountNominal');
    const finalTotalText = document.getElementById('finalTotalText');
    const appliedDiscountText = document.getElementById('appliedDiscountText');

    document.getElementById('openPayModal').addEventListener('click', () => {
        const form = document.getElementById('trxForm');
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }
        calcAll();
        const totalNum = toNumber(modalSubtotalEl.dataset.value);
        modalSubtotalEl.innerText = formatRupiah(totalNum);
        discountNoneRadio.checked = true;
        discountPercentInput.value = '';
        discountNominalInput.value = '';
        discountPercentInput.disabled = true;
        discountNominalInput.disabled = true;
        payInput.value = '';
        payError.classList.add('d-none');
        changeText.innerText = formatRupiah(0);
        changeStatus.innerText = 'Belum bayar';
        changeStatus.className = 'badge bg-secondary';
        recalcDiscountPreview();
        const modal = new bootstrap.Modal(document.getElementById('payModal'));
        modal.show();
    });

    document.querySelectorAll('.discountMode').forEach(r => {
        r.addEventListener('change', () => {
            if (discountPercentRadio.checked) {
                discountPercentInput.disabled = false;
                discountNominalInput.disabled = true;
                discountNominalInput.value = '';
            } else if (discountNominalRadio.checked) {
                discountPercentInput.disabled = true;
                discountNominalInput.disabled = false;
                discountPercentInput.value = '';
            } else {
                discountPercentInput.disabled = true;
                discountNominalInput.disabled = true;
                discountPercentInput.value = '';
                discountNominalInput.value = '';
            }
            recalcDiscountPreview();
        });
    });

    function recalcDiscountPreview() {
        const total = toNumber(modalSubtotalEl.dataset.value);
        let discountValue = 0;
        if (discountPercentRadio.checked) {
            const pct = toNumber(discountPercentInput.value);
            discountValue = Math.floor(total * pct / 100);
            appliedDiscountText.innerText = `Diskon: ${pct}% → ${formatRupiah(discountValue)}`;
        } else if (discountNominalRadio.checked) {
            discountValue = toNumber(discountNominalInput.value);
            appliedDiscountText.innerText = `Diskon: ${formatRupiah(discountValue)}`;
        } else {
            appliedDiscountText.innerText = 'Diskon: Rp 0';
        }
        const finalTotal = Math.max(total - discountValue, 0);
        finalTotalText.innerText = formatRupiah(finalTotal);
        updateChangePreview();
    }

    discountPercentInput.addEventListener('input', recalcDiscountPreview);
    discountNominalInput.addEventListener('input', recalcDiscountPreview);

    function updateChangePreview() {
        const finalTotal = toNumber(finalTotalText.innerText.replace(/\D/g, ''));
        const bayar = toNumber(payInput.value);
        const kembali = bayar - finalTotal;
        changeText.innerText = formatRupiah(Math.max(kembali, 0));
        if (bayar >= finalTotal && bayar > 0) {
            changeStatus.innerText = 'Cukup';
            changeStatus.className = 'badge bg-success';
            payError.classList.add('d-none');
        } else if (bayar > 0 && bayar < finalTotal) {
            changeStatus.innerText = 'Kurang';
            changeStatus.className = 'badge bg-danger';
            payError.classList.remove('d-none');
        } else {
            changeStatus.innerText = 'Belum bayar';
            changeStatus.className = 'badge bg-secondary';
        }
    }

    payInput.addEventListener('input', updateChangePreview);

    document.getElementById('confirmPayBtn').addEventListener('click', () => {
        const finalTotal = toNumber(finalTotalText.innerText.replace(/\D/g, ''));
        const bayar = toNumber(payInput.value);
        if (bayar < finalTotal) {
            payError.classList.remove('d-none');
            return;
        }

        // update cash hidden
        document.getElementById('cashInput').value = bayar;

        // RESET SEMUA DISCOUNT HIDDEN
        let dNominal = 0;
        const subtotal = toNumber(modalSubtotalEl.dataset.value);

        // persen
        if (discountPercentRadio.checked) {
            const pct = toNumber(discountPercentInput.value);
            dNominal = Math.floor(subtotal * pct / 100);
        }

        // nominal
        if (discountNominalRadio.checked) {
            dNominal = toNumber(discountNominalInput.value);
        }

        // kirim hanya trs_discount
        document.getElementById('trs_discount').value = dNominal;

        // === SUBMIT FORM DI SINI ===
        document.getElementById('trxForm').submit();
    });

    calcAll();
</script>
@endsection