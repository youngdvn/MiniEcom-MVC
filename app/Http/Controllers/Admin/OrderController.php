<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->keyword);
        $status = trim((string) $request->status);
        $paymentStatus = trim((string) $request->payment_status);
        $limit = (int) ($request->limit ?? 10);
        if (!in_array($limit, [5, 10, 20, 50], true)) {
            $limit = 10;
        }

        $orders = Order::with('customer')
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where('order_code', 'like', "%{$keyword}%")
                    ->orWhereHas('customer', function ($q) use ($keyword) {
                        $q->where('fullname', 'like', "%{$keyword}%")
                            ->orWhere('phone', 'like', "%{$keyword}%");
                    });
            })
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->when($paymentStatus !== '', fn ($query) => $query->where('payment_status', $paymentStatus))
            ->latest()
            ->paginate($limit)
            ->withQueryString();

        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'revenue_today' => (int) Order::whereDate('created_at', today())
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount'),
        ];

        return view('admin.order.index', compact('orders', 'keyword', 'status', 'paymentStatus', 'limit', 'stats'));
    }

    public function show(int $id)
    {
        $order = Order::with(['customer', 'items.product'])->findOrFail($id);
        return view('admin.order.show', compact('order'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        try {
            DB::transaction(function () use ($id, $request) {
                $order = Order::with('items')->lockForUpdate()->findOrFail($id);
                $oldStatus = $order->status;
                $newStatus = $request->status;

                if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled') {
                    foreach ($order->items as $item) {
                        if ($item->product_variant_id) {
                            ProductVariant::where('id', $item->product_variant_id)->increment('stock_quantity', (int) $item->quantity);
                        } else {
                            Product::where('id', $item->product_id)->increment('stock_quantity', (int) $item->quantity);
                        }
                    }
                }

                if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                    foreach ($order->items as $item) {
                        if ($item->product_variant_id) {
                            $variant = ProductVariant::lockForUpdate()->find($item->product_variant_id);
                            if (!$variant || $variant->stock_quantity < $item->quantity) {
                                throw new \RuntimeException('Không đủ tồn kho phiên bản để khôi phục đơn hàng đã hủy.');
                            }
                        } else {
                            $product = Product::lockForUpdate()->find($item->product_id);
                            if (!$product || $product->stock_quantity < $item->quantity) {
                                throw new \RuntimeException('Không đủ tồn kho để khôi phục đơn hàng đã hủy.');
                            }
                        }
                    }

                    foreach ($order->items as $item) {
                        if ($item->product_variant_id) {
                            ProductVariant::where('id', $item->product_variant_id)->decrement('stock_quantity', (int) $item->quantity);
                        } else {
                            Product::where('id', $item->product_id)->decrement('stock_quantity', (int) $item->quantity);
                        }
                    }
                }

                $order->update(['status' => $newStatus]);
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('admin.order.index')->withErrors(['general' => $e->getMessage()]);
        }

        return redirect()->route('admin.order.index')->with('message', 'Cập nhật trạng thái đơn hàng thành công');
    }

    public function updatePaymentStatus(Request $request, int $id)
    {
        $request->validate([
            'payment_status' => 'required|in:unpaid,paid,refunded',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'payment_status' => $request->payment_status,
        ]);

        return redirect()->route('admin.order.index')->with('message', 'Cập nhật trạng thái thanh toán thành công');
    }
}
