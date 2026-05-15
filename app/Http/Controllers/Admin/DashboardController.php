<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $todayRevenue = (int) Order::query()
            ->whereDate('created_at', today())
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $monthRevenue = (int) Order::query()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $orderCount = (int) Order::count();
        $paidCount = (int) Order::where('payment_status', 'paid')->count();
        $unpaidCount = (int) Order::where('payment_status', 'unpaid')->count();
        $refundedCount = (int) Order::where('payment_status', 'refunded')->count();

        $paidRate = $orderCount > 0 ? round(($paidCount / $orderCount) * 100, 1) : 0;
        $unpaidRate = $orderCount > 0 ? round(($unpaidCount / $orderCount) * 100, 1) : 0;
        $refundedRate = $orderCount > 0 ? round(($refundedCount / $orderCount) * 100, 1) : 0;

        $topProducts = OrderItem::query()
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->select(
                'order_items.product_id',
                'products.proname',
                DB::raw('SUM(order_items.quantity) as sold_qty'),
                DB::raw('SUM(order_items.quantity * order_items.price) as revenue')
            )
            ->groupBy('order_items.product_id', 'products.proname')
            ->orderByDesc('sold_qty')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'productCount' => Product::count(),
            'orderCount' => $orderCount,
            'contactCount' => ContactMessage::count(),
            'userCount' => User::count(),
            'todayRevenue' => $todayRevenue,
            'monthRevenue' => $monthRevenue,
            'paidCount' => $paidCount,
            'unpaidCount' => $unpaidCount,
            'refundedCount' => $refundedCount,
            'paidRate' => $paidRate,
            'unpaidRate' => $unpaidRate,
            'refundedRate' => $refundedRate,
            'topProducts' => $topProducts,
        ]);
    }
}

