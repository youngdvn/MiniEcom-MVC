@extends('admin.layouts.app')

@section('title', 'Quản lý thương hiệu')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div>
            <h4 class="mb-0 fw-bold">Quản lý thương hiệu</h4>
            <small class="text-secondary">Quản trị thương hiệu sản phẩm công nghệ</small>
        </div>
        <a href="{{ route('admin.brand.create') }}" class="btn btn-primary">+ Thêm thương hiệu</a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <small class="text-muted">Tổng thương hiệu</small>
                <h4 class="mt-1 mb-0 fw-bold">{{ number_format($stats['total']) }}</h4>
            </div></div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <small class="text-muted">Đang hiển thị</small>
                <h4 class="mt-1 mb-0 fw-bold text-success">{{ number_format($stats['active']) }}</h4>
            </div></div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <small class="text-muted">Đang ẩn</small>
                <h4 class="mt-1 mb-0 fw-bold text-secondary">{{ number_format($stats['hidden']) }}</h4>
            </div></div>
        </div>
    </div>

    <div class="admin-section mb-3">
        <form method="GET" action="{{ route('admin.brand.index') }}">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="keyword" class="form-control" placeholder="Tìm tên hoặc slug..." value="{{ $keyword }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="1" {{ $status === '1' ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ $status === '0' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="limit" class="form-select">
                        <option value="5" {{ (int) $limit === 5 ? 'selected' : '' }}>5 / trang</option>
                        <option value="10" {{ (int) $limit === 10 ? 'selected' : '' }}>10 / trang</option>
                        <option value="20" {{ (int) $limit === 20 ? 'selected' : '' }}>20 / trang</option>
                        <option value="50" {{ (int) $limit === 50 ? 'selected' : '' }}>50 / trang</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Lọc</button>
                    <a href="{{ route('admin.brand.index') }}" class="btn btn-outline-secondary">Đặt lại</a>
                </div>
            </div>
        </form>
    </div>

    <div class="admin-table-wrap">
        <div class="table-responsive">
            <table class="table admin-table align-middle mb-0">
                <thead class="table-dark text-center">
                    <tr>
                        <th width="70">ID</th>
                        <th width="110">Ảnh</th>
                        <th>Tên thương hiệu</th>
                        <th>Slug</th>
                        <th width="110">Trạng thái</th>
                        <th>Mô tả</th>
                        <th width="190">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($list as $brand)
                        <tr>
                            <td class="text-center">{{ $brand->id }}</td>
                            <td class="text-center">
                                <img src="{{ asset('storage/brands/' . ($brand->thumbnail ?? 'default.png')) }}" width="72" class="img-thumbnail" alt="thumb">
                            </td>
                            <td class="fw-semibold">{{ $brand->brandname }}</td>
                            <td>{{ $brand->slug }}</td>
                            <td class="text-center">
                                <span class="badge {{ $brand->status ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $brand->status ? 'Hiển thị' : 'Ẩn' }}
                                </span>
                            </td>
                            <td>{{ $brand->description ?: '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('product.brand', ['slug' => $brand->slug]) }}" target="_blank" class="btn btn-outline-info btn-sm">Xem</a>
                                <a href="{{ route('admin.brand.edit', ['id' => $brand->id]) }}" class="btn btn-outline-primary btn-sm">Sửa</a>
                                <form action="{{ route('admin.brand.del', ['id' => $brand->id]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">Không có dữ liệu thương hiệu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <small class="text-muted">Hiển thị {{ $list->firstItem() ?? 0 }} - {{ $list->lastItem() ?? 0 }} / {{ $list->total() }} thương hiệu</small>
        <div>{{ $list->links('pagination::bootstrap-5') }}</div>
    </div>
</div>
@endsection

