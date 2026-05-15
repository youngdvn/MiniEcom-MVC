<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    private function generateOrderCode(): string
    {
        for ($i = 0; $i < 50; $i++) {
            $code = 'ORD-' . str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            if (!Order::where('order_code', $code)->exists()) {
                return $code;
            }
        }

        throw new \RuntimeException('Không thể tạo mã đơn hàng. Vui lòng thử lại.');
    }

    private function getAvailableCoupons(int $subtotal)
    {
        return Coupon::query()
            ->where('status', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')->orWhereColumn('used_count', '<', 'usage_limit');
            })
            ->where('min_order', '<=', $subtotal)
            ->orderByDesc('value')
            ->get();
    }

    private function findValidCouponByCode(string $code, int $subtotal): ?Coupon
    {
        $coupon = Coupon::where('code', strtoupper($code))
            ->where('status', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->first();

        if (!$coupon) {
            return null;
        }
        if (!is_null($coupon->usage_limit) && $coupon->used_count >= $coupon->usage_limit) {
            return null;
        }
        if ($subtotal < (int) $coupon->min_order) {
            return null;
        }

        return $coupon;
    }

    private function mapCoupon(Coupon $coupon): array
    {
        return [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => (int) $coupon->value,
            'max_discount' => $coupon->max_discount ? (int) $coupon->max_discount : null,
            'min_order' => (int) $coupon->min_order,
        ];
    }

    private function getSessionCoupon(array $cart): ?array
    {
        $sessionCoupon = Session::get('coupon');
        if (empty($sessionCoupon['code'])) {
            return null;
        }

        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $coupon = $this->findValidCouponByCode((string) $sessionCoupon['code'], (int) $subtotal);
        if (!$coupon) {
            Session::forget('coupon');
            return null;
        }

        $payload = $this->mapCoupon($coupon);
        Session::put('coupon', $payload);

        return $payload;
    }

    private function calculateCartSummary(array $cart, ?array $coupon = null): array
    {
        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $discount = 0;

        if ($coupon && isset($coupon['type'], $coupon['value'])) {
            if ($coupon['type'] === 'percent') {
                $discount = (int) round($subtotal * ((int) $coupon['value']) / 100);
                if (!empty($coupon['max_discount'])) {
                    $discount = min($discount, (int) $coupon['max_discount']);
                }
            } elseif ($coupon['type'] === 'fixed') {
                $discount = (int) $coupon['value'];
            }
        }

        $discount = min($discount, $subtotal);
        $total = max($subtotal - $discount, 0);
        return compact('subtotal', 'discount', 'total');
    }

    public function addToCart(Request $request, int $id)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1|max:999',
            'variant_id' => 'nullable|integer|exists:product_variants,id',
        ]);

        $product = Product::with('variants')
            ->where('status', 1)
            ->findOrFail($id);

        $quantityToAdd = (int) ($request->input('quantity', 1));
        $variantId = $request->input('variant_id');
        $variant = null;
        $variantSize = null;

        if ($product->variants->where('status', true)->count() > 0) {
            if (!$variantId) {
                $message = 'Vui lòng chọn phiên bản trước khi thêm vào giỏ';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['status' => 'error', 'message' => $message], 422);
                }
                return redirect()->back()->with('message', $message);
            }

            $variant = $product->variants->firstWhere('id', (int) $variantId);
            if (!$variant || !$variant->status) {
                $message = 'Biến thể sản phẩm không hợp lệ';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['status' => 'error', 'message' => $message], 422);
                }
                return redirect()->back()->with('message', $message);
            }

            $stockLimit = (int) $variant->stock_quantity;
            $unitPrice = $variant->sale_price && $variant->sale_price > 0 && $variant->sale_price < $variant->price
                ? $variant->sale_price
                : ((int) ($variant->price ?: $product->final_price));
            $variantSize = $variant->size;
        } else {
            $stockLimit = (int) $product->stock_quantity;
            $unitPrice = (int) $product->final_price;
        }

        if ($stockLimit <= 0) {
            $message = 'Sản phẩm đã hết hàng';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $message], 422);
            }
            return redirect()->back()->with('message', $message);
        }

        $cart = Session::get('cart', []);
        $cartKey = $variant ? ($product->id . ':' . $variant->id) : (string) $product->id;
        $currentQty = (int) ($cart[$cartKey]['quantity'] ?? 0);
        $nextQty = $currentQty + $quantityToAdd;
        if ($nextQty > $stockLimit) {
            $message = 'Số lượng trong giỏ đã đạt tồn kho hiện có';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $message], 422);
            }
            return redirect()->back()->with('message', $message);
        }

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] = $nextQty;
        } else {
            $cart[$cartKey] = [
                'cart_key' => $cartKey,
                'productid' => $product->id,
                'variant_id' => $variant?->id,
                'variant_size' => $variantSize,
                'proname' => $product->proname,
                'quantity' => $quantityToAdd,
                'price' => $unitPrice,
            ];
        }

        Session::put('cart', $cart);
        $payload = [
            'status' => 'success',
            'message' => 'Đã thêm vào giỏ hàng',
            'cart' => $cart,
            'cart_count' => count($cart),
        ];

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($payload);
        }

        return redirect()->back()->with('message', $payload['message']);
    }

    public function updateQuantity(Request $request, string $key)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1|max:999',
            'action' => 'nullable|in:increase,decrease',
        ]);

        $cart = Session::get('cart', []);
        if (!isset($cart[$key])) {
            return redirect()->back()->with('message', 'Sản phẩm không tồn tại trong giỏ hàng');
        }

        $item = $cart[$key];
        $product = Product::with('variants')->select('id', 'proname', 'status', 'stock_quantity')->find($item['productid']);
        if (!$product || !$product->status) {
            unset($cart[$key]);
            Session::put('cart', $cart);
            return redirect()->back()->with('message', 'Sản phẩm không còn khả dụng');
        }

        $stockLimit = (int) $product->stock_quantity;
        if (!empty($item['variant_id'])) {
            $variant = $product->variants->firstWhere('id', (int) $item['variant_id']);
            if (!$variant || !$variant->status) {
                unset($cart[$key]);
                Session::put('cart', $cart);
                return redirect()->back()->with('message', 'Biến thể sản phẩm không còn khả dụng');
            }
            $stockLimit = (int) $variant->stock_quantity;
        }

        $currentQty = (int) $cart[$key]['quantity'];
        $action = (string) $request->input('action', '');
        if ($action === 'increase') {
            $newQty = $currentQty + 1;
        } elseif ($action === 'decrease') {
            $newQty = max(1, $currentQty - 1);
        } else {
            $newQty = (int) $request->input('quantity', $currentQty);
        }

        if ($newQty > $stockLimit) {
            return redirect()->back()->with('message', 'Sản phẩm "' . $product->proname . '" chỉ còn ' . $stockLimit . ' trong kho.');
        }

        $cart[$key]['quantity'] = $newQty;
        Session::put('cart', $cart);
        return redirect()->back()->with('message', 'Đã cập nhật số lượng sản phẩm');
    }

    public function show()
    {
        $cart = Session::get('cart', []);
        $coupon = $this->getSessionCoupon($cart);
        $summary = $this->calculateCartSummary($cart, $coupon);
        $availableCoupons = $this->getAvailableCoupons((int) ($summary['subtotal'] ?? 0));

        return view('client.cart.index', compact('summary', 'coupon', 'availableCoupons'));
    }

    public function removeCart(string $key)
    {
        $cart = Session::get('cart', []);
        if (isset($cart[$key])) {
            unset($cart[$key]);
        }

        Session::put('cart', $cart);
        return redirect()->back()->with('message', 'Đã xóa sản phẩm khỏi giỏ hàng');
    }

    public function checkout()
    {
        $cart = Session::get('cart', []);
        $coupon = $this->getSessionCoupon($cart);
        $summary = $this->calculateCartSummary($cart, $coupon);

        return view('client.cart.checkout', compact('summary', 'coupon'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $cart = Session::get('cart', []);
        $subtotal = (int) collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $coupon = $this->findValidCouponByCode(trim((string) $request->code), $subtotal);
        if (!$coupon) {
            return back()->with('message', 'Mã giảm giá không hợp lệ');
        }

        Session::put('coupon', $this->mapCoupon($coupon));
        return back()->with('message', 'Áp dụng mã giảm giá thành công');
    }

    public function removeCoupon()
    {
        Session::forget('coupon');
        return back()->with('message', 'Đã gỡ mã giảm giá');
    }

    public function save(Request $request)
    {
        $request->validate(
            [
                'fullname' => 'required|min:3|max:100',
                'phone' => 'required|min:8|max:20',
                'address' => 'required|min:5|max:255',
                'payment_method' => 'required|in:cod,bank_transfer,ewallet',
                'note' => 'nullable|max:400',
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute phải có ít nhất :min ký tự',
                'max' => ':attribute không được vượt quá :max ký tự',
                'payment_method.in' => 'Phương thức thanh toán không hợp lệ',
            ],
            [
                'fullname' => 'Họ tên',
                'phone' => 'Số điện thoại',
                'address' => 'Địa chỉ',
                'payment_method' => 'Phương thức thanh toán',
                'note' => 'Ghi chú',
            ]
        );

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('message', 'Không tồn tại giỏ hàng');
        }

        $productIds = array_map(fn ($item) => (int) $item['productid'], $cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        $variantIds = collect($cart)->pluck('variant_id')->filter()->map(fn ($i) => (int) $i)->values()->all();
        $variants = ProductVariant::whereIn('id', $variantIds)->get()->keyBy('id');

        foreach ($cart as $item) {
            $product = $products->get((int) $item['productid']);
            if (!$product || !$product->status) {
                return redirect()->back()->with('message', 'Có sản phẩm không còn khả dụng. Vui lòng kiểm tra lại giỏ hàng.');
            }
            if (!empty($item['variant_id'])) {
                $variant = $variants->get((int) $item['variant_id']);
                if (!$variant || !$variant->status || (int) $item['quantity'] > (int) $variant->stock_quantity) {
                    return redirect()->back()->with('message', 'Sản phẩm "' . $product->proname . '" phiên bản ' . ($variant->size ?? 'N/A') . ' không đủ tồn kho.');
                }
            } elseif ((int) $item['quantity'] > (int) $product->stock_quantity) {
                return redirect()->back()->with('message', 'Sản phẩm "' . $product->proname . '" chỉ còn ' . $product->stock_quantity . ' trong kho.');
            }
        }

        $customer = Customer::where('phone', $request->phone)->first();
        if (!$customer) {
            $customer = Customer::create([
                'user_id' => auth()->id(),
                'fullname' => $request->fullname,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        } elseif (auth()->check() && !$customer->user_id) {
            $customer->update(['user_id' => auth()->id()]);
        }

        $coupon = $this->getSessionCoupon($cart);
        $summary = $this->calculateCartSummary($cart, $coupon);
        $note = trim((string) $request->note);
        if ($coupon && !empty($coupon['code'])) {
            $note = trim($note . ' | Coupon: ' . $coupon['code']);
        }

        $createdOrderId = null;

        try {
            DB::transaction(function () use ($cart, $customer, $summary, $note, $request, $coupon, &$createdOrderId) {
                $finalTotal = $summary['total'];
                if (!empty($coupon['code'])) {
                    $subtotal = (int) collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
                    $couponRow = Coupon::where('code', $coupon['code'])->lockForUpdate()->first();
                    if (!$couponRow) {
                        throw new \RuntimeException('Mã giảm giá không còn tồn tại.');
                    }
                    $validCoupon = $this->findValidCouponByCode($couponRow->code, $subtotal);
                    if (!$validCoupon) {
                        throw new \RuntimeException('Mã giảm giá không còn hiệu lực.');
                    }
                    $couponData = $this->mapCoupon($validCoupon);
                    $recalculated = $this->calculateCartSummary($cart, $couponData);
                    $finalTotal = $recalculated['total'];
                    $couponRow->increment('used_count');
                }

                $order = Order::create([
                    'customer_id' => $customer->id,
                    'order_code' => $this->generateOrderCode(),
                    'total_amount' => $finalTotal,
                    'payment_method' => $request->payment_method,
                    'note' => $note,
                ]);
                $createdOrderId = (int) $order->id;

                $orderItems = [];
                foreach ($cart as $item) {
                    $product = Product::where('id', $item['productid'])->lockForUpdate()->first();
                    if (!$product || !$product->status) {
                        throw new \RuntimeException('Sản phẩm "' . ($product->proname ?? 'không xác định') . '" không còn khả dụng.');
                    }

                    if (!empty($item['variant_id'])) {
                        $variant = ProductVariant::where('id', $item['variant_id'])->lockForUpdate()->first();
                        if (!$variant || !$variant->status || $variant->stock_quantity < $item['quantity']) {
                            throw new \RuntimeException('Sản phẩm "' . $product->proname . '" phiên bản ' . ($variant->size ?? 'N/A') . ' không đủ tồn kho.');
                        }
                        $variant->decrement('stock_quantity', (int) $item['quantity']);
                    } else {
                        if ($product->stock_quantity < $item['quantity']) {
                            throw new \RuntimeException('Sản phẩm "' . $product->proname . '" không đủ tồn kho.');
                        }
                        $product->decrement('stock_quantity', (int) $item['quantity']);
                    }

                    $orderItems[] = [
                        'order_id' => $order->id,
                        'product_id' => $item['productid'],
                        'product_variant_id' => $item['variant_id'] ?? null,
                        'variant_size' => $item['variant_size'] ?? null,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                OrderItem::insert($orderItems);
            });
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('message', $e->getMessage());
        }

        Session::forget('cart');
        Session::forget('coupon');

        if (in_array($request->payment_method, ['bank_transfer', 'ewallet'], true) && $createdOrderId) {
            return redirect()->route('order.show', $createdOrderId)->with('message', 'Đặt hàng thành công. Vui lòng thanh toán đơn hàng.');
        }

        return redirect()->route('cart.show')->with('message', 'Đặt hàng thành công');
    }
}
