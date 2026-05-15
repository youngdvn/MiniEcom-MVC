@extends('admin.layouts.app')
@section('title', 'Người dùng')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3 fw-bold">Sửa người dùng</h4>
    <x-admin.panel-error/>

    <div class="admin-section">
        <form action="{{ route('admin.user.update', $model->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-lg-6">
                    <label class="form-label">Tên người dùng</label>
                    <input type="text" name="username" class="form-control" value="{{ old('username', $model->username) }}">
                    @error('username')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-lg-6">
                    <label class="form-label">Họ và tên</label>
                    <input type="text" name="fullname" class="form-control" value="{{ old('fullname', $model->fullname) }}">
                    @error('fullname')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-lg-6">
                    <label class="form-label">Email</label>
                    <input type="text" name="email" class="form-control" value="{{ old('email', $model->email) }}">
                    @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-lg-6">
                    <label class="form-label">Vai trò</label>
                    <select name="role" class="form-select">
                        <option value="0" {{ old('role', $model->role) == '0' ? 'selected' : '' }}>User</option>
                        <option value="1" {{ old('role', $model->role) == '1' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary">Quay lại</a>
                <button type="submit" class="btn btn-primary">Lưu người dùng</button>
                <button type="reset" class="btn btn-light border">Làm lại</button>
            </div>
        </form>
    </div>
</div>
@endsection
