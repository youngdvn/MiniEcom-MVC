<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $keyword = trim((string) request('keyword', ''));
        $category = request('category');
        $brand = request('brand');
        $minPrice = request('min_price');
        $maxPrice = request('max_price');
        $sort = request('sort', 'latest');

        $products = Product::with(['category', 'brand', 'variants'])
            ->where('status', 1)
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where('proname', 'like', "%{$keyword}%");
            })
            ->when($category, function ($query) use ($category) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $category));
            })
            ->when($brand, function ($query) use ($brand) {
                $query->whereHas('brand', fn ($q) => $q->where('slug', $brand));
            })
            ->when(is_numeric($minPrice), function ($query) use ($minPrice) {
                $query->where('price', '>=', (int) $minPrice);
            })
            ->when(is_numeric($maxPrice), function ($query) use ($maxPrice) {
                $query->where('price', '<=', (int) $maxPrice);
            })
            ->when($sort === 'price_asc', fn ($query) => $query->orderBy('price'))
            ->when($sort === 'price_desc', fn ($query) => $query->orderByDesc('price'))
            ->when($sort === 'name_asc', fn ($query) => $query->orderBy('proname'))
            ->when($sort === 'name_desc', fn ($query) => $query->orderByDesc('proname'))
            ->when($sort === 'latest', fn ($query) => $query->latest())
            ->paginate(12)
            ->withQueryString();

        $categories = Category::where('status', 1)->orderBy('catename')->get(['cateid', 'catename', 'slug']);
        $brands = Brand::where('status', 1)->orderBy('brandname')->get(['id', 'brandname', 'slug']);

        return view('client.product.index', compact('products', 'categories', 'brands', 'keyword', 'sort'));
    }

    public function show(string $slug)
    {
        $product = Product::with(['images', 'category', 'brand', 'variants'])
            ->where('status', 1)
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedProducts = Product::where('status', 1)
            ->where('id', '!=', $product->id)
            ->when($product->cateid, fn ($query) => $query->where('cateid', $product->cateid))
            ->latest()
            ->take(8)
            ->get();

        return view('client.product.show', compact('product', 'relatedProducts'));
    }

    public function category(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::with('variants')->where('status', 1)
            ->where('cateid', $category->cateid)
            ->latest()
            ->paginate(12);

        return view('client.product.category', compact('category', 'products'));
    }

    public function brand(string $slug)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();
        $products = Product::with('variants')->where('status', 1)
            ->where('brandid', $brand->id)
            ->latest()
            ->paginate(12);

        return view('client.product.brand', compact('brand', 'products'));
    }

    public function search(Request $request)
    {
        $keyword = trim((string) $request->query('keyword', ''));
        $products = Product::with('variants')->where('status', 1)
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where('proname', 'like', "%{$keyword}%");
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('client.product.search', compact('products', 'keyword'));
    }
}
