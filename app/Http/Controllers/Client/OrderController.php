<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Support\OrderStatus;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $orders = Order::with('customer')
            ->whereHas('customer', function ($query) use ($user) {
                $query->where('user_id', $user->id);
                if ($user->email) {
                    $query->orWhere('email', $user->email);
                }
            })
            ->latest()
            ->paginate(10);

        return view('client.order.index', compact('orders'));
    }

    public function show(int $id)
    {
        $user = Auth::user();

        $order = Order::with(['customer', 'items.product'])
            ->where('id', $id)
            ->whereHas('customer', function ($query) use ($user) {
                $query->where('user_id', $user->id);
                if ($user->email) {
                    $query->orWhere('email', $user->email);
                }
            })
            ->firstOrFail();

        return view('client.order.show', compact('order'));
    }

    public function payDemo(int $id)
    {
        $user = Auth::user();

        $order = Order::with('customer')
            ->where('id', $id)
            ->whereHas('customer', function ($query) use ($user) {
                $query->where('user_id', $user->id);
                if ($user->email) {
                    $query->orWhere('email', $user->email);
                }
            })
            ->firstOrFail();

        if ($order->payment_status === OrderStatus::PAYMENT_PAID) {
            return redirect()->route('order.show', $order->id)->with('message', 'Đơn hàng đã thanh toán trước đó.');
        }

        $order->update(['payment_status' => OrderStatus::PAYMENT_PAID]);

        return redirect()->route('order.show', $order->id)->with('message', 'Thanh toán thành công (mô phỏng).');
    }
}
