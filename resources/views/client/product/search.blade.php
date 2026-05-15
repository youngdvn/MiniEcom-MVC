@extends('client.layout.app')
@section('title', 'Tìm kiếm sản phẩm')

@section('content')
    <x-client.partials.breadcrumb :items="[
        ['label' => 'Trang chủ', 'url' => route('home')],
        ['label' => 'Tìm kiếm']
    ]" />

    <h3 class="mb-3 text-xl font-bold">Kết quả tìm kiếm: "{{ $keyword }}"</h3>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($products as $product)
            <x-client.product-card :product="$product" />
        @empty
            <p>Không tìm thấy sản phẩm phù hợp.</p>
        @endforelse
    </div>
    <div class="mt-3">
        <x-client.pagination :paginator="$products" />
    </div>
@endsection
