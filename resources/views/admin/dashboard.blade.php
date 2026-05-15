@extends('admin.layouts.app')

@section('title','Dashboard')

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <h4 class="mb-0 fw-bold">Bảng điều khiển quản trị</h4>
        <p class="text-muted mb-0">Theo dõi nhanh tình hình kinh doanh và vận hành.</p>
    </div>

    <div class="row g-3">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Sản phẩm</small>
                    <h4 class="mb-0 mt-1 fw-bold">{{ number_format($productCount) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Đơn hàng</small>
                    <h4 class="mb-0 mt-1 fw-bold">{{ number_format($orderCount) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Liên hệ mới</small>
                    <h4 class="mb-0 mt-1 fw-bold">{{ number_format($contactCount) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted">Người dùng</small>
                    <h4 class="mb-0 mt-1 fw-bold">{{ number_format($userCount) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Doanh thu</h5>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="rounded-3 border p-3 bg-light">
                                <small class="text-muted">Hôm nay</small>
                                <div class="fs-4 fw-bold text-primary mt-1">{{ number_format($todayRevenue) }} đ</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="rounded-3 border p-3 bg-light">
                                <small class="text-muted">Tháng {{ now()->format('m/Y') }}</small>
                                <div class="fs-4 fw-bold text-success mt-1">{{ number_format($monthRevenue) }} đ</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Tỉ lệ thanh toán</h5>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Đã thanh toán ({{ $paidCount }})</span>
                            <span class="fw-semibold">{{ $paidRate }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ $paidRate }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Chưa thanh toán ({{ $unpaidCount }})</span>
                            <span class="fw-semibold">{{ $unpaidRate }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-secondary" style="width: {{ $unpaidRate }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Đã hoàn tiền ({{ $refundedCount }})</span>
                            <span class="fw-semibold">{{ $refundedRate }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: {{ $refundedRate }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Top sản phẩm bán chạy</h5>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Đã bán</th>
                                    <th class="text-end">Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProducts as $product)
                                    <tr>
                                        <td class="fw-semibold">{{ $product->proname }}</td>
                                        <td class="text-center">{{ number_format($product->sold_qty) }}</td>
                                        <td class="text-end">{{ number_format($product->revenue) }} đ</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Chưa có dữ liệu bán hàng.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="mb-3 fw-bold">Truy cập nhanh</h5>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('admin.product.create') }}" class="btn btn-primary btn-sm">+ Thêm sản phẩm</a>
                        <a href="{{ route('admin.post.create') }}" class="btn btn-outline-primary btn-sm">+ Thêm bài viết</a>
                        <a href="{{ route('admin.order.index') }}" class="btn btn-outline-secondary btn-sm">Xem đơn hàng</a>
                        <a href="{{ route('admin.contact.index') }}" class="btn btn-outline-secondary btn-sm">Xem liên hệ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

