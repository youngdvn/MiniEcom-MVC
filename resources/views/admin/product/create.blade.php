@extends('admin.layouts.app')
@section('title', 'Sản phẩm - Thêm')
@section('content')

<h3>Thêm sản phẩm</h3>
<x-admin.panel-error/>
<form action="{{ route('admin.product.store') }}" method="post" class="m-auto w-auto border p-3" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="proname">Tên sản phẩm</label>
        <input type="text" name="proname" id="proname" class="form-control" value="{{ old('proname') }}">
        @error('proname')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <input type="hidden" name="slug" id="slug" value="{{ old('slug') }}">

    <div class="row">
        <div class="col-lg-4 mb-3">
            <label for="price">Giá</label>
            <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}">
            @error('price')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-lg-4 mb-3">
            <label for="sale_price">Giá khuyến mãi</label>
            <input type="number" name="sale_price" id="sale_price" class="form-control" value="{{ old('sale_price') }}">
            <small class="text-muted">Để trống nếu không áp dụng khuyến mãi</small>
            @error('sale_price')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-lg-4 mb-3">
            <label for="stock_quantity">Tồn kho</label>
            <input type="number" min="0" name="stock_quantity" id="stock_quantity" class="form-control" value="{{ old('stock_quantity', 0) }}">
            @error('stock_quantity')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Dropdown loại sản phẩm, thương hiệu --}}
    <div class="row">
        <div class="col-6 mb-3">
            <label>Danh mục</label>
            <select name="cateid" class="form-control">
                <option value="" disabled {{ old('cateid') ? '' : 'selected' }}>-- Chọn danh mục --</option>
                @foreach($categories as $cate)
                    <option value="{{ $cate->cateid }}" {{ (string) old('cateid') === (string) $cate->cateid ? 'selected' : '' }}>
                        {{ $cate->catename }}
                    </option>
                @endforeach
            </select>
            @error('cateid')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-6 mb-3">
            <label>Thương hiệu</label>
            <select name="brandid" class="form-control">
                <option value="">-- Chọn thương hiệu --</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ (string) old('brandid') === (string) $brand->id ? 'selected' : '' }}>
                        {{ $brand->brandname }}
                    </option>
                @endforeach
            </select>
            @error('brandid')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    {{-- **** hiển thị checkbox Ẩn/hiện sản phẩm --}}
<div class="mb-3">
    <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
    <label class="form-check-label" for="status">Hiển thị</label>
</div>

{{-- **** Ảnh thumbnail --}}
<div class="mb-3 img-group">
    <label for="thumbnail">Hình ảnh đại diện</label>
    <input type="file" name="thumbnail" id="thumbnail" class="form-control img-input">
    
    {{-- ** hiển thị ảnh preview --}}
    <div class="img-preview"></div>

    {{-- hiển thị lỗi cho field thumbnail --}}
    @error('thumbnail')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

{{-- **** Ảnh liên quan --}}
<div class="mb-3 img-group">
    <label for="image">Hình ảnh</label>
    <input type="file" name="images[]" id="image" multiple class="form-control img-input">
    
    {{-- ** hiển thị ảnh preview --}}
    <div class="img-preview"></div>

    {{-- hiển thị lỗi --}}
    @error('images.*')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label fw-bold">Biến thể phiên bản công nghệ</label>
    <div class="table-responsive rounded border">
        <table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Phiên bản</th>
                    <th>Giá</th>
                    <th>Giá KM</th>
                    <th>Tồn kho</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @for($i = 0; $i < 5; $i++)
                    <tr>
                        <td><input type="text" name="variants[{{ $i }}][size]" class="form-control" value="{{ old('variants.'.$i.'.size') }}" placeholder="8GB/128GB - Đen"></td>
                        <td><input type="number" name="variants[{{ $i }}][price]" class="form-control" value="{{ old('variants.'.$i.'.price') }}" placeholder="Nếu khác giá gốc"></td>
                        <td><input type="number" name="variants[{{ $i }}][sale_price]" class="form-control" value="{{ old('variants.'.$i.'.sale_price') }}" placeholder="Nếu có"></td>
                        <td><input type="number" min="0" name="variants[{{ $i }}][stock_quantity]" class="form-control" value="{{ old('variants.'.$i.'.stock_quantity', 0) }}"></td>
                        <td>
                            <select name="variants[{{ $i }}][status]" class="form-select">
                                <option value="1" {{ old('variants.'.$i.'.status', '1') === '1' ? 'selected' : '' }}>Bật</option>
                                <option value="0" {{ old('variants.'.$i.'.status') === '0' ? 'selected' : '' }}>Tắt</option>
                            </select>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
    <small class="text-muted">Điền phiên bản cần bán, để trống dòng không dùng. Tồn kho tổng sẽ tự cộng từ các phiên bản.</small>
</div>

    <div class="mb-3">
        <label for="des">Mô tả cơ bản</label>
        <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
        @error('description')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex gap-2 mt-4">
        <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary">Quay lại</a>
        <button type="submit" class="btn btn-primary">Lưu sản phẩm</button>
        <button type="reset" class="btn btn-light border">Làm lại</button>
    </div>
</form>

@endsection
@section('scripts')
<script src="{{asset('admin/preview-images.js')}}"></script>
@endsection
