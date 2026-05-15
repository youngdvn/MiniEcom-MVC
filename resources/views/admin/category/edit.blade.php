@extends('admin.layouts.app')
@section('title', 'Loại Sản Phẩm')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3 fw-bold">Sửa loại sản phẩm</h4>
    <x-admin.panel-error/>

    <div class="admin-section">
        <form action="{{ route('admin.cate.update', $model->cateid) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-lg-6">
                    <label class="form-label">Tên loại</label>
                    <input type="text" name="catename" class="form-control" value="{{old('catename', $model->catename)}}">
                    @error('catename')<div class="text-danger small mt-1">{{$message}}</div>@enderror
                </div>
                <input type="hidden" name="slug" value="{{ old('slug', $model->slug) }}">
                <div class="col-12">
                    <label class="form-label">Mô tả</label>
                    <input type="text" name="description" class="form-control" value="{{ old('description', $model->description) }}">
                </div>
                <div class="col-lg-6">
                    <label class="form-label">Hình ảnh</label>
                    <input type="file" name="thumbnail" class="form-control img-input" id="thumbnail">
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
                <a href="{{ route('admin.cate.index') }}" class="btn btn-outline-secondary">Quay lại</a>
                <button type="submit" class="btn btn-primary">Lưu danh mục</button>
                <button type="reset" class="btn btn-light border">Làm lại</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{asset('admin/preview-images.js')}}"></script>
@endsection
