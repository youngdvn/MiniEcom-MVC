<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập khách hàng</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100">
    @if(session('message'))
        <div data-flash-message="{{ session('message') }}" data-flash-type="error" class="hidden"></div>
    @endif

    <div class="mx-auto flex min-h-screen w-full max-w-7xl items-center px-4 py-8">
        <div class="grid w-full overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl lg:grid-cols-2">
            <aside class="relative overflow-hidden bg-linear-to-br from-sky-700 via-sky-600 to-cyan-500 p-8 text-white lg:p-10">
                <div class="absolute -top-14 -right-14 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>
                <div class="absolute -bottom-20 -left-12 h-56 w-56 rounded-full bg-white/15 blur-3xl"></div>
                <div class="relative">
                    <p class="inline-flex rounded-full border border-white/35 bg-white/15 px-3 py-1 text-xs font-semibold">MiniEcom</p>
                    <h1 class="mt-4 text-3xl leading-tight font-extrabold lg:text-4xl">Mua sắm thông minh, đơn giản và nhanh chóng</h1>
                    <p class="mt-3 text-sm text-white/90">Đăng nhập để theo dõi đơn hàng, lưu sản phẩm yêu thích và nhận ưu đãi dành riêng cho bạn.</p>
                    <ul class="mt-6 space-y-2 text-sm text-white/95">
                        <li>- Theo dõi trạng thái đơn hàng theo thời gian thực</li>
                        <li>- Lưu lịch sử mua hàng và địa chỉ nhận hàng</li>
                        <li>- Nhận thông báo khuyến mãi mỗi tuần</li>
                    </ul>
                    <a href="{{ route('home') }}" class="mt-8 inline-flex rounded-lg bg-white px-4 py-2 text-sm font-bold text-sky-700 hover:bg-slate-100">Về trang chủ</a>
                </div>
            </aside>

            <section class="p-6 sm:p-8 lg:p-10">
                <h2 class="text-2xl font-extrabold text-slate-900">Đăng nhập khách hàng</h2>
                <p class="mt-1 text-sm text-slate-500">Tiếp tục mua sắm và quản lý tài khoản của bạn.</p>

                <form action="{{ route('client.login.submit') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Tên đăng nhập hoặc email</label>
                        <input type="text" name="username" value="{{ old('username') }}" class="h-11 w-full rounded-lg border border-slate-300 px-3 text-slate-800 focus:border-sky-500 focus:outline-none" placeholder="Nhập tên đăng nhập hoặc email">
                        @error('username')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Mật khẩu</label>
                        <input type="password" name="password" class="h-11 w-full rounded-lg border border-slate-300 px-3 text-slate-800 focus:border-sky-500 focus:outline-none" placeholder="Nhập mật khẩu">
                        @error('password')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" name="remember" class="rounded border-slate-300">
                        Ghi nhớ đăng nhập
                    </label>
                    <button type="submit" class="h-11 w-full rounded-lg bg-sky-500 font-bold text-white hover:bg-sky-600">Đăng nhập</button>
                </form>

                <p class="mt-5 text-center text-sm text-slate-600">
                    Chưa có tài khoản?
                    <a href="{{ route('client.register') }}" class="font-semibold text-sky-700 hover:text-sky-600">Đăng ký ngay</a>
                </p>
            </section>
        </div>
    </div>
</body>
</html>
