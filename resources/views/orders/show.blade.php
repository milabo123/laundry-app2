@extends('layouts.app')
@section('title', 'Detail Order')
@section('page-title', 'Detail Order')

@section('content')
<div style="display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start;">
    <div>
        {{-- Order Info --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-clipboard-data"></i> Informasi Order</div>
                <div style="display:flex;gap:8px;">
                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Kode Order</div>
                <div class="detail-value" style="font-weight:700;font-family:monospace;color:var(--primary-dark);">{{ $order->order_code }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Pelanggan</div>
                <div class="detail-value" style="font-weight:600;">
                    @if($order->id_customer)
                        {{ $order->customer->customer_name }}
                        <span class="badge badge-success" style="font-size:10px;margin-left:8px;">Member</span>
                    @else
                        {{ $order->customer_name }}
                        <span class="badge badge-secondary" style="font-size:10px;margin-left:8px;">Non-Member</span>
                    @endif
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">No Telepon</div>
                <div class="detail-value">
                    {{ $order->id_customer ? ($order->customer->phone ?? '-') : ($order->customer_phone ?? '-') }}
                </div>
            </div>
            @if(!$order->id_customer && $order->customer_address)
            <div class="detail-row">
                <div class="detail-label">Alamat</div>
                <div class="detail-value">{{ $order->customer_address }}</div>
            </div>
            @elseif($order->id_customer && $order->customer->address)
            <div class="detail-row">
                <div class="detail-label">Alamat</div>
                <div class="detail-value">{{ $order->customer->address }}</div>
            </div>
            @endif
            <div class="detail-row">
                <div class="detail-label">Tanggal Order</div>
                <div class="detail-value">{{ $order->order_date->format('d M Y') }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Estimasi Selesai</div>
                <div class="detail-value">{{ $order->order_end_date ? $order->order_end_date->format('d M Y') : '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    <span class="badge badge-{{ $order->status_color }}">{{ $order->status_label }}</span>
                </div>
            </div>

            {{-- Update Status --}}
            <hr class="divider">
            <div style="font-weight:600;font-size:14px;margin-bottom:12px;"><i class="bi bi-lightning-charge"></i> Ubah Status Order</div>
            <form method="POST" action="{{ route('orders.updateStatus', $order) }}" style="display:flex;gap:8px;flex-wrap:wrap;">
                @csrf @method('PATCH')
                @foreach([0 => ['Baru','warning'], 1 => ['Sudah Diambil','success']] as $val => $info)
                    <button type="submit" name="order_status" value="{{ $val }}"
                        class="btn btn-{{ $info[1] }} btn-sm {{ $order->order_status == $val ? '' : '' }}"
                        style="{{ $order->order_status == $val ? 'opacity:.5;pointer-events:none;' : '' }}">
                        {{ $info[0] }}
                    </button>
                @endforeach
            </form>
        </div>

        {{-- Detail Items --}}
        <div class="card">
            <div class="card-title" style="margin-bottom:16px;"><i class="bi bi-droplet"></i> Detail Layanan</div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Layanan</th>
                            <th>Harga Satuan</th>
                            <th>Qty (Gram)</th>
                            <th>Subtotal</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->details as $i => $d)
                        <tr>
                            <td style="color:#64748b;">{{ $i + 1 }}</td>
                            <td style="font-weight:600;">{{ $d->service->service_name ?? '-' }}</td>
                            <td style="color:#94a3b8;">Rp {{ number_format($d->service->price ?? 0, 0, ',', '.') }}/kg</td>
                            <td>{{ $d->qty }} g</td>
                            <td style="font-weight:700;color:var(--primary);">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                            <td style="color:#94a3b8;font-size:13px;">{{ $d->notes ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Payment Summary --}}
    <div style="position:sticky;top:80px;">
        <div class="card" style="background:#f8fafc; border-color:var(--border);">
            <div class="card-title" style="margin-bottom:20px;"><i class="bi bi-cash-stack"></i> Ringkasan Pembayaran</div>

            <div style="background:rgba(22,163,74,.03);border-radius:12px;padding:14px;margin-bottom:16px;">
                @php
                    $itemSubtotal = $order->details->sum('subtotal');
                    $tax = (int)round($itemSubtotal * 0.1);
                    $subtotalWithTax = $itemSubtotal + $tax;
                @endphp
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:8px;">
                    <span style="color:#94a3b8;">Subtotal Item</span>
                    <span style="font-weight:500;">Rp {{ number_format($itemSubtotal, 0, ',', '.') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:8px;">
                    <span style="color:#94a3b8;">Pajak (10%)</span>
                    <span style="font-weight:500;">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:8px;color:var(--danger);font-weight:600;">
                    <span>
                        Potongan Diskon
                        @if($order->id_voucher)
                            <br><small style="font-weight: normal; color: #94a3b8;">(Voucher: {{ $order->voucher->code }})</small>
                        @endif
                    </span>
                    <span>-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                <hr style="border:none;border-top:1px dashed #e2e8f0;margin:10px 0;">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-weight:700;font-size:14px;">Total Akhir</span>
                    <span style="font-size:22px;font-weight:800;color:var(--primary);font-family:monospace;">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            @if($order->order_pay)
            <div style="display:flex;justify-content:space-between;font-size:14px;margin-bottom:6px;">
                <span style="color:#94a3b8;">Bayar</span>
                <span style="color:var(--primary-dark);">Rp {{ number_format($order->order_pay, 0, ',', '.') }}</span>
            </div>
            @endif

            @if($order->order_change)
            <div style="display:flex;justify-content:space-between;font-size:14px;">
                <span style="color:#94a3b8;">Kembalian</span>
                <span style="color:var(--secondary);">Rp {{ number_format($order->order_change, 0, ',', '.') }}</span>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
