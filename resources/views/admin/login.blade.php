<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập quản trị</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100">
    <div class="mx-auto flex min-h-screen w-full max-w-7xl items-center px-4 py-8">
        <div class="grid w-full overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl lg:grid-cols-2">
            <aside class="relative overflow-hidden bg-linear-to-br from-slate-900 via-slate-800 to-sky-900 p-8 text-white lg:p-10">
                <div class="absolute -top-16 -right-16 h-52 w-52 rounded-full bg-sky-400/20 blur-3xl"></div>
                <div class="absolute -bottom-20 -left-14 h-60 w-60 rounded-full bg-cyan-300/15 blur-3xl"></div>
                <div class="relative">
                    <p class="inline-flex rounded-full border border-white/25 bg-white/10 px-3 py-1 text-xs font-semibold">Admin CMS</p>
                    <h1 class="mt-4 text-3xl leading-tight font-extrabold lg:text-4xl">Quản trị hệ thống bán hàng tập trung</h1>
                    <p class="mt-3 text-sm text-white/85">Đăng nhập để quản lý sản phẩm, đơn hàng, coupon và theo dõi vận hành hệ thống.</p>
                    <ul class="mt-6 space-y-2 text-sm text-white/90">
                        <li>- Quản lý toàn bộ dữ liệu cửa hàng</li>
                        <li>- Theo dõi trạng thái đơn và thanh toán</li>
                        <li>- Cập nhật nội dung và chương trình khuyến mãi</li>
                    </ul>
                </div>
            </aside>

            <section class="p-6 sm:p-8 lg:p-10">
                <h2 class="text-2xl font-extrabold text-slate-900">Đăng nhập quản trị</h2>
                <p class="mt-1 text-sm text-slate-500">Chỉ tài khoản quản trị mới được phép truy cập.</p>

                <div class="mt-4">
                    <x-admin.panel-error />
                </div>

                <form action="{{ route('admin.login') }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Tên đăng nhập / Email</label>
                        <input type="text" name="username" value="{{ old('username') }}" placeholder="Nhập tên đăng nhập hoặc email" class="h-11 w-full rounded-lg border border-slate-300 px-3 text-slate-800 focus:border-sky-500 focus:outline-none">
                        @error('username')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Mật khẩu</label>
                        <input type="password" name="password" placeholder="Nhập mật khẩu" class="h-11 w-full rounded-lg border border-slate-300 px-3 text-slate-800 focus:border-sky-500 focus:outline-none">
                        @error('password')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input class="rounded border-slate-300" type="checkbox" name="remember">
                        Ghi nhớ đăng nhập
                    </label>

                    <button type="submit" class="h-11 w-full rounded-lg bg-sky-600 font-bold text-white hover:bg-sky-500">Đăng nhập</button>

                    <a href="{{ route('admin.forgot') }}" class="block text-center text-sm font-semibold text-sky-700 hover:text-sky-600">
                        Quên mật khẩu?
                    </a>
                </form>
            </section>
        </div>
    </div>
</body>
</html>
