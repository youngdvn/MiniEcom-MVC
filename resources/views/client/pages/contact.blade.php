@extends('client.layout.app')
@section('title', 'Liên hệ')

@section('content')
    <x-client.partials.breadcrumb :items="[
        ['label' => 'Trang chủ', 'url' => route('home')],
        ['label' => 'Liên hệ']
    ]" />

    <section class="mb-6 rounded-lg bg-linear-to-r from-sky-600 to-cyan-500 p-6 text-white">
        <h1 class="text-3xl font-extrabold">Liên hệ</h1>
        <p class="mt-2 text-sm text-white/90">Nếu bạn cần hỗ trợ đơn hàng hoặc tư vấn sản phẩm, hãy liên hệ với chúng tôi.</p>
    </section>

    <div class="grid gap-6 md:grid-cols-[0.75fr_1.25fr]">
        <div class="rounded-lg border border-slate-400 border-dashed bg-white p-5 shadow-sm">
            <h2 class="text-lg text-center font-bold text-slate-900">Thông tin liên hệ</h2>
            <ul class="mt-4 space-y-2 text-sm text-slate-700">
                <li>Email: support@miniecom.vn</li>
                <li>Hotline: 1900 1234</li>
                <li>Địa chỉ: TP. Hồ Chí Minh</li>
                <li>Giờ làm việc: 8:00 - 22:00 (Thứ 2 - Chủ nhật)</li>
            </ul>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-5 pt-10">
            <h2 class="text-lg font-bold text-slate-900 text-center">Gửi tin nhắn nhanh</h2>
            <form action="{{ route('contact.submit') }}" method="POST" class="mt-4 space-y-3">
                @csrf
                @if ($errors->any())
                    <div class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700">{{ $errors->first() }}</div>
                @endif
                <input type="text" name="fullname" value="{{ old('fullname') }}" class="h-11 w-full rounded-lg border border-slate-300 px-3 focus:border-sky-500 focus:outline-none" placeholder="Họ và tên" required>
                <input type="email" name="email" value="{{ old('email') }}" class="h-11 w-full rounded-lg border border-slate-300 px-3 focus:border-sky-500 focus:outline-none" placeholder="Email" required>
                <input type="text" name="phone" value="{{ old('phone') }}" class="h-11 w-full rounded-lg border border-slate-300 px-3 focus:border-sky-500 focus:outline-none" placeholder="Số điện thoại">
                <textarea name="message" class="min-h-28 w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-sky-500 focus:outline-none" placeholder="Nội dung" required>{{ old('message') }}</textarea>
                <button type="submit" class="h-11 rounded-lg bg-linear-to-r from-sky-600 to-cyan-500 px-5 font-semibold text-white hover:bg-sky-700 w-full">Gửi liên hệ</button>
            </form>
        </div>
    </div>
@endsection
