<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $products = DB::table('products')
            ->join('categories', 'products.cateid', '=', 'categories.cateid')
            ->select('products.id', 'products.price', 'products.sale_price', 'products.stock_quantity', 'categories.catename')
            ->get();

        foreach ($products as $product) {
            $basePrice = (int) $product->price;
            $baseSale = (int) ($product->sale_price ?: $basePrice);
            $stock = max(1, (int) $product->stock_quantity);

            $versions = match ($product->catename) {
                'Điện thoại' => [
                    ['name' => '8GB/128GB - Đen', 'delta' => 0],
                    ['name' => '8GB/256GB - Xanh', 'delta' => 1000000],
                    ['name' => '12GB/256GB - Bạc', 'delta' => 2000000],
                ],
                'Laptop' => [
                    ['name' => '16GB/512GB SSD', 'delta' => 0],
                    ['name' => '16GB/1TB SSD', 'delta' => 2500000],
                    ['name' => '32GB/1TB SSD', 'delta' => 5000000],
                ],
                'Máy tính bảng' => [
                    ['name' => 'WiFi 128GB', 'delta' => 0],
                    ['name' => 'WiFi 256GB', 'delta' => 1500000],
                    ['name' => '5G 256GB', 'delta' => 3000000],
                ],
                'Tai nghe' => [
                    ['name' => 'Đen', 'delta' => 0],
                    ['name' => 'Bạc', 'delta' => 200000],
                    ['name' => 'Xanh Navy', 'delta' => 200000],
                ],
                default => [
                    ['name' => 'Tiêu chuẩn', 'delta' => 0],
                    ['name' => 'Nâng cao', 'delta' => 300000],
                    ['name' => 'Pro', 'delta' => 600000],
                ],
            };

            $stocks = $this->splitStock($stock, count($versions));

            foreach ($versions as $idx => $v) {
                DB::table('product_variants')->insert([
                    'product_id' => $product->id,
                    'size' => $v['name'],
                    'price' => $basePrice + $v['delta'],
                    'sale_price' => max(0, $baseSale + $v['delta']),
                    'stock_quantity' => $stocks[$idx],
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function splitStock(int $total, int $parts): array
    {
        $base = intdiv($total, $parts);
        $remain = $total % $parts;
        $result = [];

        for ($i = 0; $i < $parts; $i++) {
            $result[] = $base + ($i < $remain ? 1 : 0);
        }

        return $result;
    }
}
