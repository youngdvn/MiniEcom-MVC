@extends('admin.layouts.app')
@section('title', 'Bài viết - Sửa')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3 fw-bold">Sửa bài viết</h4>
    <x-admin.panel-error/>

    <div class="admin-section">
        <form action="{{ route('admin.post.update', $model->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-lg-8">
                    <label class="form-label">Tiêu đề</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $model->title) }}">
                    @error('title')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <input type="hidden" name="slug" value="{{ old('slug', $model->slug) }}">
                <div class="col-lg-4">
                    <label class="form-label">Tác giả</label>
                    <select name="userid" class="form-select">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ (string) old('userid', $model->userid) === (string) $user->id ? 'selected' : '' }}>{{ $user->username }}</option>
                        @endforeach
                    </select>
                    @error('userid')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-lg-4">
                    <label class="form-label">Ảnh (URL)</label>
                    <input type="text" name="image" class="form-control" value="{{ old('image', $model->image) }}">
                    @error('image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-lg-4">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="1" {{ old('status', $model->status) == '1' ? 'selected' : '' }}>Hiện</option>
                        <option value="0" {{ old('status', $model->status) == '0' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Nội dung</label>
                    <textarea name="content" class="form-control" rows="6">{{ old('content', $model->content) }}</textarea>
                    @error('content')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('admin.post.index') }}" class="btn btn-outline-secondary">Quay lại</a>
                <button type="submit" class="btn btn-primary">Lưu bài viết</button>
                <button type="reset" class="btn btn-light border">Làm lại</button>
            </div>
        </form>
    </div>
</div>
@endsection
