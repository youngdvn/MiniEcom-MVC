@extends('client.layout.app')
@section('title', 'Đơn hàng của tôi')

@section('content')
    @php use App\Support\OrderStatus; @endphp

    <x-client.partials.breadcrumb :items="[
        ['label' => 'Trang chủ', 'url' => route('home')],
        ['label' => 'Đơn hàng của tôi']
    ]" />

    <section class="mb-5 rounded-lg border border-slate-400 border-dashed bg-white p-5">
        <h1 class="text-2xl font-extrabold text-slate-900">Đơn hàng của tôi</h1>
        <p class="mt-1 text-sm text-slate-600">Theo dõi danh sách đơn hàng đã đặt.</p>
    </section>

    <div class="overflow-x-auto rounded-lg border border-dashed bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-slate-200 text-left border-b border-slate-400">
                    <th class="px-3 py-2 border-r">Mã đơn</th>
                    <th class="px-3 py-2 border-r">Khách hàng</th>
                    <th class="px-3 py-2 border-r">Trạng thái</th>
                    <th class="px-3 py-2 border-r">Thanh toán</th>
                    <th class="px-3 py-2 border-r">Tổng tiền</th>
                    <th class="px-3 py-2 border-r">Ngày đặt</th>
                    <th class="px-3 py-2">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="border-t">
                        <td class="px-3 py-2 font-semibold text-slate-800">{{ $order->order_code }}</td>
                        <td class="px-3 py-2">{{ $order->customer?->fullname }}</td>
                        <td class="px-3 py-2">
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                {{ $order->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ $order->status === 'processing' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $order->status === 'pending' ? 'bg-slate-200 text-slate-700' : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-rose-100 text-rose-700' : '' }}">
                                {{ OrderStatus::orderLabel($order->status) }}
                            </span>
                        </td>
                        <td class="px-3 py-2">
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ $order->payment_status === 'unpaid' ? 'bg-slate-200 text-slate-700' : '' }}
                                {{ $order->payment_status === 'refunded' ? 'bg-sky-100 text-sky-700' : '' }}">
                                {{ OrderStatus::paymentLabel($order->payment_status) }}
                            </span>
                        </td>
                        <td class="px-3 py-2">{{ number_format($order->total_amount) }}</td>
                        <td class="px-3 py-2">{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                        <td class="px-3 py-2">
                            <a href="{{ route('order.show', $order->id) }}" class="rounded bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700 hover:bg-sky-200">
                                Chi tiết
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-3 py-5 text-center text-slate-500">Bạn chưa có đơn hàng nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-client.pagination :paginator="$orders" />
@endsection
