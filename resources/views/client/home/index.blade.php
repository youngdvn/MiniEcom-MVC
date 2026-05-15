@extends('client.layout.app')
@section('title', 'Trang chủ')

@section('content')
    <section class="relative left-1/2 right-1/2 mx-[-50vw] mb-10 w-screen overflow-hidden border-y border-slate-200 bg-white shadow-sm">
        @if($banners->isNotEmpty())
            <div class="relative" data-banner-slider>
                <div class="relative h-56 sm:h-72 lg:h-svh">
                    @foreach($banners as $index => $banner)
                        <a
                            href="{{ $banner->link ?: '#' }}"
                            class="absolute inset-0 transition-opacity duration-500 {{ $index === 0 ? 'opacity-100 z-10' : 'pointer-events-none opacity-0 z-0' }}"
                            data-banner-slide
                        >
                            <img src="{{ $banner->image }}" alt="{{ $banner->title }}" class="h-full w-full object-cover">
                            <div class="absolute inset-0 bg-linear-to-r from-slate-900/65 via-slate-900/30 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4 text-white sm:bottom-6 sm:left-6">
                                <h2 class="text-xl font-extrabold sm:text-2xl lg:text-3xl">{{ $banner->title }}</h2>
                                @if($banner->subtitle)
                                    <p class="mt-1 text-sm text-white/90 sm:text-base">{{ $banner->subtitle }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <button
                    type="button"
                    class="absolute top-1/2 left-5 z-20 -translate-y-1/2 rounded-full bg-black/35 p-2 text-white hover:bg-black/50"
                    data-banner-prev
                    aria-label="Banner trước"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.79 4.23a.75.75 0 0 1-.02 1.06L8.81 9.25H16a.75.75 0 0 1 0 1.5H8.81l3.96 3.96a.75.75 0 1 1-1.06 1.06l-5.25-5.25a.75.75 0 0 1 0-1.06l5.25-5.25a.75.75 0 0 1 1.08.02Z" clip-rule="evenodd" />
                    </svg>
                </button>
                <button
                    type="button"
                    class="absolute top-1/2 right-5 z-20 -translate-y-1/2 rounded-full bg-black/35 p-2 text-white hover:bg-black/50"
                    data-banner-next
                    aria-label="Banner tiếp theo"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.21 4.23a.75.75 0 0 0 .02 1.06l3.96 3.96H4a.75.75 0 0 0 0 1.5h7.19l-3.96 3.96a.75.75 0 1 0 1.06 1.06l5.25-5.25a.75.75 0 0 0 0-1.06L8.29 4.21a.75.75 0 0 0-1.08.02Z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div class="absolute bottom-3 left-1/2 z-20 flex -translate-x-1/2 items-center gap-2">
                    @foreach($banners as $index => $banner)
                        <button
                            type="button"
                            class="h-1.5 w-5 rounded-full {{ $index === 0 ? 'bg-white' : 'bg-white/50' }}"
                            data-banner-dot
                            data-index="{{ $index }}"
                            aria-label="Chuyển slide {{ $index + 1 }}"
                        ></button>
                    @endforeach
                </div>
            </div>
        @else
            <div class="flex h-56 items-center justify-center bg-slate-900/90 px-4 text-center text-sm font-semibold text-white sm:h-72 lg:h-115">
                Chưa có banner hiển thị.
            </div>
        @endif
    </section>

    <section id="top" class="relative mb-10 overflow-hidden rounded-lg bg-slate-900 p-6 text-white shadow-xl lg:p-10">
        <div class="pointer-events-none absolute -right-16 -top-16 h-52 w-52 rounded-full bg-sky-400/25 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-20 -left-10 h-56 w-56 rounded-full bg-sky-400/20 blur-3xl"></div>
        <div class="relative grid gap-8 lg:grid-cols-[1.4fr_1fr] lg:items-end">
            <div>
                <p class="mb-3 inline-flex rounded-full border border-white/20 bg-white/10 px-3 py-2 text-xs font-semibold tracking-wide">Bộ sưu tập mới 2026</p>
                <h1 class="max-w-2xl text-3xl leading-tight font-extrabold lg:text-5xl">Nâng cấp phong cách mua sắm với ưu đãi tốt nhất trong tuần</h1>
                <p class="mt-4 max-w-2xl text-sm text-slate-200 lg:text-base">Lựa chọn sản phẩm mới, hàng nổi bật và deal giá tốt được cập nhật liên tục mỗi ngày.</p>
                <form action="{{ route('product.search') }}" method="GET" class="mt-6 flex flex-col gap-2 sm:flex-row">
                    <input type="text" name="keyword" class="w-full rounded-lg border border-white/20 bg-white/10 px-4 py-2.5 text-sm text-white placeholder:text-slate-300 focus:border-sky-300 focus:outline-none" placeholder="Tìm nhanh sản phẩm bạn cần...">
                    <button type="submit" aria-label="Tìm kiếm" class="inline-flex items-center justify-center cursor-pointer rounded-lg bg-sky-400 px-4 py-2.5 text-white/80 hover:bg-sky-500 hover:scale-105 transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 3a6 6 0 1 0 3.87 10.586l3.272 3.272a1 1 0 0 0 1.415-1.414l-3.273-3.273A6 6 0 0 0 9 3Zm-4 6a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div class="rounded-lg border-dashed bg-white/10 border p-4 text-center backdrop-blur select-none">
                    <p class="text-2xl font-extrabold">{{ $newProducts->count() }}+</p>
                    <p class="mt-1 text-xs text-slate-200">Sản phẩm mới</p>
                </div>
                <div class="rounded-lg border-dashed bg-white/10 border p-4 text-center backdrop-blur select-none">
                    <p class="text-2xl font-extrabold">{{ $popularProducts->count() }}+</p>
                    <p class="mt-1 text-xs text-slate-200">Nổi bật</p>
                </div>
                <div class="rounded-lg border-dashed bg-white/10 border p-4 text-center backdrop-blur select-none">
                    <p class="text-2xl font-extrabold">{{ $saleProducts->count() }}+</p>
                    <p class="mt-1 text-xs text-slate-200">Giá tốt</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-10 rounded-lg border border-slate-400 border-dashed bg-white p-5">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-900">Danh mục nổi bật</h2>
            <a href="{{ route('product.index') }}" class="text-sm font-semibold text-sky-700 hover:text-sky-600">Xem tất cả sản phẩm</a>
        </div>
        <div class="flex flex-wrap gap-2">
            @forelse($categories as $category)
                <a href="{{ route('product.category', ['slug' => $category->slug]) }}" class="rounded-full border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700">
                    {{ $category->catename }}
                </a>
            @empty
                <p class="text-sm text-slate-500">Chưa có danh mục đang hoạt động.</p>
            @endforelse
        </div>
    </section>

    {{-- <section class="mb-8 rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="mb-4 text-lg font-bold text-slate-900">Thương hiệu phổ biến</h2>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-8">
            @forelse($brands as $brand)
                <a href="{{ route('product.brand', ['slug' => $brand->slug]) }}" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 text-center text-sm font-semibold text-slate-700 transition hover:-translate-y-0.5 hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700">
                    {{ $brand->brandname }}
                </a>
            @empty
                <p class="col-span-full text-sm text-slate-500">Chưa có thương hiệu đang hoạt động.</p>
            @endforelse
        </div>
    </section> --}}

    <section id="new-products" class="mb-10">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-xl font-bold text-slate-900">Sản phẩm mới nhất</h3>
            <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-700">{{ $newProducts->count() }} sản phẩm</span>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($newProducts as $product)
                <x-client.product-card :product="$product" />
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500">Chưa có sản phẩm mới.</div>
            @endforelse
        </div>
    </section>

    <section class="mb-10">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-xl font-bold text-slate-900">Sản phẩm nổi bật</h3>
            <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">Được yêu thích</span>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($popularProducts as $product)
                <x-client.product-card :product="$product" />
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500">Chưa có sản phẩm nổi bật.</div>
            @endforelse
        </div>
    </section>

    <section class="mb-10">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-xl font-bold text-slate-900">Sản phẩm giá tốt</h3>
            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Tiết kiệm tối ưu</span>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($saleProducts as $product)
                <x-client.product-card :product="$product" />
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500">Chưa có sản phẩm giá tốt.</div>
            @endforelse
        </div>
    </section>

    <section class="mb-10 rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-xl font-bold text-slate-900">Bài viết nổi bật</h3>
            <a href="{{ route('post.index') }}" class="rounded-lg bg-sky-100 px-3 py-2 text-sm font-semibold text-sky-700 hover:bg-sky-200">
                Xem tất cả bài viết
            </a>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($featuredPosts as $post)
                <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <img src="{{ $post->image ?: asset('img/default.png') }}" alt="{{ $post->title }}" class="h-40 w-full object-cover">
                    <div class="p-3 flex flex-col">
                        <h4 class="line-clamp-2 text-sm font-bold text-slate-900">{{ $post->title }}</h4>
                        <p class="mt-1 text-xs text-slate-500">{{ $post->created_at?->format('d/m/Y') }}</p>
                        <a href="{{ route('post.show', $post->slug) }}" class="mt-2 inline-block text-sm font-semibold text-sky-700 hover:text-sky-600 text-end">Đọc bài viết</a>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-500">
                    Chưa có bài viết nổi bật.
                </div>
            @endforelse
        </div>
    </section>

    <section class="mb-10 overflow-hidden rounded-lg bg-linear-to-br from-sky-600 via-sky-500 to-cyan-500 p-6 text-white lg:p-8">
        <div class="grid gap-6 lg:grid-cols-[1.1fr_1fr] lg:items-center">
            <div class="flex items-start gap-4">
                {{-- <img src="{{ asset('img/default.png') }}" alt="Newsletter" class="size-20 rounded-2xl border border-white/30 object-cover shadow-md"> --}}
            <a href="{{ route('home') }}" class="inline-flex size-30 items-center justify-center rounded-xl border-2 border-sky-500 bg-white text-2xl shadow-lg font-extrabold text-sky-500">M</a>
                <div>
                    <h3 class="text-2xl font-extrabold leading-tight">Đăng ký nhận email ưu đãi</h3>
                    <p class="mt-2 text-sm text-white/90">Nhận thông báo khuyến mãi mới, mã giảm giá và sản phẩm nổi bật mỗi tuần.</p>
                    {{-- <div class="mt-3 inline-flex rounded-full border border-white bg-white/60 text-slate-900 px-3 py-1 text-xs font-semibold">
                        Miễn phí
                    </div> --}}
                </div>
            </div>
            <form action="{{ route('contact.submit') }}" method="POST" class="rounded-xl border-2 border-dashed border-white/30 bg-white/20 p-3 backdrop-blur-sm sm:flex sm:items-center sm:gap-2 sm:p-4">
                @csrf
                <input type="hidden" name="fullname" value="Đăng ký nhận tin">
                <input type="hidden" name="message" value="Khách hàng đăng ký nhận email ưu đãi từ trang chủ.">
                <input
                    type="email"
                    name="email"
                    required
                    class="mb-3 h-11 w-full rounded-lg border border-white/10 bg-white/90 px-4 text-sm text-white placeholder:text-slate-500 focus:border-white focus:outline-none sm:mb-0"
                    placeholder="Nhập email của bạn..."
                >
                <button type="submit" class="h-11 w-full rounded-lg bg-slate-700 px-4 text-sm font-bold text-white hover:bg-slate-900 cursor-pointer sm:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                    </svg>

                </button>
            </form>
        </div>
    </section>
@endsection
