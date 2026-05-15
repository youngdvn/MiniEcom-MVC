@extends('admin.layouts.app')
@section('title', 'Người dùng')

@section('content')
<h3>Thêm người dùng</h3>
<x-admin.panel-error/>

<form action="{{ route('admin.user.store') }}" method="POST" class="w-75">
    @csrf

    <label>Tên Người dùng</label>
    <input type="text" name="username" class="form-control" value="{{ old('username') }}">
    @error('username')
    <div class="text-danger">{{ $message }}</div>
    @enderror

    <label>Họ và tên</label>
    <input type="text" name="fullname" class="form-control" value="{{ old('fullname') }}">
    @error('fullname')
    <div class="text-danger">{{ $message }}</div>
    @enderror

    <label>Email</label>
    <input type="text" name="email" class="form-control" value="{{ old('email') }}">
    @error('email')
    <div class="text-danger">{{ $message }}</div>
    @enderror

    <label>Vai trò</label>
    <select name="role" class="form-control">
        <option value="0" {{ old('role', '0') == '0' ? 'selected' : '' }}>User</option>
        <option value="1" {{ old('role') == '1' ? 'selected' : '' }}>Admin</option>
    </select>
    @error('role')
    <div class="text-danger">{{ $message }}</div>
    @enderror


    <div class="d-flex gap-2 mt-4">
        <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary">Quay lại</a>
        <button type="submit" class="btn btn-primary">Lưu người dùng</button>
        <button type="reset" class="btn btn-light border">Làm lại</button>
    </div>

</form>
@endsection
