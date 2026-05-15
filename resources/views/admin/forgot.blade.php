<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu quản trị</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100">
    <div class="mx-auto flex min-h-screen w-full max-w-7xl items-center px-4 py-8">
        <div class="grid w-full overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl lg:grid-cols-2">
            <aside class="relative overflow-hidden bg-linear-to-br from-slate-900 via-slate-800 to-cyan-800 p-8 text-white lg:p-10">
                <div class="absolute -top-16 -left-16 h-52 w-52 rounded-full bg-sky-300/20 blur-3xl"></div>
                <div class="absolute -bottom-20 -right-14 h-60 w-60 rounded-full bg-cyan-300/15 blur-3xl"></div>
                <div class="relative">
                    <p class="inline-flex rounded-full border border-white/25 bg-white/10 px-3 py-1 text-xs font-semibold">Admin CMS</p>
                    <h1 class="mt-4 text-3xl leading-tight font-extrabold lg:text-4xl">Khôi phục quyền truy cập quản trị</h1>
                    <p class="mt-3 text-sm text-white/85">Nhập email quản trị để hệ thống gửi mật khẩu mới. Sau khi đăng nhập, hãy đổi lại mật khẩu ngay.</p>
                </div>
            </aside>

            <section class="p-6 sm:p-8 lg:p-10">
                <h2 class="text-2xl font-extrabold text-slate-900">Quên mật khẩu</h2>
                <p class="mt-1 text-sm text-slate-500">Hệ thống sẽ gửi mật khẩu mới qua email của bạn.</p>

                <div class="mt-4">
                    <x-admin.panel-error />
                </div>

                <form action="{{ route('admin.forgot.submit') }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Email</label>
                        <input type="text" name="email" value="{{ old('email') }}" placeholder="Nhập email quản trị" class="h-11 w-full rounded-lg border border-slate-300 px-3 text-slate-800 focus:border-sky-500 focus:outline-none">
                        @error('email')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="h-11 w-full rounded-lg bg-sky-600 font-bold text-white hover:bg-sky-500">Gửi mật khẩu mới</button>
                    <a href="{{ route('admin.login') }}" class="inline-flex h-11 w-full items-center justify-center rounded-lg border border-slate-300 font-semibold text-slate-700 hover:bg-slate-50">
                        Quay lại đăng nhập
                    </a>
                </form>
            </section>
        </div>
    </div>
</body>
</html>
