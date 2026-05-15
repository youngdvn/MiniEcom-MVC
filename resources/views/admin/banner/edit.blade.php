@extends('admin.layouts.app')
@section('title', 'Banner - Sửa')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3 fw-bold">Sửa banner</h4>
    <x-admin.panel-error/>

    <div class="admin-section">
        <form action="{{ route('admin.banner.update', ['id' => $model->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-lg-6">
                    <label class="form-label">Tiêu đề</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $model->title) }}">
                </div>
                <div class="col-lg-6">
                    <label class="form-label">Phụ đề</label>
                    <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $model->subtitle) }}">
                </div>
                <div class="col-lg-8">
                    <label class="form-label">Ảnh (URL)</label>
                    <input type="text" name="image" class="form-control" value="{{ old('image', $model->image) }}">
                </div>
                <div class="col-lg-4">
                    <label class="form-label">Upload ảnh mới</label>
                    <input type="file" name="image_file" class="form-control" accept="image/*">
                    <small class="text-muted">Nếu upload file mới sẽ ghi đè ảnh hiện tại.</small>
                </div>
                <div class="col-lg-4">
                    <label class="form-label">Thứ tự hiển thị</label>
                    <input type="number" min="0" name="sort_order" class="form-control" value="{{ old('sort_order', $model->sort_order) }}">
                </div>
                <div class="col-lg-8">
                    <label class="form-label">Link chuyển hướng</label>
                    <input type="text" name="link" class="form-control" value="{{ old('link', $model->link) }}">
                </div>
                <div class="col-lg-4">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="1" {{ (string) old('status', $model->status) === '1' ? 'selected' : '' }}>Hiện</option>
                        <option value="0" {{ (string) old('status', $model->status) === '0' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Xem trước ảnh</label>
                    <div>
                        <img src="{{ old('image', $model->image) }}" alt="{{ $model->title }}" style="max-width:360px;width:100%;height:180px;object-fit:cover;border-radius:12px;border:1px solid #cbd5e1;">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('admin.banner.index') }}" class="btn btn-outline-secondary">Quay lại</a>
                <button type="submit" class="btn btn-primary">Lưu banner</button>
                <button type="reset" class="btn btn-light border">Làm lại</button>
            </div>
        </form>
    </div>
</div>
@endsection
