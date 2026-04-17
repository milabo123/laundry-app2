@extends('layouts.app')
@section('title', 'Tambah Voucher')
@section('page-title', 'Tambah Voucher Baru')

@section('content')
<div class="card" style="max-width:600px;">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-ticket-plus"></i> Form Tambah Voucher</div>
        <a href="{{ route('vouchers.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('vouchers.store') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Kode Voucher <span style="color:#ef4444;">*</span></label>
            <input type="text" name="code" class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}"
                value="{{ old('code') }}" placeholder="Contoh: DISKON10" autofocus style="text-transform: uppercase;">
            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <small style="color:var(--text-muted);font-size:11px;">Kode akan otomatis dikonversi ke huruf besar.</small>
        </div>

        <div class="form-group">
            <label class="form-label">Persentase Diskon (%) <span style="color:#ef4444;">*</span></label>
            <input type="number" name="discount_percent" class="form-control {{ $errors->has('discount_percent') ? 'is-invalid' : '' }}"
                value="{{ old('discount_percent', 10) }}" min="1" max="100" placeholder="10">
            @error('discount_percent')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Tanggal Kadaluarsa <span style="color:#ef4444;">*</span></label>
            <input type="date" name="expires_at" class="form-control {{ $errors->has('expires_at') ? 'is-invalid' : '' }}"
                value="{{ old('expires_at') }}">
            @error('expires_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div style="display:flex;gap:10px;margin-top:24px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Voucher
            </button>
            <a href="{{ route('vouchers.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
