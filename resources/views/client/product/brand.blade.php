@extends('client.layout.app')
@section('title', 'Thương hiệu: ' . $brand->brandname)

@section('content')
    <x-client.partials.breadcrumb :items="[
        ['label' => 'Trang chủ', 'url' => route('home')],
        ['label' => 'Sản phẩm', 'url' => route('product.index')],
        ['label' => $brand->brandname]
    ]" />

    <h3 class="mb-3 text-xl font-bold">Thương hiệu: {{ $brand->brandname }}</h3>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($products as $product)
            <x-client.product-card :product="$product" />
        @empty
            <p>Không có sản phẩm.</p>
        @endforelse
    </div>
    <div class="mt-3">
        <x-client.pagination :paginator="$products" />
    </div>
@endsection
