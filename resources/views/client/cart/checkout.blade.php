@extends('client.layout.app')
@section('title', 'Thanh toán')

@section('content')
    @php
        $cart = Session::get('cart', []);
    @endphp
    <x-client.partials.breadcrumb :items="[
        ['label' => 'Trang chủ', 'url' => route('home')],
        ['label' => 'Giỏ hàng', 'url' => route('cart.show')],
        ['label' => 'Thanh toán']
    ]" />

    <section class="py-3">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div>
                <h3 class="mb-3 text-xl font-bold">Thông tin đặt hàng</h3>
                @if($errors->any())
                    <div class="mb-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <form action="{{ route('cart.save') }}" method="POST" class="rounded-xl border bg-white p-4 shadow-sm">
                    @csrf
                    <div class="mb-3">
                        <label class="mb-1 block text-sm font-medium">Họ tên</label>
                        <input type="text" class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-sky-500 focus:outline-none" name="fullname" value="{{ old('fullname') }}" placeholder="Nhập họ và tên người nhận">
                    </div>
                    <div class="mb-3">
                        <label class="mb-1 block text-sm font-medium">Email</label>
                        <input type="text" class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-sky-500 focus:outline-none" name="email" value="{{ old('email') }}" placeholder="Nhập email (không bắt buộc)">
                    </div>
                    <div class="mb-3">
                        <label class="mb-1 block text-sm font-medium">Số điện thoại</label>
                        <input type="text" class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-sky-500 focus:outline-none" name="phone" value="{{ old('phone') }}" placeholder="Nhập số điện thoại liên hệ">
                    </div>
                    <div class="mb-3">
                        <label class="mb-1 block text-sm font-medium">Địa chỉ</label>
                        <input type="text" class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-sky-500 focus:outline-none" name="address" value="{{ old('address') }}" placeholder="Nhập địa chỉ nhận hàng chi tiết">
                    </div>
                    <div class="mb-3">
                        <label class="mb-1 block text-sm font-medium">Ghi chú</label>
                        <input type="text" class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-sky-500 focus:outline-none" name="note" value="{{ old('note') }}" placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi giao">
                    </div>
                    <div class="mb-3">
                        <label class="mb-1 block text-sm font-medium">Phương thức thanh toán</label>
                        <select name="payment_method" class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-sky-500 focus:outline-none">
                            <option value="cod" {{ old('payment_method', 'cod') === 'cod' ? 'selected' : '' }}>Thanh toán khi nhận hàng (COD)</option>
                            <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Chuyển khoản ngân hàng</option>
                            <option value="ewallet" {{ old('payment_method') === 'ewallet' ? 'selected' : '' }}>Ví điện tử</option>
                        </select>
                    </div>
                    <button type="submit" class="rounded-md bg-sky-600 px-4 py-2 font-semibold text-white hover:bg-sky-500">Đặt hàng</button>
                </form>
            </div>
            <div>
                <h3 class="mb-3 text-xl font-bold">Giỏ hàng</h3>
                <div class="overflow-x-auto rounded-xl border bg-white shadow-sm">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-slate-100 text-left">
                            <th class="px-3 py-2">STT</th>
                            <th class="px-3 py-2">Sản phẩm</th>
                            <th class="px-3 py-2">SL</th>
                            <th class="px-3 py-2">Giá</th>
                            <th class="px-3 py-2">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $item)
                            <tr class="border-t">
                                <td class="px-3 py-2">{{ $loop->iteration }}</td>
                                <td class="px-3 py-2">
                                    {{ $item['proname'] }}
                                    @if(!empty($item['variant_size']))
                                        <div class="text-xs text-slate-500">Phiên bản: {{ $item['variant_size'] }}</div>
                                    @endif
                                </td>
                                <td class="px-3 py-2">{{ $item['quantity'] }}</td>
                                <td class="px-3 py-2">{{ number_format($item['price']) }}</td>
                                <td class="px-3 py-2">{{ number_format($item['price'] * $item['quantity']) }}</td>
                            </tr>
                        @endforeach
                        <tr class="border-t bg-slate-50">
                            <td colspan="4" class="px-3 py-2 text-right"><strong>Tạm tính</strong></td>
                            <td class="px-3 py-2"><strong>{{ number_format($summary['subtotal'] ?? 0) }}</strong></td>
                        </tr>
                        <tr class="border-t bg-slate-50">
                            <td colspan="4" class="px-3 py-2 text-right"><strong>Giảm giá</strong></td>
                            <td class="px-3 py-2">
                                <strong>- {{ number_format($summary['discount'] ?? 0) }}</strong>
                                @if(!empty($coupon['code']))
                                    <span class="ml-2 rounded bg-sky-100 px-2 py-0.5 text-xs font-semibold text-sky-700">{{ $coupon['code'] }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr class="border-t bg-slate-50">
                            <td colspan="4" class="px-3 py-2 text-right"><strong>Tổng thanh toán</strong></td>
                            <td class="px-3 py-2"><strong>{{ number_format($summary['total'] ?? 0) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </section>
@endsection
