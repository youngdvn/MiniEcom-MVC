@extends('admin.layouts.app')
@section('title', 'Mã giảm giá')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h4 class="mb-0 fw-bold">Quản lý mã giảm giá</h4>
            <small class="text-secondary">Theo dõi hiệu lực và mức sử dụng coupon</small>
        </div>
        <a href="{{ route('admin.coupon.create') }}" class="btn btn-primary">+ Thêm mã</a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-sm-3">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <small class="text-muted">Tổng mã</small>
                <h4 class="mt-1 mb-0 fw-bold">{{ number_format($stats['total']) }}</h4>
            </div></div>
        </div>
        <div class="col-sm-3">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <small class="text-muted">Đang hoạt động</small>
                <h4 class="mt-1 mb-0 fw-bold text-success">{{ number_format($stats['active']) }}</h4>
            </div></div>
        </div>
        <div class="col-sm-3">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <small class="text-muted">Đã hết hạn</small>
                <h4 class="mt-1 mb-0 fw-bold text-secondary">{{ number_format($stats['expired']) }}</h4>
            </div></div>
        </div>
        <div class="col-sm-3">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <small class="text-muted">Hết lượt dùng</small>
                <h4 class="mt-1 mb-0 fw-bold text-warning">{{ number_format($stats['used_up']) }}</h4>
            </div></div>
        </div>
    </div>

    <div class="admin-section mb-3">
        <form method="GET" action="{{ route('admin.coupon.index') }}">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="keyword" class="form-control" placeholder="Tìm mã coupon..." value="{{ $keyword }}">
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">Tất cả loại</option>
                        <option value="percent" {{ $type === 'percent' ? 'selected' : '' }}>Phần trăm</option>
                        <option value="fixed" {{ $type === 'fixed' ? 'selected' : '' }}>Tiền cố định</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="1" {{ $status === '1' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ $status === '0' ? 'selected' : '' }}>Tắt</option>
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
                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Lọc</button>
                    <a href="{{ route('admin.coupon.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="admin-table-wrap">
        <div class="table-responsive">
            <table class="table admin-table align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Mã</th>
                        <th>Loại</th>
                        <th>Giá trị</th>
                        <th>Điều kiện</th>
                        <th>Lượt dùng</th>
                        <th>Hiệu lực</th>
                        <th>Trạng thái</th>
                        <th width="160">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($list as $coupon)
                        <tr>
                            <td class="fw-bold">{{ $coupon->code }}</td>
                            <td>{{ $coupon->type === 'percent' ? 'Phần trăm' : 'Tiền cố định' }}</td>
                            <td>
                                @if($coupon->type === 'percent')
                                    {{ $coupon->value }}%
                                    @if($coupon->max_discount)
                                        <div class="small text-muted">Tối đa {{ number_format($coupon->max_discount) }}đ</div>
                                    @endif
                                @else
                                    {{ number_format($coupon->value) }}đ
                                @endif
                            </td>
                            <td>Đơn tối thiểu {{ number_format($coupon->min_order) }}đ</td>
                            <td>{{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}</td>
                            <td class="small">
                                {{ $coupon->starts_at?->format('d/m/Y H:i') ?? 'Ngay' }} - {{ $coupon->ends_at?->format('d/m/Y H:i') ?? 'Không giới hạn' }}
                            </td>
                            <td>
                                <span class="badge {{ $coupon->status ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $coupon->status ? 'Hoạt động' : 'Tắt' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.coupon.edit', $coupon->id) }}" class="btn btn-outline-primary btn-sm">Sửa</a>
                                <form action="{{ route('admin.coupon.del', $coupon->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-4">Chưa có mã giảm giá</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <small class="text-muted">Hiển thị {{ $list->firstItem() ?? 0 }} - {{ $list->lastItem() ?? 0 }} / {{ $list->total() }} mã</small>
        <div>{{ $list->links('pagination::bootstrap-5') }}</div>
    </div>
</div>
@endsection

