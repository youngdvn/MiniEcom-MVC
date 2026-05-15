@extends('admin.layouts.app')
@section('title', 'Banner - Thêm')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3 fw-bold">Thêm banner</h4>
    <x-admin.panel-error/>

    <div class="admin-section">
        <form action="{{ route('admin.banner.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-lg-6">
                    <label class="form-label">Tiêu đề</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                </div>
                <div class="col-lg-6">
                    <label class="form-label">Phụ đề</label>
                    <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle') }}">
                </div>
                <div class="col-lg-8">
                    <label class="form-label">Ảnh (URL)</label>
                    <input type="text" name="image" class="form-control" placeholder="https://..." value="{{ old('image') }}">
                </div>
                <div class="col-lg-4">
                    <label class="form-label">Upload ảnh</label>
                    <input type="file" name="image_file" class="form-control" accept="image/*">
                    <small class="text-muted">Có thể nhập URL hoặc upload file.</small>
                </div>
                <div class="col-lg-4">
                    <label class="form-label">Thứ tự hiển thị</label>
                    <input type="number" min="0" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}">
                </div>
                <div class="col-lg-8">
                    <label class="form-label">Link chuyển hướng</label>
                    <input type="text" name="link" class="form-control" placeholder="https://... hoặc /san-pham" value="{{ old('link') }}">
                </div>
                <div class="col-lg-4">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="1" {{ old('status', '1') === '1' ? 'selected' : '' }}>Hiện</option>
                        <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Ẩn</option>
                    </select>
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
