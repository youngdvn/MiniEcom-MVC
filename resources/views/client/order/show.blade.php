@extends('client.layout.app')
@section('title', 'Chi tiết đơn hàng')

@section('content')
    @php use App\Support\OrderStatus; @endphp

    <x-client.partials.breadcrumb :items="[
        ['label' => 'Trang chủ', 'url' => route('home')],
        ['label' => 'Đơn hàng của tôi', 'url' => route('order.index')],
        ['label' => $order->order_code]
    ]" />

    <section class="mb-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Chi tiết đơn hàng {{ $order->order_code }}</h1>
                <p class="mt-1 text-sm text-slate-600">Ngày đặt: {{ $order->created_at?->format('d/m/Y H:i') }}</p>
            </div>
            <a href="{{ route('order.index') }}" class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Quay lại danh sách</a>
        </div>
    </section>

    <div class="grid gap-5 lg:grid-cols-[1fr_340px]">
        <div class="overflow-x-auto rounded-xl border bg-white shadow-sm">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-slate-100 text-left">
                        <th class="px-3 py-2">Sản phẩm</th>
                        <th class="px-3 py-2">Phiên bản</th>
                        <th class="px-3 py-2">SL</th>
                        <th class="px-3 py-2">Giá</th>
                        <th class="px-3 py-2">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr class="border-t">
                            <td class="px-3 py-2">{{ $item->product?->proname ?? 'Sản phẩm đã xóa' }}</td>
                            <td class="px-3 py-2">{{ $item->variant_size ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $item->quantity }}</td>
                            <td class="px-3 py-2">{{ number_format($item->price) }}</td>
                            <td class="px-3 py-2">{{ number_format($item->price * $item->quantity) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <aside class="rounded-xl border bg-white p-4 shadow-sm">
            <h2 class="mb-3 text-base font-bold text-slate-900">Thông tin đơn hàng</h2>
            <div class="space-y-2 text-sm text-slate-700">
                <p><span class="font-semibold">Khách hàng:</span> {{ $order->customer?->fullname }}</p>
                <p><span class="font-semibold">Điện thoại:</span> {{ $order->customer?->phone }}</p>
                <p><span class="font-semibold">Địa chỉ:</span> {{ $order->customer?->address }}</p>
                <p>
                    <span class="font-semibold">Trạng thái:</span>
                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                        {{ $order->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : '' }}
                        {{ $order->status === 'processing' ? 'bg-amber-100 text-amber-700' : '' }}
                        {{ $order->status === 'pending' ? 'bg-slate-200 text-slate-700' : '' }}
                        {{ $order->status === 'cancelled' ? 'bg-rose-100 text-rose-700' : '' }}">
                        {{ OrderStatus::orderLabel($order->status) }}
                    </span>
                </p>
                <p>
                    <span class="font-semibold">Thanh toán:</span>
                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                        {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-700' : '' }}
                        {{ $order->payment_status === 'unpaid' ? 'bg-slate-200 text-slate-700' : '' }}
                        {{ $order->payment_status === 'refunded' ? 'bg-sky-100 text-sky-700' : '' }}">
                        {{ OrderStatus::paymentLabel($order->payment_status) }}
                    </span>
                </p>
                @if($order->payment_status === OrderStatus::PAYMENT_UNPAID && in_array($order->payment_method, ['bank_transfer', 'ewallet'], true))
                    <form action="{{ route('order.pay.demo', $order->id) }}" method="POST" class="pt-1">
                        @csrf
                        <button type="submit" class="rounded-lg bg-emerald-500 px-3 py-2 text-xs font-bold text-white hover:bg-emerald-600">
                            Thanh toán ngay (mô phỏng)
                        </button>
                    </form>
                @endif
                <p><span class="font-semibold">Tổng tiền:</span> {{ number_format($order->total_amount) }}</p>
                @if($order->note)
                    <p><span class="font-semibold">Ghi chú:</span> {{ $order->note }}</p>
                @endif
            </div>
        </aside>
    </div>
@endsection
