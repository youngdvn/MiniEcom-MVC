<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartCheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    private function seedBaseProduct(): array
    {
        $category = Category::create([
            'catename' => 'Ao',
            'slug' => 'ao',
            'status' => 1,
        ]);

        $brand = Brand::create([
            'brandname' => 'Brand A',
            'slug' => 'brand-a',
            'status' => 1,
        ]);

        $product = Product::create([
            'proname' => 'Ao thun test',
            'cateid' => $category->cateid,
            'brandid' => $brand->id,
            'slug' => 'ao-thun-test',
            'price' => 200000,
            'sale_price' => 150000,
            'stock_quantity' => 20,
            'status' => 1,
        ]);

        return [$product, $brand, $category];
    }

    public function test_add_to_cart_requires_variant_when_product_has_sizes(): void
    {
        [$product] = $this->seedBaseProduct();

        ProductVariant::create([
            'product_id' => $product->id,
            'size' => 'M',
            'price' => 180000,
            'sale_price' => 160000,
            'stock_quantity' => 5,
            'status' => 1,
        ]);

        $response = $this->postJson(route('cart.add', $product->id), [
            'quantity' => 1,
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('message', 'Vui lòng chọn phiên bản trước khi thêm vào giỏ');
    }

    public function test_can_apply_coupon_from_cart(): void
    {
        Coupon::create([
            'code' => 'GIAM10',
            'type' => 'percent',
            'value' => 10,
            'min_order' => 100000,
            'status' => 1,
        ]);

        $this->withSession([
            'cart' => [
                '1' => [
                    'cart_key' => '1',
                    'productid' => 1,
                    'proname' => 'San pham test',
                    'quantity' => 2,
                    'price' => 80000,
                ],
            ],
        ]);

        $response = $this->post(route('cart.coupon.apply'), [
            'code' => 'GIAM10',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('coupon.code', 'GIAM10');
    }

    public function test_checkout_bank_transfer_redirects_to_order_detail(): void
    {
        [$product] = $this->seedBaseProduct();

        $user = User::create([
            'username' => 'testuser',
            'fullname' => 'Nguyen Van Test',
            'email' => 'test@example.com',
            'password' => '12345678',
            'role' => 0,
        ]);

        $this->actingAs($user);

        $this->withSession([
            'cart' => [
                (string) $product->id => [
                    'cart_key' => (string) $product->id,
                    'productid' => $product->id,
                    'proname' => $product->proname,
                    'quantity' => 1,
                    'price' => 150000,
                ],
            ],
        ]);

        $response = $this->post(route('cart.save'), [
            'fullname' => 'Nguyen Van Test',
            'email' => 'test@example.com',
            'phone' => '0912345678',
            'address' => 'So 1 Ha Noi',
            'payment_method' => 'bank_transfer',
            'note' => 'test',
        ]);

        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertSame('unpaid', $order->payment_status);

        $response->assertRedirect(route('order.show', $order->id));
    }
}
