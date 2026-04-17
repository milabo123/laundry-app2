<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Voucher;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::orderBy('expires_at')->paginate(10);
        return view('vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('vouchers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:vouchers,code',
            'discount_percent' => 'required|integer|min:1|max:100',
            'expires_at' => 'required|date',
        ]);

        Voucher::create($request->all());

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil ditambahkan.');
    }

    public function edit(Voucher $voucher)
    {
        return view('vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:vouchers,code,' . $voucher->id,
            'discount_percent' => 'required|integer|min:1|max:100',
            'expires_at' => 'required|date',
            'is_active' => 'required|boolean',
        ]);

        $voucher->update($request->all());

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil diperbarui.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil dihapus.');
    }

    public function checkVoucher(Request $request)
    {
        $code = strtoupper($request->code);
        $voucher = Voucher::where('code', $code)->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Voucher tidak ditemukan.']);
        }

        if (!$voucher->is_active || ($voucher->expires_at && $voucher->expires_at->isPast())) {
            return response()->json(['success' => false, 'message' => 'Voucher sudah tidak aktif atau kadaluarsa.']);
        }

        // Check usage history
        $userId = $request->id_customer;
        $phone = $request->customer_phone;

        $query = \App\Models\TransOrder::where('id_voucher', $voucher->id);

        if ($userId) {
            $query->where('id_customer', $userId);
        } elseif ($phone) {
            $query->where('customer_phone', $phone);
        } else {
            // If No ID and No Phone, we can't properly check usage yet, 
            // but usually this won't happen if called from the form correctly.
            return response()->json(['success' => true, 'id' => $voucher->id, 'discount_percent' => $voucher->discount_percent]);
        }

        if ($query->exists()) {
            return response()->json(['success' => false, 'message' => 'Voucher ini sudah pernah digunakan oleh pelanggan ini.']);
        }

        return response()->json([
            'success' => true,
            'id' => $voucher->id,
            'discount_percent' => $voucher->discount_percent,
            'message' => 'Voucher ' . $voucher->discount_percent . '% berhasil diterapkan!'
        ]);
    }
}
