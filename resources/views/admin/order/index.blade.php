@extends('admin.layouts.app')
@section('title', 'Quản lý đơn hàng')

@section('content')
@php use App\Support\OrderStatus; @endphp
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h4 class="mb-0 fw-bold">Quản lý đơn hàng</h4>
            <small class="text-secondary">Theo dõi trạng thái xử lý và thanh toán đơn hàng</small>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <small class="text-muted">Tổng đơn</small>
                <h5 class="mt-1 mb-0 fw-bold">{{ number_format($stats['total']) }}</h5>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <small class="text-muted">Chờ xử lý</small>
                <h5 class="mt-1 mb-0 fw-bold text-warning">{{ number_format($stats['pending']) }}</h5>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <small class="text-muted">Đang xử lý</small>
                <h5 class="mt-1 mb-0 fw-bold text-primary">{{ number_format($stats['processing']) }}</h5>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <small class="text-muted">Hoàn thành</small>
                <h5 class="mt-1 mb-0 fw-bold text-success">{{ number_format($stats['completed']) }}</h5>
            </div></div>
        </div>
        <div class="col-sm-12 col-xl-4">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <small class="text-muted">Doanh thu hôm nay</small>
                <h5 class="mt-1 mb-0 fw-bold">{{ number_format($stats['revenue_today']) }} đ</h5>
            </div></div>
        </div>
    </div>

    <div class="admin-section mb-3">
        <form class="row g-2" method="GET" action="{{ route('admin.order.index') }}">
            <div class="col-md-4">
                <input type="text" name="keyword" value="{{ $keyword }}" class="form-control" placeholder="Mã đơn / khách hàng / số điện thoại">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="processing" {{ $status === 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="payment_status" class="form-select">
                    <option value="">Tất cả thanh toán</option>
                    <option value="unpaid" {{ $paymentStatus === 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                    <option value="paid" {{ $paymentStatus === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="refunded" {{ $paymentStatus === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
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
                <button class="btn btn-primary w-100">Lọc</button>
                <a href="{{ route('admin.order.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="admin-table-wrap">
        <div class="table-responsive">
            <table class="table admin-table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Điện thoại</th>
                        <th>Trạng thái</th>
                        <th>Thanh toán</th>
                        <th>PTTT</th>
                        <th>Tổng tiền</th>
                        <th>Ngày tạo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="fw-semibold">{{ $order->order_code }}</td>
                            <td>{{ $order->customer?->fullname }}</td>
                            <td>{{ $order->customer?->phone }}</td>
                            <td>
                                <span class="badge {{
                                    $order->status === 'completed' ? 'bg-success' :
                                    ($order->status === 'processing' ? 'bg-primary' :
                                    ($order->status === 'cancelled' ? 'bg-danger' : 'bg-warning text-dark'))
                                }}">
                                    {{ OrderStatus::orderLabel($order->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : ($order->payment_status === 'refunded' ? 'bg-info' : 'bg-secondary') }}">
                                    {{ OrderStatus::paymentLabel($order->payment_status) }}
                                </span>
                            </td>
                            <td>{{ OrderStatus::paymentMethodLabel($order->payment_method) }}</td>
                            <td>{{ number_format($order->total_amount) }}</td>
                            <td>{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.order.show', $order->id) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center py-4">Chưa có đơn hàng</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <small class="text-muted">Hiển thị {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} / {{ $orders->total() }} đơn</small>
        <div>{{ $orders->links('pagination::bootstrap-5') }}</div>
    </div>
</div>
@endsection

