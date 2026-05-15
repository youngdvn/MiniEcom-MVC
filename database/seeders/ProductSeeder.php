<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = DB::table('categories')->pluck('cateid', 'catename');
        $brands = DB::table('brands')->pluck('id', 'brandname');

        $products = [
            [
                'name' => 'iPhone 15 128GB',
                'category' => 'Điện thoại',
                'brand' => 'Apple',
                'price' => 22990000,
                'sale_price' => 20990000,
                'stock' => 30,
                'description' => 'iPhone 15 trang bị chip A16 Bionic, màn hình Super Retina XDR 6.1 inch và camera chính 48MP cho chất lượng ảnh sắc nét. Máy hỗ trợ Dynamic Island, sạc USB-C và thời lượng pin ổn định cho nhu cầu sử dụng cả ngày.',
            ],
            [
                'name' => 'Galaxy S24 256GB',
                'category' => 'Điện thoại',
                'brand' => 'Samsung',
                'price' => 20990000,
                'sale_price' => 19490000,
                'stock' => 24,
                'description' => 'Galaxy S24 sở hữu thiết kế gọn nhẹ, màn hình AMOLED 120Hz và hiệu năng mạnh mẽ cho đa nhiệm. Cụm camera tối ưu chụp đêm, pin bền bỉ và giao diện One UI thân thiện cho trải nghiệm sử dụng linh hoạt.',
            ],
            [
                'name' => 'Xiaomi 14T 256GB',
                'category' => 'Điện thoại',
                'brand' => 'Xiaomi',
                'price' => 12990000,
                'sale_price' => 11990000,
                'stock' => 35,
                'description' => 'Xiaomi 14T mang lại cân bằng tốt giữa hiệu năng và giá thành với màn hình chất lượng cao, tốc độ phản hồi nhanh và camera tối ưu AI. Thiết bị phù hợp người dùng cần một smartphone mạnh để học tập, giải trí và làm việc hằng ngày.',
            ],
            [
                'name' => 'MacBook Air M3 13 inch',
                'category' => 'Laptop',
                'brand' => 'Apple',
                'price' => 28990000,
                'sale_price' => 27490000,
                'stock' => 18,
                'description' => 'MacBook Air M3 13 inch nổi bật với thiết kế mỏng nhẹ, màn hình Liquid Retina sắc nét và thời lượng pin dài. Chip M3 cho khả năng xử lý mượt mà các tác vụ văn phòng, sáng tạo nội dung và lập trình cơ bản.',
            ],
            [
                'name' => 'Dell XPS 13 Plus',
                'category' => 'Laptop',
                'brand' => 'Dell',
                'price' => 35990000,
                'sale_price' => 33990000,
                'stock' => 12,
                'description' => 'Dell XPS 13 Plus có thiết kế cao cấp, bàn phím tràn cạnh hiện đại và màn hình độ phân giải cao. Máy hướng đến người dùng chuyên nghiệp cần hiệu suất ổn định, di động tốt và trải nghiệm gõ phím thoải mái.',
            ],
            [
                'name' => 'Asus Vivobook 15 OLED',
                'category' => 'Laptop',
                'brand' => 'Asus',
                'price' => 18990000,
                'sale_price' => 17290000,
                'stock' => 20,
                'description' => 'Asus Vivobook 15 OLED mang đến màn hình OLED rực rỡ, âm thanh sống động và cấu hình đáp ứng tốt nhu cầu học tập, văn phòng. Đây là lựa chọn phù hợp cho người dùng cần laptop cân bằng giữa hiệu năng và chi phí.',
            ],
            [
                'name' => 'iPad Air M2 WiFi',
                'category' => 'Máy tính bảng',
                'brand' => 'Apple',
                'price' => 16990000,
                'sale_price' => 15990000,
                'stock' => 16,
                'description' => 'iPad Air M2 WiFi có thiết kế mỏng nhẹ, màn hình hiển thị sắc nét và chip M2 mạnh mẽ cho công việc lẫn giải trí. Thiết bị hỗ trợ tốt cho ghi chú, học online, chỉnh sửa nội dung và sử dụng cùng Apple Pencil.',
            ],
            [
                'name' => 'Galaxy Tab S9 FE',
                'category' => 'Máy tính bảng',
                'brand' => 'Samsung',
                'price' => 11990000,
                'sale_price' => 10990000,
                'stock' => 22,
                'description' => 'Galaxy Tab S9 FE được tối ưu cho học tập và giải trí với màn hình lớn, bút S Pen đi kèm và giao diện thân thiện. Máy có pin tốt, khả năng đa nhiệm ổn và phù hợp người dùng cần tablet đa năng mỗi ngày.',
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'category' => 'Tai nghe',
                'brand' => 'Sony',
                'price' => 8990000,
                'sale_price' => 8290000,
                'stock' => 26,
                'description' => 'Sony WH-1000XM5 là tai nghe chống ồn chủ động cao cấp với chất âm chi tiết, đeo êm và thời lượng pin dài. Sản phẩm phù hợp cho làm việc, học tập, nghe nhạc chất lượng cao và di chuyển thường xuyên.',
            ],
            [
                'name' => 'Logitech MX Master 3S',
                'category' => 'Chuột - Bàn phím',
                'brand' => 'Logitech',
                'price' => 2690000,
                'sale_price' => 2390000,
                'stock' => 40,
                'description' => 'Logitech MX Master 3S là chuột không dây cao cấp cho công việc chuyên sâu với cảm biến chính xác, cuộn MagSpeed và nút tùy biến linh hoạt. Thiết kế công thái học giúp sử dụng lâu dài thoải mái và hiệu quả.',
            ],
        ];

        foreach ($products as $item) {
            DB::table('products')->insert([
                'proname' => $item['name'],
                'slug' => Str::slug($item['name']),
                'cateid' => $categories[$item['category']] ?? 1,
                'brandid' => $brands[$item['brand']] ?? 1,
                'price' => $item['price'],
                'sale_price' => $item['sale_price'],
                'stock_quantity' => $item['stock'],
                'thumbnail' => 'default.png',
                'description' => $item['description'],
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
