@extends('admin.layouts.app')
@section('title', 'Loại Thương Hiệu')

@section('content')
<h3>Thêm loại sản phẩm</h3>
<x-admin.panel-error/>

<form action="{{ route('admin.brand.store') }}" method="POST" class="w-75" enctype="multipart/form-data">
    @csrf

    <label>Tên Thương hiệu</label>
    <input type="text" name="brandname" class="form-control" value="{{ old('brandname') }}">
    @error('brandname')
    <div class="text-danger">{{ $message }}</div>
    @enderror

    <input type="hidden" name="slug" value="{{ old('slug') }}">

    <label>Mô tả</label>
    <input type="text" name="description" class="form-control" value="{{ old('description') }}">

    <div class="mb-3 img-group">
        <label for="thumbnail">Hình ảnh</label>
        <input type="file" name="thumbnail" class="form-control img-input" id="thumbnail">
        <div class="img-preview mt-2"></div>
        @error('thumbnail')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3 form-check">
        <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
        <label for="status" class="form-check-label">Hiển thị</label>
    </div>

    <div class="d-flex gap-2 mt-4">
        <a href="{{ route('admin.brand.index') }}" class="btn btn-outline-secondary">Quay lại</a>
        <button type="submit" class="btn btn-primary">Lưu thương hiệu</button>
        <button type="reset" class="btn btn-light border">Làm lại</button>
    </div>

</form>
@endsection
@section('scripts')
<script src="{{asset('admin/preview-images.js')}}"></script>
@endsection
