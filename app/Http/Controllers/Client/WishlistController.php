<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with('product')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(12);

        return view('client.wishlist.index', compact('wishlists'));
    }

    public function add(int $productId)
    {
        Product::where('status', 1)->findOrFail($productId);

        Wishlist::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $productId,
        ]);

        return back()->with('message', 'Đã thêm vào danh sách yêu thích');
    }

    public function remove(int $productId)
    {
        Wishlist::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->delete();

        return back()->with('message', 'Đã xóa khỏi danh sách yêu thích');
    }
}
