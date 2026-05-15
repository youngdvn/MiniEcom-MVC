@extends('admin.layouts.app')
@section('title', 'Chi tiết đơn hàng')

@section('content')
@php use App\Support\OrderStatus; @endphp
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 fw-bold">Chi tiết đơn {{ $order->order_code }}</h4>
        <a href="{{ route('admin.order.index') }}" class="btn btn-outline-secondary btn-sm">Quay lại</a>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="admin-table-wrap">
                <div class="card-body p-0">
                    <table class="table admin-table mb-0 align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Phiên bản</th>
                                <th>SL</th>
                                <th>Giá</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product?->proname ?? 'Sản phẩm đã xóa' }}</td>
                                <td>{{ $item->variant_size ?? '-' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price) }}</td>
                                <td>{{ number_format($item->price * $item->quantity) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3 rounded-4">
                <div class="card-body">
                    <h6 class="fw-bold">Thông tin đơn hàng</h6>
                    <p class="mb-1">
                        <strong>Trạng thái:</strong>
                        <span class="badge {{
                            $order->status === 'completed' ? 'bg-success' :
                            ($order->status === 'processing' ? 'bg-primary' :
                            ($order->status === 'cancelled' ? 'bg-danger' : 'bg-warning text-dark'))
                        }}">
                            {{ OrderStatus::orderLabel($order->status) }}
                        </span>
                    </p>
                    <p class="mb-1"><strong>Khách hàng:</strong> {{ $order->customer?->fullname }}</p>
                    <p class="mb-1"><strong>SĐT:</strong> {{ $order->customer?->phone }}</p>
                    <p class="mb-1"><strong>Địa chỉ:</strong> {{ $order->customer?->address }}</p>
                    <p class="mb-1"><strong>Tổng tiền:</strong> {{ number_format($order->total_amount) }}</p>
                    <p class="mb-1"><strong>Phương thức:</strong> {{ OrderStatus::paymentMethodLabel($order->payment_method) }}</p>
                    <p class="mb-1"><strong>Thanh toán:</strong> {{ OrderStatus::paymentLabel($order->payment_status) }}</p>
                    <p class="mb-1"><strong>Ghi chú:</strong> {{ $order->note }}</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body">
                    <h6 class="fw-bold">Cập nhật trạng thái</h6>
                    <form method="POST" action="{{ route('admin.order.status.update', $order->id) }}">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-select mb-2">
                            @foreach(['pending','processing','completed','cancelled'] as $status)
                                <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ OrderStatus::orderLabel($status) }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary btn-sm">Lưu trạng thái</button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h6 class="fw-bold">Cập nhật thanh toán</h6>
                    <form method="POST" action="{{ route('admin.order.payment-status.update', $order->id) }}">
                        @csrf
                        @method('PUT')
                        <select name="payment_status" class="form-select mb-2">
                            @foreach(['unpaid', 'paid', 'refunded'] as $paymentStatus)
                                <option value="{{ $paymentStatus }}" {{ $order->payment_status === $paymentStatus ? 'selected' : '' }}>
                                    {{ OrderStatus::paymentLabel($paymentStatus) }}
                                </option>
                            @endforeach
                        </select>
                        <button class="btn btn-success btn-sm">Lưu thanh toán</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
