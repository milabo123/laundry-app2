@extends('layouts.app')
@section('title', 'Manajemen Voucher')
@section('page-title', 'Manajemen Voucher')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-ticket-perforated"></i> Daftar Voucher Diskon</div>
        <a href="{{ route('vouchers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Voucher
        </a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode Voucher</th>
                    <th>Diskon</th>
                    <th>Tgl Kadaluarsa</th>
                    <th>Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vouchers as $i => $v)
                    <tr>
                        <td style="color:#64748b;">{{ $vouchers->firstItem() + $i }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:34px;height:34px;border-radius:10px;background:rgba(59,130,246,.15);border:1px solid rgba(59,130,246,.3);display:flex;align-items:center;justify-content:center;font-size:16px;color:var(--info);"><i class="bi bi-ticket"></i></div>
                                <span style="font-weight:700;font-family:monospace;letter-spacing:1px;font-size:15px;color:var(--primary-dark);">{{ $v->code }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-success">{{ $v->discount_percent }}%</span>
                        </td>
                        <td style="color:#64748b;font-size:13px;">
                            {{ $v->expires_at ? $v->expires_at->format('d M Y') : '-' }}
                        </td>
                        <td>
                            @php
                                $isExpired = $v->expires_at && $v->expires_at->isPast();
                            @endphp
                            @if(!$v->is_active)
                                <span class="badge badge-secondary">Non-Aktif</span>
                            @elseif($isExpired)
                                <span class="badge badge-danger">Kadaluarsa</span>
                            @else
                                <span class="badge badge-success">Aktif</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;justify-content:center;">
                                <a href="{{ route('vouchers.edit', $v) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('vouchers.destroy', $v) }}"
                                    onsubmit="return confirm('Hapus voucher {{ $v->code }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:40px;color:#64748b;">
                            <div style="font-size:36px;margin-bottom:10px;"><i class="bi bi-ticket-perforated"></i></div>
                            Belum ada data voucher
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $vouchers->links('pagination::simple-default') }}</div>
</div>
@endsection
