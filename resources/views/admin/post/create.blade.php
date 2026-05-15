@extends('admin.layouts.app')
@section('title', 'Bài viết - Thêm')
@section('content')

<h3>Thêm bài viết</h3>
<x-admin.panel-error/>
<form action="{{ route('admin.post.store') }}" method="post" class="m-auto w-auto border p-3">
    @csrf

    <div class="mb-3">
        <label for="title">Tiêu đề</label>
        <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}">
        @error('title')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <input type="hidden" name="slug" id="slug" value="{{ old('slug') }}">

    <div class="mb-3">
        <label for="userid">Tác giả</label>
        <select name="userid" id="userid" class="form-control">
            <option value="" disabled {{ old('userid') ? '' : 'selected' }}>-- Chọn tác giả --</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ (string) old('userid') === (string) $user->id ? 'selected' : '' }}>{{ $user->username }}</option>
            @endforeach
        </select>
        @error('userid')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="image">Ảnh (URL)</label>
        <input type="text" name="image" id="image" class="form-control" value="{{ old('image') }}">
        @error('image')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="content">Nội dung</label>
        <textarea name="content" id="content" class="form-control" rows="4">{{ old('content') }}</textarea>
        @error('content')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="status">Trạng thái</label>
        <select name="status" id="status" class="form-control">
            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Hiện</option>
            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Ẩn</option>
        </select>
        @error('status')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex gap-2 mt-4">
        <a href="{{ route('admin.post.index') }}" class="btn btn-outline-secondary">Quay lại</a>
        <button type="submit" class="btn btn-primary">Lưu bài viết</button>
        <button type="reset" class="btn btn-light border">Làm lại</button>
    </div>
</form>

@endsection
