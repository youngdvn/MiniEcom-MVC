@extends('client.layout.app')
@section('title', 'Tài khoản của tôi')

@section('content')
    <x-client.partials.breadcrumb :items="[
        ['label' => 'Trang chủ', 'url' => route('home')],
        ['label' => 'Tài khoản']
    ]" />

    <section class="mb-5 rounded-lg border border-slate-400 border-dashed bg-white p-5 ">
        <h1 class="text-2xl font-extrabold text-slate-900">Tài khoản của tôi</h1>
        <p class="mt-1 text-sm text-slate-600">Cập nhật thông tin cá nhân và mật khẩu.</p>
    </section>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-lg border-slate-200 border-be-sky-500 border-2 bg-white p-4 ">
            <h2 class="mb-3 text-lg font-bold">Thông tin cá nhân</h2>
            <form method="POST" action="{{ route('account.profile.update') }}" class="space-y-3">
                @csrf
                @method('PUT')
                <div>
                    <label class="mb-1 block text-sm font-medium">Họ và tên</label>
                    <input type="text" name="fullname" value="{{ old('fullname', $user->fullname) }}" class="h-10 w-full rounded-md border border-slate-300 px-3 focus:border-sky-500 focus:outline-none">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Username</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" class="h-10 w-full rounded-md border border-slate-300 px-3 focus:border-sky-500 focus:outline-none">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="h-10 w-full rounded-md border border-slate-300 px-3 focus:border-sky-500 focus:outline-none">
                </div>
                <button class="rounded-md bg-sky-500 ml-auto px-4 py-2 font-semibold text-white hover:bg-sky-600 w-full">Cập nhật thông tin</button>
            </form>
        </div>

        <div class="rounded-lg border-slate-200 border-be-slate-900 border-2 bg-white p-4">
            <h2 class="mb-3 text-lg font-bold">Đổi mật khẩu</h2>
            <form method="POST" action="{{ route('account.password.update') }}" class="space-y-3">
                @csrf
                @method('PUT')
                <div>
                    <label class="mb-1 block text-sm font-medium">Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" placeholder="******" class="h-10 w-full rounded-md border border-slate-300 px-3 focus:border-sky-500 focus:outline-none">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Mật khẩu mới</label>
                    <input type="password" name="password" placeholder="******" class="h-10 w-full rounded-md border border-slate-300 px-3 focus:border-sky-500 focus:outline-none">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Xác nhận mật khẩu mới</label>
                    <input type="password" name="password_confirmation" placeholder="******" class="h-10 w-full rounded-md border border-slate-300 px-3 focus:border-sky-500 focus:outline-none">
                </div>
                <button class="rounded-md w-full bg-slate-900 px-4 py-2 font-semibold text-white hover:bg-slate-800">Đổi mật khẩu</button>
            </form>
        </div>
    </div>
@endsection
