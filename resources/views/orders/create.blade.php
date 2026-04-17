@extends('layouts.app')
@section('title', 'Buat Order')
@section('page-title', 'Buat Order Laundry')

@push('styles')
<style>
    .detail-line {
        background: #f1f5f9;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 10px;
        position: relative;
    }
    .detail-line .grid { align-items: center; }
    .remove-row {
        background: #fee2e2; color:#dc2626;
        border: 1px solid rgba(239,68,68,.2);
        border-radius: 8px; cursor:pointer;
        width: 34px; height: 34px;
        display: flex; align-items: center; justify-content: center;
        transition: all .2s; font-size: 14px;
    }
    .remove-row:hover { background: rgba(239,68,68,.3); }
    #total-display {
        font-size: 22px; font-weight: 800;
        color: var(--primary); font-family: monospace;
    }
</style>
@endpush

@section('content')
<div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start;">
    <div>
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-clipboard-plus"></i> Form Buat Order</div>
                <div style="display:flex;gap:8px;">
                    <a href="{{ route('customers.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-user-plus"></i> Tambah Member
                    </a>
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
                @csrf

                <div class="grid grid-2">
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Tipe Pelanggan <span style="color:#ef4444;">*</span></label>
                        <div style="display:flex;gap:20px;margin-top:8px;">
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                                <input type="radio" name="customer_type" value="member" {{ old('customer_type', 'member') == 'member' ? 'checked' : '' }} onchange="toggleCustomerFields()"> 
                                <span style="font-weight:600;">Member</span>
                            </label>
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                                <input type="radio" name="customer_type" value="customer" {{ old('customer_type') == 'customer' ? 'checked' : '' }} onchange="toggleCustomerFields()"> 
                                <span style="font-weight:600;">Customer (Non-Member)</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group" id="member-select-wrapper" style="display: {{ old('customer_type', 'member') == 'member' ? 'block' : 'none' }};">
                        <label class="form-label">Pilih Member <span style="color:#ef4444;">*</span></label>
                        <select name="id_customer" class="form-control {{ $errors->has('id_customer') ? 'is-invalid' : '' }}" onchange="calcTotal()">
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ old('id_customer') == $c->id ? 'selected' : '' }} data-orders="{{ $c->orders_count }}">
                                    {{ $c->customer_name }} ({{ $c->phone }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_customer')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div id="customer-inputs-wrapper" style="display: {{ old('customer_type') == 'customer' ? 'contents' : 'none' }};">
                        <div class="form-group">
                            <label class="form-label">Nama Customer <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="customer_name" class="form-control {{ $errors->has('customer_name') ? 'is-invalid' : '' }}" 
                                value="{{ old('customer_name') }}" placeholder="Nama Lengkap">
                            @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="customer_phone" class="form-control {{ $errors->has('customer_phone') ? 'is-invalid' : '' }}" 
                                value="{{ old('customer_phone') }}" placeholder="08xxxxxxxx">
                            @error('customer_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group" style="grid-column: span 2;">
                            <label class="form-label">Alamat</label>
                            <textarea name="customer_address" class="form-control" rows="2" placeholder="Alamat Lengkap (Opsional)">{{ old('customer_address') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Order <span style="color:#ef4444;">*</span></label>
                        <input type="date" name="order_date" class="form-control {{ $errors->has('order_date') ? 'is-invalid' : '' }}"
                            value="{{ old('order_date', date('Y-m-d')) }}">
                        @error('order_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Estimasi Selesai</label>
                        <input type="date" name="order_end_date" class="form-control"
                            value="{{ old('order_end_date') }}">
                    </div>
                </div>

                <hr class="divider">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                    <div style="font-weight:700;font-size:15px;"><i class="bi bi-droplet"></i> Detail Layanan</div>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="addRow()">
                        <i class="fas fa-plus"></i> Tambah Layanan
                    </button>
                </div>

                <div id="detail-rows"></div>

                @error('services')<div class="alert alert-danger">{{ $message }}</div>@enderror

                <div style="margin-top:20px;">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> Simpan Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary panel --}}
    <div style="position:sticky;top:80px;">
        <div class="card">
            <div class="card-title" style="margin-bottom:16px;"><i class="bi bi-cash-stack"></i> Ringkasan Order</div>
            <div id="summary-list" style="display:grid;gap:8px;margin-bottom:16px;"></div>
            
            <div style="background:rgba(22,163,74,.05);border-radius:12px;padding:16px;margin-bottom:16px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:13px;">
                    <span style="color:#94a3b8;">Subtotal Item</span>
                    <span id="subtotal-display" style="font-weight:600;">Rp 0</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:13px;">
                    <span style="color:#94a3b8;">Pajak (10%)</span>
                    <span id="tax-display" style="font-weight:600;color:#f59e0b;">Rp 0</span>
                </div>
                <div id="discount-row" style="display:none;justify-content:space-between;margin-bottom:8px;font-size:13px;color:var(--danger);font-weight:600;">
                    <span>Diskon (<span id="discount-percent-display">0</span>%)</span>
                    <span id="discount-amount-display">-Rp 0</span>
                </div>
                <hr style="border:none;border-top:1px dashed #e2e8f0;margin:8px 0;">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-weight:700;font-size:14px;">Total Tagihan</span>
                    <div id="grand-total-display" style="font-size:20px;font-weight:800;color:var(--primary);">Rp 0</div>
                </div>
            </div>

            <div id="member-discount-info" style="display:none;background:rgba(59,130,246,.1);border:1px solid rgba(59,130,246,.2);padding:10px;border-radius:10px;margin-bottom:16px;font-size:11px;color:var(--info);">
                <i class="fas fa-gift"></i> <strong>Member Baru!</strong> Diskon 5% otomatis untuk transaksi pertama.
            </div>

            <div class="form-group" style="margin-bottom:16px;">
                <label style="font-size:12px;color:var(--text-muted);font-weight:600;margin-bottom:6px;display:block;">KODE VOUCHER</label>
                <div style="display:flex;gap:8px;">
                    <input type="text" name="voucher_code" id="voucher_code" class="form-control" placeholder="DISKON10" style="text-transform:uppercase;font-size:13px;">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="applyVoucher()">Cek</button>
                    <button type="button" id="remove-voucher-btn" class="btn btn-danger btn-sm" style="display:none;" onclick="removeVoucher()"><i class="fas fa-times"></i></button>
                </div>
                <div id="voucher-message" style="font-size:10px;margin-top:4px;font-weight:600;display:none;"></div>
            </div>

            <div class="form-group" style="margin-bottom:12px;">
                <label style="font-size:13px;color:var(--text-muted);">Uang Bayar (Rp)</label>
                <input type="number" name="order_pay" id="order_pay" class="form-control" form="orderForm"
                    placeholder="0" min="0" oninput="calcTotal()" style="text-align:right;">
                <div id="pay-error" style="display:none;margin-top:6px;font-size:12px;color:#dc2626;font-weight:600;">
                    <i class="fas fa-exclamation-circle"></i> Uang bayar kurang dari total tagihan.
                </div>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <span style="color:#94a3b8;font-size:14px;">Kembalian</span>
                <div id="change-display" style="font-size:18px;font-weight:700;color:var(--secondary);">Rp 0</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const services = @json($services);
let rowIndex = 0;
let currentGrandTotal = 0;

function toggleCustomerFields() {
    const type = document.querySelector('input[name="customer_type"]:checked').value;
    const memberWrapper = document.getElementById('member-select-wrapper');
    const customerWrapper = document.getElementById('customer-inputs-wrapper');
    
    if (type === 'member') {
        memberWrapper.style.display = 'block';
        customerWrapper.style.display = 'none';
    } else {
        memberWrapper.style.display = 'none';
        customerWrapper.style.display = 'contents';
    }
    calcTotal();
}

function addRow(svcId = '', qty = 1, notes = '') {
    const container = document.getElementById('detail-rows');
    const div = document.createElement('div');
    div.className = 'detail-line';
    div.dataset.index = rowIndex;

    let options = '<option value="">-- Pilih Layanan --</option>';
    services.forEach(s => {
        options += `<option value="${s.id}" data-price="${s.price}" ${s.id == svcId ? 'selected' : ''}>
            ${s.service_name} – Rp ${Number(s.price).toLocaleString('id-ID')}
        </option>`;
    });

    div.innerHTML = `
        <div style="display:grid;grid-template-columns:2fr 100px auto 34px;gap:12px;align-items:end;">
            <div>
                <label class="form-label" style="font-size:12px;">Layanan</label>
                <select name="services[${rowIndex}][id_service]" class="form-control svc-select" onchange="calcTotal()">
                    ${options}
                </select>
            </div>
            <div>
                <label class="form-label" style="font-size:12px;">Qty (Gram)</label>
                <input type="number" name="services[${rowIndex}][qty]" class="form-control svc-qty"
                    value="${qty}" min="1" oninput="calcTotal()">
            </div>
            <div>
                <label class="form-label" style="font-size:12px;">Catatan</label>
                <input type="text" name="services[${rowIndex}][notes]" class="form-control"
                    value="${notes}" placeholder="Opsional">
            </div>
            <div style="margin-top:22px;">
                <button type="button" class="remove-row" onclick="removeRow(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div style="margin-top:8px;font-size:12px;color:#94a3b8;" class="svc-subtotal"></div>
    `;

    container.appendChild(div);
    rowIndex++;
    calcTotal();
}

function removeRow(btn) {
    btn.closest('.detail-line').remove();
    calcTotal();
}

function calcTotal() {
    const rows = document.querySelectorAll('.detail-line');
    let itemsSubtotal = 0;
    let summaryHtml = '';

    rows.forEach(row => {
        const sel = row.querySelector('.svc-select');
        const qty = parseInt(row.querySelector('.svc-qty').value) || 0;
        const opt = sel.options[sel.selectedIndex];
        const price = parseInt(opt?.dataset?.price || 0);
        const subtotal = Math.round(price * (qty / 1000));
        itemsSubtotal += subtotal;

        if (sel.value && qty > 0) {
            row.querySelector('.svc-subtotal').textContent = `Subtotal: Rp ${subtotal.toLocaleString('id-ID')}`;
            summaryHtml += `<div style="display:flex;justify-content:space-between;font-size:13px;">
                <span style="color:var(--text-muted);">${opt.text.split('–')[0].trim()} x ${qty}g</span>
                <span style="color:var(--text);">Rp ${subtotal.toLocaleString('id-ID')}</span>
            </div>`;
        } else {
            row.querySelector('.svc-subtotal').textContent = '';
        }
    });

    document.getElementById('subtotal-display').textContent = 'Rp ' + itemsSubtotal.toLocaleString('id-ID');

    const tax = Math.round(itemsSubtotal * 0.1);
    const subtotalWithTax = itemsSubtotal + tax;
    document.getElementById('tax-display').textContent = 'Rp ' + tax.toLocaleString('id-ID');

    // --- DISCOUNT CALCULATION ---
    let totalDiscountPercent = 0;
    
    // 1. New Member Discount (5% automatically)
    const type = document.querySelector('input[name="customer_type"]:checked').value;
    const memberDiscountInfo = document.getElementById('member-discount-info');
    if (type === 'member') {
        const select = document.querySelector('select[name="id_customer"]');
        const opt = select.options[select.selectedIndex];
        // If it's a first time member (data-orders == 0)
        if (opt && opt.value && opt.dataset.orders == 0) {
            totalDiscountPercent += 5;
            memberDiscountInfo.style.display = 'block';
        } else {
            memberDiscountInfo.style.display = 'none';
        }
    } else {
        memberDiscountInfo.style.display = 'none';
    }

    // 2. Voucher Discount
    if (appliedVoucher) {
        totalDiscountPercent += appliedVoucher.discount_percent;
    }

    const discountAmount = Math.round(subtotalWithTax * (totalDiscountPercent / 100));
    currentGrandTotal = subtotalWithTax - discountAmount;

    // --- VIEW UPDATES ---
    const discountRow = document.getElementById('discount-row');
    if (totalDiscountPercent > 0) {
        discountRow.style.display = 'flex';
        document.getElementById('discount-percent-display').textContent = totalDiscountPercent;
        document.getElementById('discount-amount-display').textContent = '-Rp ' + discountAmount.toLocaleString('id-ID');
    } else {
        discountRow.style.display = 'none';
    }

    document.getElementById('grand-total-display').textContent = 'Rp ' + currentGrandTotal.toLocaleString('id-ID');

    const payInput = document.getElementById('order_pay');
    const pay = parseInt(payInput.value) || 0;
    const change = Math.max(0, pay - currentGrandTotal);
    document.getElementById('change-display').textContent = 'Rp ' + change.toLocaleString('id-ID');

    const payError = document.getElementById('pay-error');
    const submitBtn = document.getElementById('submitBtn');
    const hasPay = payInput.value !== '';
    const isInsufficient = hasPay && pay < currentGrandTotal;

    payInput.style.borderColor = isInsufficient ? '#dc2626' : '';
    payError.style.display = isInsufficient ? 'block' : 'none';
    submitBtn.disabled = isInsufficient;
    submitBtn.style.opacity = isInsufficient ? '0.5' : '';
    submitBtn.style.cursor = isInsufficient ? 'not-allowed' : '';

    document.getElementById('summary-list').innerHTML = summaryHtml ||
        '<div style="color:#64748b;font-size:13px;text-align:center;">Belum ada layanan dipilih</div>';
}

let appliedVoucher = null;

function applyVoucher() {
    const code = document.getElementById('voucher_code').value;
    const type = document.querySelector('input[name="customer_type"]:checked').value;
    const idCust = document.querySelector('select[name="id_customer"]').value;
    const phone = document.getElementsByName('customer_phone')[0].value;
    const msg = document.getElementById('voucher-message');
    
    if (!code) {
        msg.textContent = 'Masukkan kode voucher dahulu.';
        msg.style.color = 'var(--danger)';
        msg.style.display = 'block';
        return;
    }

    if (type === 'member' && !idCust) {
        msg.textContent = 'Pilih member dahulu.';
        msg.style.color = 'var(--danger)';
        msg.style.display = 'block';
        return;
    }

    fetch('{{ route("vouchers.check") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            code: code,
            id_customer: type === 'member' ? idCust : null,
            customer_phone: type === 'customer' ? phone : null
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            appliedVoucher = {
                id: data.id,
                discount_percent: data.discount_percent
            };
            msg.textContent = data.message;
            msg.style.color = 'var(--primary)';
            msg.style.display = 'block';
            document.getElementById('remove-voucher-btn').style.display = 'block';
            document.getElementById('voucher_code').readOnly = true;
            calcTotal();
        } else {
            msg.textContent = data.message;
            msg.style.color = 'var(--danger)';
            msg.style.display = 'block';
        }
    })
    .catch(err => {
        msg.textContent = 'Terjadi kesalahan sistem.';
        msg.style.color = 'var(--danger)';
        msg.style.display = 'block';
    });
}

function removeVoucher() {
    appliedVoucher = null;
    document.getElementById('voucher_code').value = '';
    document.getElementById('voucher_code').readOnly = false;
    document.getElementById('remove-voucher-btn').style.display = 'none';
    document.getElementById('voucher-message').style.display = 'none';
    calcTotal();
}

// Block submission if pay is empty or insufficient
document.getElementById('orderForm').addEventListener('submit', function (e) {
    const pay = parseInt(document.getElementById('order_pay').value) || 0;
    const payInput = document.getElementById('order_pay');
    const payError = document.getElementById('pay-error');

    if (pay < currentGrandTotal) {
        e.preventDefault();
        payInput.style.borderColor = '#dc2626';
        payError.style.display = 'block';
        payInput.focus();
        // Scroll to summary panel so user sees the error
        payInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});

// Add first row on load
addRow();
</script>
@endpush