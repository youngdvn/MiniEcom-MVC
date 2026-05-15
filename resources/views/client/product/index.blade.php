@extends('client.layout.app')
@section('title', 'Sản phẩm')

@section('content')
    <x-client.partials.breadcrumb :items="[
        ['label' => 'Trang chủ', 'url' => route('home')],
        ['label' => 'Sản phẩm']
    ]" />

    <section class="mb-5 rounded-lg border border-dashed border-slate-400 bg-white p-5">
        <h1 class="text-2xl font-extrabold text-slate-900">Tất cả sản phẩm</h1>
        <p class="mt-1 text-sm text-slate-600">Lọc và tìm nhanh sản phẩm theo nhu cầu của bạn.</p>
    </section>

    <div class="grid gap-5 lg:grid-cols-[280px_1fr]">
        <aside class="h-fit rounded-lg bg-white p-4">
            <h2 class="mb-3 text-base font-bold text-slate-900">Bộ lọc sản phẩm</h2>
            <form method="GET" action="{{ route('product.index') }}" class="space-y-3">
                <input type="text" name="keyword" value="{{ request('keyword') }}" class="h-10 w-full rounded-lg border border-slate-300 px-3 text-sm focus:border-sky-500 focus:outline-none" placeholder="Tên sản phẩm...">

                <div class="relative">
                <select name="category" class="h-11 w-full appearance-none rounded-lg border border-slate-300 px-3 pr-10 text-sm font-medium text-slate-700 transition duration-150  focus:bg-white focus:outline-none">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $item)
                        <option value="{{ $item->slug }}" @selected(request('category') === $item->slug)>{{ $item->catename }}</option>
                    @endforeach
                </select>
                <span class="pointer-events-none absolute inset-y-0 right-3 inline-flex items-center text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                    </svg>
                </span>
                </div>

                <div class="relative">
                <select name="brand" class="h-11 w-full appearance-none rounded-lg border border-slate-300 px-3 pr-10 text-sm font-medium text-slate-700 transition duration-150 focus:bg-white focus:outline-none ">
                    <option value="">Tất cả thương hiệu</option>
                    @foreach($brands as $item)
                        <option value="{{ $item->slug }}" @selected(request('brand') === $item->slug)>{{ $item->brandname }}</option>
                    @endforeach
                </select>
                <span class="pointer-events-none absolute inset-y-0 right-3 inline-flex items-center text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                    </svg>
                </span>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <input type="number" min="0" name="min_price" value="{{ request('min_price') }}" class="h-10 w-full rounded-lg border border-slate-300 px-3 text-sm focus:outline-none" placeholder="Giá từ">
                    <input type="number" min="0" name="max_price" value="{{ request('max_price') }}" class="h-10 w-full rounded-lg border border-slate-300 px-3 text-sm focus:outline-none" placeholder="Giá đến">
                </div>

                <div class="relative">
                <select name="sort" class="h-11 w-full appearance-none rounded-lg border border-slate-300 px-3 pr-10 text-sm font-medium text-slate-700 transition duration-150 focus:bg-white focus:outline-none active:scale-[0.99]">
                    <option value="latest" @selected($sort === 'latest')>Mới nhất</option>
                    <option value="price_asc" @selected($sort === 'price_asc')>Giá tăng dần</option>
                    <option value="price_desc" @selected($sort === 'price_desc')>Giá giảm dần</option>
                    <option value="name_asc" @selected($sort === 'name_asc')>Tên A-Z</option>
                    <option value="name_desc" @selected($sort === 'name_desc')>Tên Z-A</option>
                </select>
                <span class="pointer-events-none absolute inset-y-0 right-3 inline-flex items-center text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                    </svg>
                </span>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="h-10 flex-1 rounded-lg bg-sky-50 text-sm font-semibold text-sky-600 border hover:text-white transition duration-200 hover:border-white border-sky-300 hover:bg-sky-600">Lọc</button>
                    <a href="{{ route('product.index') }}" class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-300 px-3 bg-slate-100 text-sm font-semibold text-slate-700 hover:bg-slate-300">Reset</a>
                </div>
            </form>
        </aside>

        <div>
            <div class="mb-3 flex items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600">
                <span>Hiển thị {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} / {{ $products->total() }} sản phẩm</span>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @forelse($products as $product)
                    <x-client.product-card :product="$product" />
                @empty
                    <div class="col-span-full rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-500">
                        Không tìm thấy sản phẩm phù hợp.
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                <x-client.pagination :paginator="$products" />
            </div>
        </div>
    </div>
@endsection
