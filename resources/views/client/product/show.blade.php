@extends('client.layout.app')
@section('title', $product->proname)

@php
    $hasSale = !empty($product->sale_price) && $product->sale_price > 0 && $product->sale_price < $product->price;
    $discountPercent = $hasSale ? round((($product->price - $product->sale_price) / $product->price) * 100) : 0;
    $galleryImages = collect([$product->thumbnail])->merge($product->images->pluck('image'))->filter()->unique()->values();
    $stock = (int) ($product->stock_quantity ?? 0);
    $availableVariants = $product->variants->where('status', true)->values();
@endphp

@section('content')
    <div class="mb-4 text-sm text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-sky-700">Trang chủ</a>
        <span class="mx-1">/</span>
        <a href="{{ route('product.index') }}" class="hover:text-sky-700">Sản phẩm</a>
        <span class="mx-1">/</span>
        <span class="font-semibold text-slate-700">{{ $product->proname }}</span>
    </div>

    <section class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div class="grid grid-cols-1 lg:grid-cols-[1.08fr_1fr]">
            <div class="border-b border-slate-200 bg-slate-50 p-4 lg:border-r lg:border-b-0 lg:p-6">
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <img
                        src="{{ asset('storage/products/' . ($product->thumbnail ?? 'default.png')) }}"
                        alt="{{ $product->proname }}"
                        class="h-72 w-full object-cover sm:h-105 lg:h-125"
                    >
                </div>
                @if($galleryImages->count() > 1)
                    <div class="mt-3 grid grid-cols-4 gap-2 sm:grid-cols-5">
                        @foreach($galleryImages as $img)
                            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                                <img src="{{ asset('storage/products/' . $img) }}" alt="{{ $product->proname }}" class="h-16 w-full object-cover sm:h-20">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="p-4 sm:p-6 lg:p-7">
                <div class="mb-3 flex flex-wrap items-center gap-2 text-xs">
                    @if($product->category)
                        <span class="rounded-full bg-sky-100 px-3 py-1 font-semibold text-sky-700">{{ $product->category->catename }}</span>
                    @endif
                    @if($product->brand)
                        <span class="rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-700">{{ $product->brand->brandname }}</span>
                    @endif
                    @if($stock <= 0)
                        <span class="rounded-full bg-slate-900 px-3 py-1 font-semibold text-white">Hết hàng</span>
                    @elseif($stock <= 5)
                        <span class="rounded-full bg-amber-400 px-3 py-1 font-semibold text-slate-900">Sắp hết: {{ $stock }}</span>
                    @else
                        <span class="rounded-full bg-emerald-100 px-3 py-1 font-semibold text-emerald-700">Còn hàng: {{ $stock }}</span>
                    @endif
                </div>

                <h1 class="text-3xl leading-tight font-extrabold text-slate-900">{{ $product->proname }}</h1>

                <div class="mt-5 p-4">
                    @if($hasSale)
                        <div class="flex items-center gap-3">
                            <p class="text-3xl font-extrabold text-rose-600">{{ number_format($product->sale_price) }} VND</p>
                            <span class="rounded-full bg-rose-600 px-2.5 py-1 text-xs font-bold text-white">-{{ $discountPercent }}%</span>
                        </div>
                        <p class="mt-1 text-base text-slate-500 line-through">{{ number_format($product->price) }} VND</p>
                    @else
                        <p class="text-4xl font-extrabold text-slate-900">{{ number_format($product->price) }} VND</p>
                    @endif
                </div>

                <div class="mt-5 rounded-xl border border-slate-200 bg-slate-50 p-6">
                    <div class="grid grid-cols-2 gap-3 items-center justify-between">
                        <div class="mb-3">
                            <label class="mb-1 block text-xs font-semibold text-slate-600">Số lượng</label>
                            <input
                            type="number"
                            name="quantity"
                            form="add-to-cart-form"
                            min="1"
                            max="{{ max($stock, 1) }}"
                            value="1"
                            class="h-11 w-full rounded-lg border border-slate-300 bg-white px-3 text-sm focus:border-sky-500 focus:outline-none"
                            {{ $stock <= 0 ? 'disabled' : '' }}
                        >
                    </div>
                    @if($availableVariants->isNotEmpty())
                        <div class="mb-3 max-w-70">
                            <label class="mb-1 block text-xs font-semibold text-slate-600">Phiên bản</label>
                            <select
                                name="variant_id"
                                form="add-to-cart-form"
                                class="h-11 w-full rounded-lg border border-slate-300 bg-white px-3 text-sm focus:border-sky-500 focus:outline-none"
                                required
                                >
                                <option value="">Chọn phiên bản</option>
                                @foreach($availableVariants as $variant)
                                @php
                                        $variantOut = (int) $variant->stock_quantity <= 0;
                                        $variantPrice = ($variant->sale_price && $variant->sale_price > 0 && $variant->sale_price < $variant->price) ? $variant->sale_price : ($variant->price ?: $product->final_price);
                                        @endphp
                                    <option value="{{ $variant->id }}" {{ $variantOut ? 'disabled' : '' }}>
                                        {{ $variant->size }} - {{ number_format($variantPrice) }}VND {{ $variantOut ? '(Hết hàng)' : '' }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif        
                    </div>
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <form id="add-to-cart-form" action="{{ route('cart.add', $product->id) }}" method="POST" class="form-add-cart">
                            @csrf
                            @if($stock > 0)
                                <button type="submit" class="inline-flex h-11 w-full items-center justify-center rounded-lg bg-emerald-600 px-4 text-sm font-bold text-white hover:bg-emerald-500">
                                    Thêm vào giỏ hàng
                                </button>
                            @else
                                <button type="button" disabled class="inline-flex h-11 w-full cursor-not-allowed items-center justify-center rounded-lg bg-slate-300 px-4 text-sm font-bold text-slate-600">
                                    Sản phẩm đã hết hàng
                                </button>
                            @endif
                        </form>

                        @auth
                            <form action="{{ route('wishlist.add', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex h-11 w-full items-center justify-center rounded-lg border border-rose-300 bg-white px-4 text-sm font-bold text-rose-600 hover:bg-rose-50">
                                    Thêm vào yêu thích
                                </button>
                            </form>
                        @else
                            <a href="{{ route('client.login') }}" class="inline-flex h-11 w-full items-center justify-center rounded-lg border border-rose-300 bg-white px-4 text-sm font-bold text-rose-600 hover:bg-rose-50">
                                Yêu thích
                            </a>
                        @endauth
                    </div>
                </div>

                <div class="mt-4 rounded-xl border border-slate-200 bg-white p-4 text-sm text-slate-600">
                    <p>- Hỗ trợ đổi trả theo chính sách cửa hàng.</p>
                    <p class="mt-1">- Giao hàng nhanh toàn quốc.</p>
                    <p class="mt-1">- Tư vấn chọn sản phẩm phù hợp theo nhu cầu.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-6 rounded-lg border-dashed border border-slate-400 bg-white p-5 sm:p-6">
        <h2 class="mb-3 text-2xl font-extrabold text-slate-900">Mô tả sản phẩm</h2>
        <div class="prose prose-slate max-w-none leading-7">
            {!! $product->description !!}
        </div>
    </section>

    @if($relatedProducts->isNotEmpty())
        <section class="mt-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-900">Sản phẩm liên quan</h2>
                <a href="{{ route('product.index') }}" class="text-sm font-semibold text-sky-700 hover:text-sky-600">Xem thêm sản phẩm</a>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($relatedProducts as $item)
                    <x-client.product-card :product="$item" />
                @endforeach
            </div>
        </section>
    @endif
@endsection
