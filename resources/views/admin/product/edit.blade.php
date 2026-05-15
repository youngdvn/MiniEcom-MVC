@extends('admin.layouts.app')
@section('title', 'Sản phẩm - Sửa')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 fw-bold">Sửa sản phẩm</h4>
    </div>

    <x-admin.panel-error/>

    <div class="admin-section">
        <form action="{{ route('admin.product.update', $model->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-lg-8">
                    <label class="form-label">Tên sản phẩm</label>
                    <input type="text" name="proname" class="form-control" value="{{ old('proname', $model->proname) }}">
                    @error('proname') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <input type="hidden" name="slug" value="{{ old('slug', $model->slug) }}">

                <div class="col-lg-3">
                    <label class="form-label">Giá gốc</label>
                    <input type="number" name="price" class="form-control" value="{{ old('price', $model->price) }}">
                    @error('price') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="col-lg-3">
                    <label class="form-label">Giá khuyến mãi</label>
                    <input type="number" name="sale_price" class="form-control" value="{{ old('sale_price', $model->sale_price) }}">
                    <small class="text-muted">Để trống nếu không khuyến mãi</small>
                    @error('sale_price') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="col-lg-3">
                    <label class="form-label">Tồn kho</label>
                    <input type="number" min="0" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $model->stock_quantity) }}">
                    @error('stock_quantity') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="col-lg-3">
                    <label class="form-label">Danh mục</label>
                    <select name="cateid" class="form-select">
                        @foreach($categories as $cate)
                            <option value="{{ $cate->cateid }}" {{ (string) old('cateid', $model->cateid) === (string) $cate->cateid ? 'selected' : '' }}>
                                {{ $cate->catename }}
                            </option>
                        @endforeach
                    </select>
                    @error('cateid') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="col-lg-3">
                    <label class="form-label">Thương hiệu</label>
                    <select name="brandid" class="form-select">
                        <option value="">-- Không chọn --</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ (string) old('brandid', $model->brandid) === (string) $brand->id ? 'selected' : '' }}>
                                {{ $brand->brandname }}
                            </option>
                        @endforeach
                    </select>
                    @error('brandid') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-lg-6">
                    <label class="form-label">Ảnh đại diện</label>
                    <input type="file" name="thumbnail" class="form-control img-input">
                    <div class="mt-2 d-flex align-items-center gap-2">
                        <img src="{{ asset('storage/products/' . $model->thumbnail) }}" alt="thumbnail" width="120" class="img-thumbnail">
                        <div class="img-preview"></div>
                    </div>
                    @error('thumbnail') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="col-lg-6">
                    <label class="form-label">Ảnh phụ hiện tại</label>
                    <div class="d-flex flex-wrap gap-2">
                        @forelse($model->images as $img)
                            <img src="{{ asset('storage/products/' . $img->image) }}" alt="product-image" width="100" class="img-thumbnail">
                        @empty
                            <span class="text-muted small">Chưa có ảnh phụ</span>
                        @endforelse
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Mô tả cơ bản</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $model->description) }}</textarea>
                    @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', $model->status) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Hiển thị sản phẩm</label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
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
                            @php
                                $variantRows = old('variants', $model->variants->map(fn($v) => [
                                    'id' => $v->id,
                                    'size' => $v->size,
                                    'price' => $v->price,
                                    'sale_price' => $v->sale_price,
                                    'stock_quantity' => $v->stock_quantity,
                                    'status' => (string) $v->status,
                                ])->toArray());
                                if (count($variantRows) < 5) {
                                    $variantRows = array_merge($variantRows, array_fill(0, 5 - count($variantRows), []));
                                }
                            @endphp
                            @foreach($variantRows as $i => $variant)
                                <tr>
                                    <td>
                                        <input type="hidden" name="variants[{{ $i }}][id]" value="{{ $variant['id'] ?? '' }}">
                                        <input type="text" name="variants[{{ $i }}][size]" class="form-control" value="{{ $variant['size'] ?? '' }}" placeholder="8GB/128GB - Đen">
                                    </td>
                                    <td><input type="number" name="variants[{{ $i }}][price]" class="form-control" value="{{ $variant['price'] ?? '' }}" placeholder="Nếu khác giá gốc"></td>
                                    <td><input type="number" name="variants[{{ $i }}][sale_price]" class="form-control" value="{{ $variant['sale_price'] ?? '' }}" placeholder="Nếu có"></td>
                                    <td><input type="number" min="0" name="variants[{{ $i }}][stock_quantity]" class="form-control" value="{{ $variant['stock_quantity'] ?? 0 }}"></td>
                                    <td>
                                        <select name="variants[{{ $i }}][status]" class="form-select">
                                            <option value="1" {{ (string) ($variant['status'] ?? '1') === '1' ? 'selected' : '' }}>Bật</option>
                                            <option value="0" {{ (string) ($variant['status'] ?? '1') === '0' ? 'selected' : '' }}>Tắt</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <small class="text-muted">Điền phiên bản cần bán, xóa phiên bản bằng cách để trống dòng đó rồi lưu.</small>
            </div>

            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary">Quay lại</a>
                <button type="submit" class="btn btn-primary">Lưu sản phẩm</button>
                <button type="reset" class="btn btn-light border">Làm lại</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('admin/preview-images.js')}}"></script>
@endsection
