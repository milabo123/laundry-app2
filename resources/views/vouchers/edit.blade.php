@extends('layouts.app')
@section('title', 'Edit Voucher')
@section('page-title', 'Edit Voucher')

@section('content')
<div class="card" style="max-width:600px;">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-ticket-perforated"></i> Edit Voucher</div>
        <a href="{{ route('vouchers.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('vouchers.update', $voucher) }}">
        @csrf @method('PUT')
        <div class="form-group">
            <label class="form-label">Kode Voucher <span style="color:#ef4444;">*</span></label>
            <input type="text" name="code" class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}"
                value="{{ old('code', $voucher->code) }}" placeholder="Contoh: DISKON10" style="text-transform: uppercase;">
            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Persentase Diskon (%) <span style="color:#ef4444;">*</span></label>
            <input type="number" name="discount_percent" class="form-control {{ $errors->has('discount_percent') ? 'is-invalid' : '' }}"
                value="{{ old('discount_percent', $voucher->discount_percent) }}" min="1" max="100">
            @error('discount_percent')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Tanggal Kadaluarsa <span style="color:#ef4444;">*</span></label>
            <input type="date" name="expires_at" class="form-control {{ $errors->has('expires_at') ? 'is-invalid' : '' }}"
                value="{{ old('expires_at', $voucher->expires_at ? $voucher->expires_at->format('Y-m-d') : '') }}">
            @error('expires_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Status Voucher</label>
            <select name="is_active" class="form-control">
                <option value="1" {{ old('is_active', $voucher->is_active) ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ !old('is_active', $voucher->is_active) ? 'selected' : '' }}>Non-Aktif</option>
            </select>
        </div>

        <div style="display:flex;gap:10px;margin-top:24px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Perbarui Voucher
            </button>
            <a href="{{ route('vouchers.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
