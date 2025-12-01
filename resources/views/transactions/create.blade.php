@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="fw-bold mb-4">Transaksi Baru</h1>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-4">

            <form action="{{ route('transactions.store') }}" method="POST" id="trxForm" class="needs-validation" novalidate>
                @csrf

                <input type="hidden" name="trs_discount" id="trs_discount" value="0">
                <input type="hidden" name="cash" id="cashInput" value="0">

                {{-- ================= GLOBAL SEARCH PRODUK (DI ATAS TABLE) ================= --}}
                <div class="mb-3 position-relative">
                    <input type="text" id="globalProductSearch" class="form-control"
                        placeholder="Cari produk... (ketik nama lalu pilih dari daftar)" autocomplete="off">

                    <ul class="list-group position-absolute w-100 d-none"
                        id="globalProductList"
                        style="z-index:10; max-height:200px; overflow-y:auto;">
                        @foreach ($products as $p)
                        <li class="list-group-item globalProductItem"
                            data-id="{{ $p->id }}"
                            data-name="{{ $p->prd_name }}"
                            data-price="{{ $p->prd_price }}"
                            data-stock="{{ $p->prd_stock }}">
                            {{ $p->prd_name }} - Rp {{ number_format($p->prd_price, 0, ',', '.') }}
                        </li>
                        @endforeach
                    </ul>
                </div>

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
                    </tbody>
                </table>

                {{-- NOTE: tombol "Tambah Baris" dihapus sesuai permintaan (otomatis tambah saat pilih produk) --}}

                <div class="text-end">
                    <h4>Total: <span id="totalText">Rp 0</span></h4>
                </div>

                <div class="mb-3">
                    <label>Metode Pembayaran</label>
                    <select class="form-select" name="payment_method" required>
                        <option value="cash">Cash</option>
                        <option value="qris">Qris</option>
                    </select>
                </div>

                <div id="qrisImageBox" class="text-center mb-3 d-none">
                    <img src="/img/dana.png" alt="QRIS" class="img-fluid rounded" style="max-width:310px;">
                    <p class="mt-2 fw-bold text-secondary">Scan untuk membayar</p>
                </div>

                <!-- TOMBOL DIPINDAH KE PALING BAWAH DALAM CARD -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary px-4">
                        Kembali
                    </a>
                    <button type="button" class="btn btn-success px-4" id="openPayModal">
                        Pembayaran
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- ================== MODAL STOK HANYA SISA ================== -->
<div class="modal fade" id="qtyStockModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Stok Tidak Cukup</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="qtyStockMessage" class="mb-0"></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-bs-dismiss="modal">OK</button>
            </div>
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

                <!-- SUBTOTAL -->
                <label class="form-label fw-bold">Subtotal:</label>
                <div class="mb-2">
                    <strong id="modalSubtotal" data-value="0">Rp 0</strong>
                </div>

                <!-- DISKON -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Diskon</label>

                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input discountMode" type="radio"
                                name="discount_mode" id="discount_none" value="none" checked>
                            <label class="form-check-label" for="discount_none">Tidak</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input discountMode" type="radio"
                                name="discount_mode" id="discount_percent_radio" value="percent">
                            <label class="form-check-label" for="discount_percent_radio">Persen (%)</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input discountMode" type="radio"
                                name="discount_mode" id="discount_nominal_radio" value="nominal">
                            <label class="form-check-label" for="discount_nominal_radio">Nominal (Rp)</label>
                        </div>
                    </div>

                    <div class="mt-2 d-flex gap-2">
                        <input type="number" class="form-control" id="discountPercent"
                            placeholder="0 %" min="0" max="100" disabled>

                        <input type="number" class="form-control" id="discountNominal"
                            placeholder="Rp 0" min="0" disabled>
                    </div>
                </div>

                <hr>

                <!-- TOTAL DISKON -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Total Setelah Diskon</label>

                    <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background:#f8f9fa;">
                        <div id="finalTotalText" class="fw-bold">Rp 0</div>
                        <div id="appliedDiscountText" class="text-muted">Diskon: Rp 0</div>
                    </div>
                </div>

                <!-- INPUT PEMBAYARAN -->
                <div class="mb-3">
                    <label class="form-label">Uang Diterima</label>

                    <input type="number" class="form-control" id="payInput"
                        placeholder="Masukkan nominal" min="0" required>

                    <!-- ERROR: BAYAR < TOTAL -->
                    <div id="payError" class="text-danger mt-2 d-none">
                        Nominal kurang dari total!
                    </div>

                    <!-- ERROR: MAX LIMIT 1 MILIAR -->
                    <div id="cashLimitText" class="text-success mt-2 d-none">
                        Nominal pembayaran tidak boleh lebih dari 1.000.000.000 (1 miliar). Mohon kurangi.
                    </div>
                </div>

                <!-- KEMBALIAN -->
                <div class="mb-2">
                    <label class="form-label fw-semibold">Kembalian</label>

                    <div class="d-flex justify-content-between align-items-center p-2 rounded"
                        style="background:#f8f9fa;">
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
    /* ============================================================
       FORMAT & UTIL
    ============================================================*/
    function formatRupiah(num) {
        if (!num || isNaN(num)) return 'Rp 0';
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
    }

    function toNumber(v) {
        const n = parseInt(v, 10);
        return isNaN(n) ? 0 : n;
    }

    let globalRowIndex = 1;

    /* ============================================================
       TAMBAH BARIS DARI SELECTION GLOBAL
       - hanya global search yg aktif
       - per-row search readonly & hidden
    ============================================================*/
    function addNewRowFromSelection(data) {

        const id = data.id;
        const name = data.name;
        const price = parseInt(data.price);
        const stock = parseInt(data.stock);

        // Cek duplikat
        let duplicate = false;
        document.querySelectorAll('.productSelect').forEach(s => {
            if (s.value == id) duplicate = true;
        });
        if (duplicate) {
            alert("Produk sudah ada di tabel!");
            return;
        }

        const body = document.getElementById('itemsBody');

        // Buat row baru via JS
        const tr = document.createElement('tr');
        tr.classList.add('item-row');

        tr.innerHTML = `
        <td>
            <select class="form-select productSelect d-none" name="items[${globalRowIndex}][product_id]" required>
            <option value="${id}" data-stock="${stock}" data-price="${price}" selected> ${name}</option>
            </select>

            <input type="text" class="form-control productSearch" value="${name}" readonly>
        </td>

        <td>
            <input type="number" min="1" class="form-control qtyInput"
                name="items[${globalRowIndex}][qty]" value="1" required>
        </td>

        <td>
            <input type="number" class="form-control priceInput"
                name="items[${globalRowIndex}][price]" value="${price}" readonly>
        </td>

        <td>
            <input type="number" class="form-control subtotalInput" value="${price}" readonly>
        </td>

        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
        </td>
    `;

        body.appendChild(tr);

        globalRowIndex++;
        calcAll();
    }

    /* ============================================================
       GLOBAL SEARCH (ATAS TABLE)
    ============================================================*/
    const gSearch = document.getElementById('globalProductSearch');
    const gList = document.getElementById('globalProductList');

    gSearch.addEventListener('input', function() {
        const keyword = this.value.trim().toLowerCase();
        if (!keyword) {
            gList.classList.add('d-none');
            return;
        }

        // show list and filter
        gList.classList.remove('d-none');
        gList.querySelectorAll('.globalProductItem').forEach(item => {
            const name = item.dataset.name.toLowerCase();
            const stock = parseInt(item.dataset.stock);
            item.style.display = (stock > 0 && name.includes(keyword)) ? 'block' : 'none';
        });
    });

    // click global item -> add row
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('globalProductItem')) {
            addNewRowFromSelection(e.target.dataset);
            gSearch.value = '';
            gList.classList.add('d-none');
            return;
        }

        // click outside -> hide lists
        if (!e.target.classList.contains('globalProductItem') && e.target.id !== 'globalProductSearch') {
            document.getElementById('globalProductList').classList.add('d-none');
        }
    });

    /* ============================================================
       HAPUS ROW
    ============================================================*/
    document.addEventListener('click', function(e) {
        if (!e.target.classList.contains('removeRow')) return;

        const tr = e.target.closest('tr');
        tr.remove();
        calcAll();
    });

    /* ============================================================
   QTY VALIDATION
============================================================*/
    document.addEventListener('input', function(e) {
        if (!e.target.classList.contains('qtyInput')) return;

        let raw = e.target.value.replace(/[^0-9]/g, "");
        e.target.value = raw;

        const tr = e.target.closest('tr');
        const select = tr.querySelector('.productSelect');

        if (select && select.value !== "") {
            const stock = parseInt(select.selectedOptions[0].dataset.stock);
            let qty = raw === "" ? 0 : parseInt(raw);

            if (qty > stock) {
                e.target.value = stock;
                document.getElementById("qtyStockMessage").innerText =
                    `Stok produk hanya tersisa ${stock}.`;
                new bootstrap.Modal(document.getElementById("qtyStockModal")).show();
            }
        }

        calcAll();
    });

    /* ============================================================
       HITUNG TOTAL, UPDATE SUBTOTAL, PREVIEW DISKON, QRIS
    ============================================================*/
    function calcAll() {
        let total = 0;

        document.querySelectorAll('#itemsBody tr').forEach((tr) => {
            const select = tr.querySelector('.productSelect');
            let price = 0;

            if (select && select.value !== "") {
                const opt = select.selectedOptions ? select.selectedOptions[0] : null;
                if (opt && opt.dataset && opt.dataset.price) {
                    price = toNumber(opt.dataset.price);
                } else {
                    price = toNumber(tr.querySelector('.priceInput').value);
                }
            } else {
                price = toNumber(tr.querySelector('.priceInput').value);
            }

            let qty = toNumber(tr.querySelector('.qtyInput').value);
            if (qty < 0 || isNaN(qty)) qty = 0;

            tr.querySelector('.priceInput').value = price;
            tr.querySelector('.subtotalInput').value = price * qty;

            total += price * qty;
        });

        document.getElementById('totalText').textContent = formatRupiah(total);
        document.getElementById('modalSubtotal').dataset.value = total;
        document.getElementById('modalSubtotal').innerText = formatRupiah(total);

        recalcDiscountPreview();
        qrisAutoFill();
    }

    /* ============================================================
       MODAL PEMBAYARAN LOGIC (diskon/pay/kembalian/limit)
    ============================================================*/
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

        new bootstrap.Modal(document.getElementById('payModal')).show();
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
        qrisAutoFill();
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

    payInput.addEventListener("input", function() {
        let raw = payInput.value.replace(/[^0-9]/g, "");
        payInput.value = raw;

        let nominal = raw === "" ? 0 : parseInt(raw);

        if (nominal > 1000000000) {
            payInput.value = 1000000000;
            document.getElementById("cashLimitText").classList.remove("d-none");
        } else {
            document.getElementById("cashLimitText").classList.add("d-none");
        }

        updateChangePreview();
    });

    document.getElementById('confirmPayBtn').addEventListener('click', () => {
        const finalTotal = toNumber(finalTotalText.innerText.replace(/\D/g, ''));
        const bayar = toNumber(payInput.value);

        if (bayar < finalTotal) {
            payError.classList.remove('d-none');
            return;
        }

        document.getElementById('cashInput').value = bayar;

        let dNominal = 0;
        const subtotal = toNumber(modalSubtotalEl.dataset.value);

        if (discountPercentRadio.checked) {
            dNominal = Math.floor(subtotal * toNumber(discountPercentInput.value) / 100);
        }

        if (discountNominalRadio.checked) {
            dNominal = toNumber(discountNominalInput.value);
        }

        document.getElementById('trs_discount').value = dNominal;
        document.getElementById('trxForm').submit();
    });

    /* ============================================================
       QRIS AUTO FILL & TOGGLE IMAGE
    ============================================================*/
    function qrisAutoFill() {
        const method = document.querySelector("select[name='payment_method']").value;
        if (method !== "qris") return;

        const finalTotal = toNumber(finalTotalText.innerText.replace(/\D/g, ""));
        payInput.value = finalTotal;
        updateChangePreview();
    }

    document.querySelector("select[name='payment_method']")
        .addEventListener("change", () => {
            qrisAutoFill();
            const qrisBox = document.getElementById("qrisImageBox");
            if (document.querySelector("select[name='payment_method']").value === 'qris') {
                qrisBox.classList.remove('d-none');
            } else {
                qrisBox.classList.add('d-none');
            }
        });

    (function() {
        const qrisBox = document.getElementById("qrisImageBox");
        if (document.querySelector("select[name='payment_method']").value === 'qris') {
            qrisBox.classList.remove('d-none');
        } else {
            qrisBox.classList.add('d-none');
        }
    })();

    // initial calc
    calcAll();
</script>
@endsection