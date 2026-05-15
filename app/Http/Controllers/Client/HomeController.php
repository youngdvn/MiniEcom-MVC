<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $newProducts = Product::with('variants')->where('status', 1)
            ->latest()
            ->take(8)
            ->get();

        $popularProducts = Product::with('variants')->where('status', 1)
            ->orderByDesc('price')
            ->take(8)
            ->get();

        $saleProducts = Product::with('variants')->where('status', 1)
            ->orderBy('price')
            ->take(8)
            ->get();

        $categories = Category::where('status', 1)
            ->select('catename', 'slug')
            ->orderBy('catename')
            ->take(8)
            ->get();

        $brands = Brand::where('status', 1)
            ->select('brandname', 'slug')
            ->orderBy('brandname')
            ->take(8)
            ->get();

        $featuredPosts = Post::where('status', 1)
            ->select('title', 'slug', 'image', 'created_at')
            ->latest()
            ->take(4)
            ->get();

        $banners = Banner::where('status', true)
            ->orderBy('sort_order')
            ->latest('id')
            ->get();

        return view('client.home.index', compact(
            'banners',
            'newProducts',
            'popularProducts',
            'saleProducts',
            'categories',
            'brands',
            'featuredPosts'
        ));
    }
}
