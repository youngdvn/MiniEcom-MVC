@extends('admin.layouts.app')
@section('title', 'Loại Thương Hiệu')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3 fw-bold">Sửa thương hiệu</h4>
    <x-admin.panel-error/>

    <div class="admin-section">
        <form action="{{ route('admin.brand.update', $model->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-lg-6">
                    <label class="form-label">Tên thương hiệu</label>
                    <input type="text" name="brandname" class="form-control" value="{{ old('brandname', $model->brandname) }}">
                    @error('brandname')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <input type="hidden" name="slug" value="{{ old('slug', $model->slug) }}">
                <div class="col-12">
                    <label class="form-label">Mô tả</label>
                    <input type="text" name="description" class="form-control" value="{{ old('description', $model->description) }}">
                </div>
                <div class="col-lg-6">
                    <label class="form-label">Hình ảnh</label>
                    <input type="file" name="thumbnail" class="form-control img-input" id="thumbnail">
                    <div class="mt-2">
                        <img src="{{ asset('storage/brands/' . ($model->thumbnail ?? 'default.png')) }}" alt="thumbnail" width="120" class="img-thumbnail">
                    </div>
                    <div class="img-preview mt-2"></div>
                    @error('thumbnail')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', $model->status) ? 'checked' : '' }}>
                        <label for="status" class="form-check-label">Hiển thị</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('admin.brand.index') }}" class="btn btn-outline-secondary">Quay lại</a>
                <button type="submit" class="btn btn-primary">Lưu thương hiệu</button>
                <button type="reset" class="btn btn-light border">Làm lại</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{asset('admin/preview-images.js')}}"></script>
@endsection
