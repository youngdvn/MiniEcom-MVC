@extends('admin.layouts.app')
@section('title', 'Đổi mật khẩu')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3 fw-bold">Đổi mật khẩu người dùng</h4>
    <x-admin.panel-error/>

    <div class="admin-section" style="max-width:760px;">
        <form action="{{ route('admin.user.password.update', $model->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Người dùng</label>
                <input type="text" class="form-control" value="{{ $model->username }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Mật khẩu mới</label>
                <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu mới">
                @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu mới">
            </div>

            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary">Quay lại</a>
                <button type="submit" class="btn btn-primary">Lưu mật khẩu</button>
                <button type="reset" class="btn btn-light border">Làm lại</button>
            </div>
        </form>
    </div>
</div>
@endsection
