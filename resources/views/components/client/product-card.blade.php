@props(['product', 'showWishlistIcon' => true])

@php
    $hasSale = !empty($product->sale_price) && $product->sale_price > 0 && $product->sale_price < $product->price;
    $discountPercent = $hasSale ? round((($product->price - $product->sale_price) / $product->price) * 100) : 0;
    $stock = (int) ($product->stock_quantity ?? 0);
    $variants = $product->relationLoaded('variants')
        ? $product->variants->where('status', true)->take(6)
        : collect();
@endphp

<div class="group relative h-full overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
    <a href="{{ route('product.show', ['slug' => $product->slug]) }}" class="absolute inset-0 z-10" aria-label="Xem chi tiết {{ $product->proname }}"></a>
    <div class="relative overflow-hidden bg-slate-100">
        <img src="{{ asset('storage/products/' . ($product->thumbnail ?? 'default.png')) }}"
             class="h-56 w-full object-cover transition duration-500 group-hover:scale-105"
             alt="{{ $product->proname }}">
        <div class="pointer-events-none absolute inset-x-0 bottom-0 h-20 bg-linear-to-t from-slate-900/30 to-transparent"></div>

        @if($hasSale)
            <span class="absolute left-3 top-3 rounded-full bg-rose-600 px-2.5 py-1 text-xs font-bold text-white shadow">
                -{{ $discountPercent }}%
            </span>
        @endif

        @if($stock <= 0)
            <span class="absolute left-3 bottom-3 z-20 rounded-full bg-slate-900/85 px-2.5 py-1 text-xs font-bold text-white">
                Hết hàng
            </span>
        @elseif($stock <= 5)
            <span class="absolute left-3 bottom-3 z-20 rounded-full bg-amber-400 px-2.5 py-1 text-xs font-bold text-slate-900">
                Sắp hết: {{ $stock }}
            </span>
        @endif

        @if($showWishlistIcon)
            @auth
                <form action="{{ route('wishlist.add', $product->id) }}" method="POST" class="absolute right-3 top-3 z-20">
                    @csrf
                    <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/95 text-rose-500 shadow transition hover:scale-105 hover:bg-rose-100" title="Thêm yêu thích">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
    </svg>
                    </button>
                </form>
            @else
                <a href="{{ route('client.login') }}" class="absolute right-3 top-3 z-20 inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/95 text-rose-500 shadow transition hover:scale-105 hover:bg-rose-50" title="Đăng nhập để thêm yêu thích">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
      <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
    </svg>
                </a>
            @endauth
        @endif
    </div>

    <div class="relative z-20 flex min-h-52.5 flex-col p-4">
        <h3 class="line-clamp-2 min-h-12 text-sm font-semibold text-slate-900">{{ $product->proname }}</h3>

        @if($variants->isNotEmpty())
            <div class="mt-2 min-h-[74px]">
                <p class="mb-1 text-[11px] font-semibold uppercase tracking-wide text-slate-500">Phiên bản</p>
                <div class="flex min-h-[48px] flex-wrap content-start gap-1.5">
                    @foreach($variants as $variant)
                        @php
                            $variantId = 'variant-' . $product->id . '-' . $variant->id;
                            $outOfStock = (int) $variant->stock_quantity <= 0;
                        @endphp
                        <div>
                            <input
                                type="radio"
                                name="variant_id"
                                id="{{ $variantId }}"
                                value="{{ $variant->id }}"
                                class="peer sr-only"
                                form="add-cart-{{ $product->id }}"
                                {{ $outOfStock ? 'disabled' : '' }}
                                required
                            >
                            <label
                                for="{{ $variantId }}"
                                class="cursor-pointer rounded-md border px-2 py-0.5 text-[11px] font-semibold transition
                                    {{ $outOfStock
                                        ? 'cursor-not-allowed border-rose-200 bg-rose-50 text-rose-400 line-through'
                                        : 'border-slate-300 bg-slate-50 text-slate-700 hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700'
                                    }}
                                    peer-checked:border-sky-500 peer-checked:bg-sky-500 peer-checked:text-white"
                            >
                                {{ $variant->size }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($hasSale)
            <div class="mt-2">
                <p class="text-lg font-extrabold text-rose-600">{{ number_format($product->sale_price) }} VND</p>
                <p class="text-xs text-slate-400 line-through">{{ number_format($product->price) }} VND</p>
            </div>
        @else
            <p class="mt-2 text-lg font-extrabold text-slate-900">{{ number_format($product->price) }} VND</p>
        @endif

        <div class="mt-auto grid grid-cols-2 gap-2 pt-3">
            <a href="{{ route('product.show', ['slug' => $product->slug]) }}"
               class="inline-flex items-center justify-center rounded-lg border border-sky-200 bg-sky-50 px-3 py-2 text-xs font-semibold text-sky-700 transition hover:bg-sky-100">
                Chi tiết
            </a>
            <form id="add-cart-{{ $product->id }}" action="{{ route('cart.add', $product->id) }}" method="POST" class="form-add-cart">
                @csrf
                @if($stock > 0)
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-emerald-500">
                        Thêm giỏ
                    </button>
                @else
                    <button type="button" disabled class="inline-flex w-full cursor-not-allowed items-center justify-center rounded-lg bg-slate-300 px-3 py-2 text-xs font-semibold text-slate-600">
                        Hết hàng
                    </button>
                @endif
            </form>
        </div>
    </div>
</div>
