@extends('client.layout.app')
@section('title', 'Danh sách yêu thích')

@section('content')
    <x-client.partials.breadcrumb :items="[
        ['label' => 'Trang chủ', 'url' => route('home')],
        ['label' => 'Yêu thích']
    ]" />

    <section class="mb-5 rounded-lg border-dashed border border-slate-400 bg-white p-5">
        <h1 class="text-2xl font-extrabold text-slate-900">Danh sách yêu thích</h1>
        <p class="mt-1 text-sm text-slate-600">Các sản phẩm bạn đã lưu để xem lại nhanh hơn.</p>
    </section>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($wishlists as $item)
            @if($item->product)
                <div class="relative">
                    <x-client.product-card :product="$item->product" :showWishlistIcon="false" />
                    <form action="{{ route('wishlist.remove', $item->product->id) }}" method="POST" class="absolute top-3 right-3 z-30">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/95 text-rose-600 shadow hover:bg-rose-50" title="Bỏ yêu thích">
                            ✕
                        </button>
                    </form>
                </div>
            @endif
        @empty
            <div class="col-span-full rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-500">
                Bạn chưa có sản phẩm yêu thích nào.
            </div>
        @endforelse
    </div>

    <x-client.pagination :paginator="$wishlists" />
@endsection
