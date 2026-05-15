@extends('admin.layouts.app')

@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div>
            <h4 class="mb-0 fw-bold">Quản lý sản phẩm</h4>
            <small class="text-secondary">Theo dõi tồn kho và vận hành danh mục sản phẩm</small>
        </div>
        <a href="{{ route('admin.product.create') }}" class="btn btn-primary">
            + Thêm sản phẩm
        </a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Tổng sản phẩm</small>
                    <h4 class="mt-1 mb-0 fw-bold">{{ number_format($stats['total']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Đang hiển thị</small>
                    <h4 class="mt-1 mb-0 fw-bold text-success">{{ number_format($stats['active']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Sắp hết hàng (1-5)</small>
                    <h4 class="mt-1 mb-0 fw-bold text-warning">{{ number_format($stats['low_stock']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Hết hàng</small>
                    <h4 class="mt-1 mb-0 fw-bold text-danger">{{ number_format($stats['out_of_stock']) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-section mb-3">
        <form method="GET" action="{{ route('admin.product.index') }}">
            <div class="row g-2">
                <div class="col-12 col-md-4 col-lg-3">
                    <input
                        type="text"
                        name="keyword"
                        class="form-control"
                        placeholder="Tìm tên sản phẩm..."
                        value="{{ $keyword }}"
                    >
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <select name="cateid" class="form-select">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->cateid }}" {{ (string) $cateid === (string) $category->cateid ? 'selected' : '' }}>
                                {{ $category->catename }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <select name="brandid" class="form-select">
                        <option value="">Tất cả thương hiệu</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ (string) $brandid === (string) $brand->id ? 'selected' : '' }}>
                                {{ $brand->brandname }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <select name="status" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="1" {{ $status === '1' ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ $status === '0' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <select name="sort" class="form-select">
                        <option value="">Mới nhất</option>
                        <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                        <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                        <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                        <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                        <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                    </select>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <input
                        type="number"
                        name="min"
                        min="0"
                        class="form-control"
                        placeholder="Giá từ"
                        value="{{ $min }}"
                    >
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <input
                        type="number"
                        name="max"
                        min="0"
                        class="form-control"
                        placeholder="Giá đến"
                        value="{{ $max }}"
                    >
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <select name="limit" class="form-select">
                        <option value="5" {{ (int) $limit === 5 ? 'selected' : '' }}>5 / trang</option>
                        <option value="10" {{ (int) $limit === 10 ? 'selected' : '' }}>10 / trang</option>
                        <option value="20" {{ (int) $limit === 20 ? 'selected' : '' }}>20 / trang</option>
                        <option value="50" {{ (int) $limit === 50 ? 'selected' : '' }}>50 / trang</option>
                    </select>
                </div>
                <div class="col-12 col-md-8 col-lg-4 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Lọc dữ liệu</button>
                    <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary">Đặt lại</a>
                </div>
            </div>
        </form>
    </div>

    <div class="admin-table-wrap">
        <div class="table-responsive">
            <table class="table admin-table align-middle mb-0">
                <thead class="table-dark text-center">
                    <tr>
                        <th width="65">ID</th>
                        <th width="105" class="text-center">Ảnh</th>
                        <th class="text-start">Tên sản phẩm</th>
                        <th width="170">Giá</th>
                        <th width="130">Tồn kho</th>
                        <th width="120">Trạng thái</th>
                        <th width="170">Danh mục</th>
                        <th width="170">Thương hiệu</th>
                        <th width="190">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $pro)
                        <tr>
                            <td class="text-center">{{ $pro->id }}</td>
                            <td class="text-center">
                                <img src="{{ asset('storage/products/' . ($pro->thumbnail ?? 'default.png')) }}" alt="thumb" width="72" class="img-thumbnail">
                            </td>
                            <td class="text-start">
                                <div class="fw-semibold">{{ $pro->proname }}</div>
                                <small class="text-muted">Slug: {{ $pro->slug }}</small>
                            </td>
                            <td class="text-center">
                                @if(!empty($pro->sale_price) && $pro->sale_price > 0 && $pro->sale_price < $pro->price)
                                    <div><strong class="text-danger">{{ number_format($pro->sale_price) }} VND</strong></div>
                                    <small class="text-muted text-decoration-line-through">{{ number_format($pro->price) }} VND</small>
                                @else
                                    <strong>{{ number_format($pro->price) }} VND</strong>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(($pro->stock_quantity ?? 0) <= 0)
                                    <span class="badge bg-danger ">Hết hàng</span>
                                @elseif(($pro->stock_quantity ?? 0) <= 5)
                                    <span class="badge bg-warning text-dark">Sắp hết ({{ $pro->stock_quantity }})</span>
                                @else
                                    <span >{{ $pro->stock_quantity }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge px-2 py-2 {{ $pro->status ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $pro->status ? 'Hiển thị' : 'Ẩn' }}
                                </span>
                            </td>
                            <td class="text-center">{{ $pro->category->catename ?? 'Không có' }}</td>
                            <td class="text-center">{{ $pro->brand->brandname ?? 'Không có' }}</td>
                            <td class="text-center">
                                <a href="{{ route('product.show', ['slug' => $pro->slug]) }}" target="_blank" class="btn btn-outline-info btn-sm">
                                    Xem
                                </a>
                                <a href="{{ route('admin.product.edit', ['id' => $pro->id]) }}" class="btn btn-outline-primary btn-sm">
                                    Sửa
                                </a>
                                <form action="{{ route('admin.product.del', ['id' => $pro->id]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">
                                        Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Không có sản phẩm nào phù hợp bộ lọc.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <small class="text-muted">
            Hiển thị {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} / {{ $products->total() }} sản phẩm
        </small>
        <div>
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

