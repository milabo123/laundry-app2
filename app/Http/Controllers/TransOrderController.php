<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\TransOrder;
use App\Models\TransOrderDetail;
use App\Models\TypeOfService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransOrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $orders = TransOrder::with('customer')
            ->when($search, function($q) use ($search) {
                $q->where('order_code', 'like', "%$search%")
                  ->orWhere('customer_name', 'like', "%$search%")
                  ->orWhereHas('customer', fn($q2) => $q2->where('customer_name', 'like', "%$search%"));
            })
            ->when($status !== null && $status !== '', fn($q) => $q->where('order_status', $status))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('orders.index', compact('orders', 'search', 'status'));
    }

    public function create()
    {
        $customers = Customer::withCount('orders')->orderBy('customer_name')->get();
        $services  = TypeOfService::orderBy('service_name')->get();
        return view('orders.create', compact('customers', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_type' => 'required|in:member,customer',
            'id_customer'   => 'required_if:customer_type,member|nullable|exists:customers,id',
            'customer_name' => 'required_if:customer_type,customer|nullable|string|max:50',
            'customer_phone' => 'nullable|string|max:15',
            'customer_address' => 'nullable|string',
            'order_date'    => 'required|date',
            'order_end_date' => 'nullable|date|after_or_equal:order_date',
            'services'      => 'required|array|min:1',
            'services.*.id_service' => 'required|exists:type_of_service,id',
            'services.*.qty'        => 'required|integer|min:1',
            'services.*.notes'      => 'nullable|string',
        ]);

        $orderCode = 'ORD-' . strtoupper(Str::random(8));
        $total = 0;

        $orderData = [
            'order_code'     => $orderCode,
            'order_date'     => $request->order_date,
            'order_end_date' => $request->order_end_date,
            'order_status'   => 0,
            'total'          => 0,
        ];

        if ($request->customer_type === 'member') {
            $orderData['id_customer'] = $request->id_customer;
            $orderData['customer_name'] = null;
            $orderData['customer_phone'] = null;
            $orderData['customer_address'] = null;
        } else {
            $orderData['id_customer'] = null;
            $orderData['customer_name'] = $request->customer_name;
            $orderData['customer_phone'] = $request->customer_phone;
            $orderData['customer_address'] = $request->customer_address;
        }

        $order = TransOrder::create($orderData);

        foreach ($request->services as $svc) {
            $service  = TypeOfService::find($svc['id_service']);
            $subtotal = (int) round($service->price * ($svc['qty'] / 1000));
            $total   += $subtotal;

            TransOrderDetail::create([
                'id_order'   => $order->id,
                'id_service' => $svc['id_service'],
                'qty'        => $svc['qty'],
                'subtotal'   => $subtotal,
                'notes'      => $svc['notes'] ?? null,
            ]);
        }

        // DISABLED: Tax calculation (was 10%)
        // $tax         = (int) round($total * 0.1);
        // $subtotalWithTax = $total + $tax;
        $subtotalWithTax = $total;  // Total without tax

        // DISABLED: NEW MEMBER & VOUCHER LOGIC
        // $discountPercent = 0;
        // $voucherId = null;
        // ... (all discount and voucher logic disabled)
        
        // DISABLED: All discounts are set to 0
        // $discountAmount = (int) round($subtotalWithTax * ($discountPercent / 100));
        // $grandTotal     = $subtotalWithTax - $discountAmount;
        
        $discountAmount = 0;  // No discounts applied
        $voucherId = null;     // No vouchers used
        $grandTotal = $subtotalWithTax;  // Total without any discounts

        $request->validate([
            'order_pay' => 'required|integer|min:' . $grandTotal,
        ]);

        $orderPay    = (int) $request->order_pay;
        $orderChange = max(0, $orderPay - $grandTotal);

        $order->update([
            'total'           => $grandTotal,
            // DISABLED: voucher and discount fields
            // 'id_voucher'      => $voucherId,
            // 'discount_amount' => $discountAmount,
            'order_pay'       => $orderPay > 0 ? $orderPay : null,
            'order_change'    => $orderPay > 0 ? $orderChange : null,
        ]);

        return redirect()->route('orders.index')
            ->with('success', "Order {$orderCode} berhasil dibuat.");
    }

    public function show(TransOrder $order)
    {
        $order->load(['customer', 'details.service']);
        return view('orders.show', compact('order'));
    }

    public function edit(TransOrder $order)
    {
        $customers = Customer::orderBy('customer_name')->get();
        $services  = TypeOfService::orderBy('service_name')->get();
        $order->load('details');
        return view('orders.edit', compact('order', 'customers', 'services'));
    }

    public function update(Request $request, TransOrder $order)
    {
        $request->validate([
            'customer_type' => 'required|in:member,customer',
            'id_customer'   => 'required_if:customer_type,member|nullable|exists:customers,id',
            'customer_name' => 'required_if:customer_type,customer|nullable|string|max:50',
            'customer_phone' => 'nullable|string|max:15',
            'customer_address' => 'nullable|string',
            'order_date'    => 'required|date',
            'order_end_date' => 'nullable|date',
            'order_status'  => 'required|integer|between:0,1',
            'order_pay'     => 'nullable|integer|min:0',
            'services'      => 'required|array|min:1',
            'services.*.id_service' => 'required|exists:type_of_service,id',
            'services.*.qty'        => 'required|integer|min:1',
            'services.*.notes'      => 'nullable|string',
        ]);

        $total = 0;
        $order->details()->delete();

        foreach ($request->services as $svc) {
            $service  = TypeOfService::find($svc['id_service']);
            $subtotal = (int) round($service->price * ($svc['qty'] / 1000));
            $total   += $subtotal;

            TransOrderDetail::create([
                'id_order'   => $order->id,
                'id_service' => $svc['id_service'],
                'qty'        => $svc['qty'],
                'subtotal'   => $subtotal,
                'notes'      => $svc['notes'] ?? null,
            ]);
        }

        // DISABLED: Tax calculation (was 10%)
        // $tax         = (int) round($total * 0.1);
        // $grandTotal  = $total + $tax;
        $grandTotal  = $total;  // Total without tax

        $request->validate([
            'order_pay' => 'required|integer|min:' . $grandTotal,
        ]);

        $orderPay    = (int) $request->order_pay;
        $orderChange = max(0, $orderPay - $grandTotal);

        $orderUpdateData = [
            'order_date'     => $request->order_date,
            'order_end_date' => $request->order_end_date,
            'order_status'   => $request->order_status,
            'order_pay'      => $orderPay > 0 ? $orderPay : null,
            'order_change'   => $orderPay > 0 ? $orderChange : null,
            'total'          => $grandTotal,
        ];

        if ($request->customer_type === 'member') {
            $orderUpdateData['id_customer'] = $request->id_customer;
            $orderUpdateData['customer_name'] = null;
            $orderUpdateData['customer_phone'] = null;
            $orderUpdateData['customer_address'] = null;
        } else {
            $orderUpdateData['id_customer'] = null;
            $orderUpdateData['customer_name'] = $request->customer_name;
            $orderUpdateData['customer_phone'] = $request->customer_phone;
            $orderUpdateData['customer_address'] = $request->customer_address;
        }

        $order->update($orderUpdateData);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order berhasil diperbarui.');
    }

    public function destroy(TransOrder $order)
    {
        $order->delete();
        return redirect()->route('orders.index')
            ->with('success', 'Order berhasil dihapus.');
    }

    public function updateStatus(Request $request, TransOrder $order)
    {
        $request->validate(['order_status' => 'required|integer|between:0,1']);
        $order->update(['order_status' => $request->order_status]);

        return back()->with('success', 'Status order berhasil diperbarui.');
    }
}