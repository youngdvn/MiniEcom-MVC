@extends('client.layout.app')
@section('title', 'Giỏ hàng')

@section('content')
    @php
        $cart = Session::get('cart', []);
    @endphp
    <x-client.partials.breadcrumb :items="[
        ['label' => 'Trang chủ', 'url' => route('home')],
        ['label' => 'Giỏ hàng']
    ]" />

    <section class="py-4">
        <h3 class="mb-3 text-xl font-bold">Thông tin giỏ hàng</h3>
        <div class="overflow-x-auto rounded-lg border border-dashed bg-white ">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-slate-200 text-left">
                    <th class="px-3 py-2 border-r">STT</th>
                    <th class="px-3 py-2 border-r">Sản phẩm</th>
                    <th class="px-3 py-2 border-r">Số lượng</th>
                    <th class="px-3 py-2 border-r">Giá</th>
                    <th class="px-3 py-2 border-r">Thành tiền</th>
                    <th class="px-3 py-2">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cart as $item)
                    <tr class="border-t">
                        <td class="p-4">#{{ $loop->iteration }}</td>
                        <td class="p-4">
                            {{ $item['proname'] }}
                            @if(!empty($item['variant_size']))
                                <div class="text-xs text-slate-500">Phiên bản: {{ $item['variant_size'] }}</div>
                            @endif
                        </td>
                        <td class="p-4">
                            <form action="{{ route('cart.update', ['key' => $item['cart_key'] ?? $item['productid']]) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <button type="submit" name="action" value="decrease" class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-slate-300 text-base font-bold text-slate-700 hover:bg-slate-100">-</button>
                                <input type="number" name="quantity" min="1" max="100" value="{{ $item['quantity'] }}" class="h-9 w-16 rounded-md border border-slate-300 px-2 text-center text-sm focus:border-sky-500 focus:outline-none">
                                <button type="submit" name="action" value="increase" class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-slate-300 text-base font-bold text-slate-700 hover:bg-slate-100">+</button>
                                <button type="submit" class="h-9 rounded-md border border-sky-300 px-3 text-xs font-semibold text-sky-700 hover:bg-sky-50">
                                    Cập nhật
                                </button>
                            </form>
                        </td>
                        <td class="p-4">{{ number_format($item['price']) }}</td>
                        <td class="p-4">{{ number_format($item['price'] * $item['quantity']) }}</td>
                        <td class="p-4 text-center">
                            <form action="{{ route('cart.remove', ['key' => $item['cart_key'] ?? $item['productid']]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded bg-red-50 border border-red-300 px-3 py-1 text-xs font-semibold text-red-500 hover:bg-red-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
</svg>

                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-3 py-4 text-center">Giỏ hàng trống.</td>
                    </tr>
                @endforelse
                <tr class=" bg-slate-50">
                    <td colspan="4" class="px-3 py-2 text-right"><strong>Tạm tính</strong></td>
                    <td class="px-3 py-2 "><p>{{ number_format($summary['subtotal'] ?? 0) }}</p></td>
                    <td></td>
                </tr>
                <tr class="border-t bg-slate-50">
                    <td colspan="4" class="px-3 py-2 text-right"><strong>Giảm giá</strong></td>
                    <td class="px-3 py-2"><p>- {{ number_format($summary['discount'] ?? 0) }}</p></td>
                    <td class="px-3 py-2">
                    </td>
                </tr>
                <tr class="border-t bg-slate-50">
                    <td colspan="4" class="px-3 py-2 text-right"><strong>Tổng thanh toán</strong></td>
                    <td class="px-3 py-2 text-lg"><strong>{{ number_format($summary['total'] ?? 0) }}</strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        </div>

        @if(!empty($cart))
            <div class="mt-4 rounded-xl border bg-white p-4 shadow-sm">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div class="w-full lg:max-w-4xl">
                        <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="text" name="code" class="h-10 w-full rounded-md border border-slate-300 px-3 focus:border-sky-500 focus:outline-none" placeholder="Nhập mã giảm giá">
                            <button type="submit" class="h-10 rounded-md bg-sky-600 px-4 text-sm font-semibold text-white hover:bg-sky-500">Áp dụng</button>
                        </form>

                        @if(!empty($availableCoupons) && $availableCoupons->isNotEmpty())
                            <div class="mt-3">
                                <p class="mb-2 text-xs font-semibold text-slate-500">Mã khả dụng:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($availableCoupons as $item)
                                        <form action="{{ route('cart.coupon.apply') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="code" value="{{ $item->code }}">
                                            <button type="submit" class="rounded-lg border border-sky-200 bg-sky-50 px-3 py-1.5 text-xs font-semibold text-sky-700 hover:bg-sky-100">
                                                {{ $item->code }}
                                                @if($item->type === 'percent')
                                                    - Giảm {{ $item->value }}%
                                                @else
                                                    - Giảm {{ number_format($item->value) }}đ
                                                @endif
                                            </button>
                                        </form>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($coupon['code']))
                            <form action="{{ route('cart.coupon.remove') }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit" class="h-10 rounded-md border border-rose-300 px-3 text-sm font-semibold text-rose-600 hover:bg-rose-50">
                                    Gỡ mã {{ $coupon['code'] }}
                                </button>
                            </form>
                        @endif
                    </div>

                    <a href="{{ route('cart.checkout') }}" class="inline-flex h-10 shrink-0 items-center justify-center rounded-md bg-sky-500 px-5 text-sm font-semibold text-white hover:bg-sky-600">
                        Đặt hàng
                    </a>
                </div>
            </div>
        @endif
    </section>
@endsection
