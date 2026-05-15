<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    private function generateUniqueOrderCode(array &$usedCodes): string
    {
        for ($i = 0; $i < 100; $i++) {
            $code = 'ORD-' . str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            if (!isset($usedCodes[$code])) {
                $usedCodes[$code] = true;
                return $code;
            }
        }

        throw new \RuntimeException('Không thể tạo mã đơn hàng cho dữ liệu mẫu.');
    }

    public function run(): void
    {
        $customers = DB::table('customers')->orderBy('id')->get();
        $variants = DB::table('product_variants')->orderBy('id')->limit(12)->get()->groupBy('product_id');
        $products = DB::table('products')->get()->keyBy('id');
        $usedCodes = array_fill_keys(DB::table('orders')->pluck('order_code')->all(), true);

        foreach ($customers as $index => $customer) {
            $method = $index % 3 === 0 ? 'cod' : ($index % 3 === 1 ? 'bank_transfer' : 'ewallet');
            $paymentStatus = $method === 'cod' ? 'unpaid' : ($index % 2 === 0 ? 'paid' : 'unpaid');
            $status = $index % 4 === 0 ? 'pending' : ($index % 4 === 1 ? 'processing' : 'completed');

            $orderId = DB::table('orders')->insertGetId([
                'customer_id' => $customer->id,
                'order_code' => $this->generateUniqueOrderCode($usedCodes),
                'total_amount' => 0,
                'status' => $status,
                'payment_method' => $method,
                'payment_status' => $paymentStatus,
                'note' => 'Đơn hàng demo seeder',
                'created_at' => now()->subDays(5 - min($index, 5)),
                'updated_at' => now(),
            ]);

            $picked = 0;
            $total = 0;
            foreach ($variants as $productId => $rows) {
                if ($picked >= 2) {
                    break;
                }
                $variant = $rows->first();
                $product = $products[$productId] ?? null;
                if (!$variant || !$product) {
                    continue;
                }

                $qty = rand(1, 2);
                $price = (int) ($variant->sale_price ?: $variant->price ?: $product->price);
                $lineTotal = $price * $qty;
                $total += $lineTotal;

                DB::table('order_items')->insert([
                    'order_id' => $orderId,
                    'product_id' => $productId,
                    'product_variant_id' => $variant->id,
                    'variant_size' => $variant->size,
                    'quantity' => $qty,
                    'price' => $price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $picked++;
            }

            DB::table('orders')->where('id', $orderId)->update([
                'total_amount' => $total,
                'updated_at' => now(),
            ]);
        }
    }
}
