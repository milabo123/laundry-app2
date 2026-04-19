# Disabled Features - Reversion Guide

**Date:** April 19, 2026

This document outlines all the changes made to disable the following features:
- 10% Tax calculation
- 5% New Member discount on first transaction
- Voucher/discount code functionality

---

## Summary of Changes

Three features have been disabled in the application:

1. **Tax (10%)** - Previously added 10% tax to all orders
2. **New Member Discount (5%)** - Automatically gave 5% discount to members on their first order
3. **Voucher System** - Allowed customers to apply discount vouchers/codes

---

## Files Modified

### 1. `app/Http/Controllers/TransOrderController.php`

#### Change 1.1: Store Method - Disable Tax & Discount Logic (Lines ~95-129)

**What was changed:**
- Removed automatic 10% tax calculation
- Disabled new member discount logic (5% for first transaction)
- Disabled voucher discount logic
- Set all discount amounts to 0

**Original code:**
```php
$tax         = (int) round($total * 0.1);
$subtotalWithTax = $total + $tax;

// --- NEW MEMBER & VOUCHER LOGIC ---
$discountPercent = 0;
$voucherId = null;

// 1. New Member Discount (5% on first transaction)
if ($request->customer_type === 'member' && $request->id_customer) {
    $isFirstOrder = !TransOrder::where('id_customer', $request->id_customer)->exists();
    if ($isFirstOrder) {
        $discountPercent += 5;
    }
}

// 2. Voucher Discount
if ($request->voucher_code) {
    $voucher = \App\Models\Voucher::where('code', strtoupper($request->voucher_code))->first();
    if ($voucher && $voucher->is_active && (!$voucher->expires_at || !$voucher->expires_at->isPast())) {
        $canUse = true;
        if ($request->customer_type === 'member') {
            $canUse = !TransOrder::where('id_customer', $request->id_customer)->where('id_voucher', $voucher->id)->exists();
        } elseif ($request->customer_phone) {
            $canUse = !TransOrder::where('customer_phone', $request->customer_phone)->where('id_voucher', $voucher->id)->exists();
        }

        if ($canUse) {
            $discountPercent += $voucher->discount_percent;
            $voucherId = $voucher->id;
        }
    }
}

$discountAmount = (int) round($subtotalWithTax * ($discountPercent / 100));
$grandTotal     = $subtotalWithTax - $discountAmount;
```

**New code:**
```php
// DISABLED: Tax calculation (was 10%)
// $tax         = (int) round($total * 0.1);
// $subtotalWithTax = $total + $tax;
$subtotalWithTax = $total;  // Total without tax

// DISABLED: NEW MEMBER & VOUCHER LOGIC
// ... all discount and voucher logic disabled

// DISABLED: All discounts are set to 0
$discountAmount = 0;  // No discounts applied
$voucherId = null;     // No vouchers used
$grandTotal = $subtotalWithTax;  // Total without any discounts
```

---

#### Change 1.2: Store Method - Remove Voucher & Discount from Order Update (Lines ~140-142)

**What was changed:**
- Removed `id_voucher` field from order update
- Removed `discount_amount` field from order update

**Original code:**
```php
$order->update([
    'total'           => $grandTotal,
    'id_voucher'      => $voucherId,
    'discount_amount' => $discountAmount,
    'order_pay'       => $orderPay > 0 ? $orderPay : null,
    'order_change'    => $orderPay > 0 ? $orderChange : null,
]);
```

**New code:**
```php
$order->update([
    'total'           => $grandTotal,
    // DISABLED: voucher and discount fields
    // 'id_voucher'      => $voucherId,
    // 'discount_amount' => $discountAmount,
    'order_pay'       => $orderPay > 0 ? $orderPay : null,
    'order_change'    => $orderPay > 0 ? $orderChange : null,
]);
```

---

#### Change 1.3: Update Method - Disable Tax Calculation (Lines ~200-201)

**What was changed:**
- Removed automatic 10% tax calculation from order updates
- Grand total = subtotal without tax

**Original code:**
```php
$tax         = (int) round($total * 0.1);
$grandTotal  = $total + $tax;
```

**New code:**
```php
// DISABLED: Tax calculation (was 10%)
// $tax         = (int) round($total * 0.1);
// $grandTotal  = $total + $tax;
$grandTotal  = $total;  // Total without tax
```

---

### 2. `resources/views/orders/create.blade.php`

#### Change 2.1: Hide Tax Display (Lines ~135-139)

**What was changed:**
- Comment out the tax display row in the order summary panel

**Original code:**
```html
<div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:13px;">
    <span style="color:#94a3b8;">Pajak (10%)</span>
    <span id="tax-display" style="font-weight:600;color:#f59e0b;">Rp 0</span>
</div>
```

**New code:**
```html
<!-- DISABLED: Tax display (10% tax feature disabled) -->
<!-- <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:13px;">
    <span style="color:#94a3b8;">Pajak (10%)</span>
    <span id="tax-display" style="font-weight:600;color:#f59e0b;">Rp 0</span>
</div> -->
```

---

#### Change 2.2: Hide Discount Display (Lines ~137-141)

**What was changed:**
- Comment out the discount row in the order summary panel

**Original code:**
```html
<div id="discount-row" style="display:none;justify-content:space-between;margin-bottom:8px;font-size:13px;color:var(--danger);font-weight:600;">
    <span>Diskon (<span id="discount-percent-display">0</span>%)</span>
    <span id="discount-amount-display">-Rp 0</span>
</div>
```

**New code:**
```html
<!-- DISABLED: Discount display (voucher and member discounts disabled) -->
<!-- <div id="discount-row" style="display:none;justify-content:space-between;margin-bottom:8px;font-size:13px;color:var(--danger);font-weight:600;">
    <span>Diskon (<span id="discount-percent-display">0</span>%)</span>
    <span id="discount-amount-display">-Rp 0</span>
</div> -->
```

---

#### Change 2.3: Hide Member Discount Info (Lines ~145-147)

**What was changed:**
- Comment out the member discount notification message

**Original code:**
```html
<div id="member-discount-info" style="display:none;background:rgba(59,130,246,.1);border:1px solid rgba(59,130,246,.2);padding:10px;border-radius:10px;margin-bottom:16px;font-size:11px;color:var(--info);">
    <i class="fas fa-gift"></i> <strong>Member Baru!</strong> Diskon 5% otomatis untuk transaksi pertama.
</div>
```

**New code:**
```html
<!-- DISABLED: New member discount info (5% discount for new members disabled) -->
<!-- <div id="member-discount-info" style="display:none;background:rgba(59,130,246,.1);border:1px solid rgba(59,130,246,.2);padding:10px;border-radius:10px;margin-bottom:16px;font-size:11px;color:var(--info);">
    <i class="fas fa-gift"></i> <strong>Member Baru!</strong> Diskon 5% otomatis untuk transaksi pertama.
</div> -->
```

---

#### Change 2.4: Hide Voucher Input Form (Lines ~149-159)

**What was changed:**
- Comment out the entire voucher input form and buttons

**Original code:**
```html
<div class="form-group" style="margin-bottom:16px;">
    <label style="font-size:12px;color:var(--text-muted);font-weight:600;margin-bottom:6px;display:block;">KODE VOUCHER</label>
    <div style="display:flex;gap:8px;">
        <input type="text" name="voucher_code" id="voucher_code" class="form-control" placeholder="DISKON10" style="text-transform:uppercase;font-size:13px;">
        <button type="button" class="btn btn-secondary btn-sm" onclick="applyVoucher()">Cek</button>
        <button type="button" id="remove-voucher-btn" class="btn btn-danger btn-sm" style="display:none;" onclick="removeVoucher()"><i class="fas fa-times"></i></button>
    </div>
    <div id="voucher-message" style="font-size:10px;margin-top:4px;font-weight:600;display:none;"></div>
</div>
```

**New code:**
```html
<!-- DISABLED: Voucher input form (all voucher features disabled) -->
<!-- <div class="form-group" style="margin-bottom:16px;">
    <label style="font-size:12px;color:var(--text-muted);font-weight:600;margin-bottom:6px;display:block;">KODE VOUCHER</label>
    <div style="display:flex;gap:8px;">
        <input type="text" name="voucher_code" id="voucher_code" class="form-control" placeholder="DISKON10" style="text-transform:uppercase;font-size:13px;">
        <button type="button" class="btn btn-secondary btn-sm" onclick="applyVoucher()">Cek</button>
        <button type="button" id="remove-voucher-btn" class="btn btn-danger btn-sm" style="display:none;" onclick="removeVoucher()"><i class="fas fa-times"></i></button>
    </div>
    <div id="voucher-message" style="font-size:10px;margin-top:4px;font-weight:600;display:none;"></div>
</div> -->
```

---

#### Change 2.5: Update JavaScript calcTotal() Function (Lines ~282-324)

**What was changed:**
- Disabled tax calculation in JavaScript
- Disabled new member discount logic
- Disabled voucher discount logic
- Disabled discount row display updates

**Original code (excerpt):**
```javascript
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
```

**New code (excerpt):**
```javascript
// DISABLED: Tax calculation
// const tax = Math.round(itemsSubtotal * 0.1);
// const subtotalWithTax = itemsSubtotal + tax;
// document.getElementById('tax-display').textContent = 'Rp ' + tax.toLocaleString('id-ID');
const tax = 0;  // No tax
const subtotalWithTax = itemsSubtotal;  // Total without tax

// DISABLED: Discount calculations (member discount and voucher)
// let totalDiscountPercent = 0;
// // ... all discount logic disabled
let totalDiscountPercent = 0;  // No discounts applied
const discountAmount = 0;  // No discounts
currentGrandTotal = subtotalWithTax;  // Total without any discounts

// DISABLED: View updates for discounts
// const discountRow = document.getElementById('discount-row');
// ... discount display logic disabled
```

---

#### Change 2.6: Disable Voucher Functions (Lines ~326-392)

**What was changed:**
- Comment out the entire `applyVoucher()` function
- Comment out the entire `removeVoucher()` function

**Original code:**
```javascript
let appliedVoucher = null;

function applyVoucher() {
    const code = document.getElementById('voucher_code').value;
    // ... entire function logic
}

function removeVoucher() {
    appliedVoucher = null;
    // ... entire function logic
}
```

**New code:**
```javascript
// DISABLED: Voucher functionality
let appliedVoucher = null;

/* DISABLED: Voucher checking and application
function applyVoucher() {
    // ... entire function commented out
}

function removeVoucher() {
    // ... entire function commented out
}
*/
```

---

## How to Revert Changes

If you want to restore the original features, follow these steps:

### For TransOrderController.php:

1. **Restore Store Method Tax & Discount (Lines ~95-129):**
   - Uncomment all the tax and discount calculation code
   - Remove the lines that set `discountAmount = 0`, `voucherId = null`, and `grandTotal = $subtotalWithTax`

2. **Restore Order Update (Lines ~140-142):**
   - Uncomment `'id_voucher' => $voucherId,`
   - Uncomment `'discount_amount' => $discountAmount,`

3. **Restore Update Method Tax (Lines ~200-201):**
   - Uncomment the tax calculation lines
   - Change `$grandTotal = $total;` back to include tax

### For create.blade.php:

1. **Restore Tax Display (Lines ~135-139):**
   - Uncomment the tax display div

2. **Restore Discount Display (Lines ~137-141):**
   - Uncomment the discount row div

3. **Restore Member Discount Info (Lines ~145-147):**
   - Uncomment the member discount info div

4. **Restore Voucher Form (Lines ~149-159):**
   - Uncomment the entire voucher input form-group div

5. **Restore calcTotal() Function (Lines ~282-324):**
   - Uncomment all tax and discount calculation logic

6. **Restore Voucher Functions (Lines ~326-392):**
   - Uncomment the `applyVoucher()` and `removeVoucher()` functions

---

## Testing

After making these changes, test the following:

- ✅ Creating a new order as a **non-member** should show correct total without tax/discounts
- ✅ Creating a new order as a **new member** should NOT show 5% discount
- ✅ Voucher input field should NOT be visible
- ✅ Tax row should NOT be visible in the order summary
- ✅ Discount row should NOT be visible in the order summary
- ✅ Order totals should equal: Subtotal (Service Price × Quantity / 1000)

---

## Notes

- The database structure remains unchanged (columns for `id_voucher` and `discount_amount` still exist in the `trans_orders` table)
- Voucher management in the admin panel is still functional but won't be used in orders
- Future orders will have `NULL` values for `id_voucher` and `discount_amount` fields
- All existing orders with discounts remain unchanged in the database

---

**Last Updated:** April 19, 2026
