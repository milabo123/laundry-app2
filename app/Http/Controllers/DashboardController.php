<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\TransOrder;
use App\Models\TypeOfService;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers  = Customer::count();
        $totalOrders     = TransOrder::count();
        $totalServices   = TypeOfService::count();
        $pendingOrders   = TransOrder::where('order_status', 0)->count();
        $doneOrders      = TransOrder::where('order_status', 1)->count();

        $recentOrders    = TransOrder::with('customer')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();
            
        $totalRevenue = TransOrder::where('order_status', 1)->sum('total');

        return view('dashboard', compact(
            'totalCustomers', 'totalOrders', 'totalServices', 
            'pendingOrders', 'doneOrders', 'recentOrders', 'totalRevenue'
        ));
    }
}
