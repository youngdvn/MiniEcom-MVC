@extends('admin.layouts.app')
@section('title', 'Quản lý liên hệ')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h4 class="mb-0 fw-bold">Quản lý liên hệ</h4>
            <small class="text-secondary">Theo dõi các yêu cầu hỗ trợ từ khách hàng</small>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <small class="text-muted">Tổng liên hệ</small>
                <h4 class="mt-1 mb-0 fw-bold">{{ number_format($stats['total']) }}</h4>
            </div></div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <small class="text-muted">Hôm nay</small>
                <h4 class="mt-1 mb-0 fw-bold text-primary">{{ number_format($stats['today']) }}</h4>
            </div></div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <small class="text-muted">Tháng này</small>
                <h4 class="mt-1 mb-0 fw-bold text-success">{{ number_format($stats['this_month']) }}</h4>
            </div></div>
        </div>
    </div>

    <div class="admin-section mb-3">
        <form class="row g-2" method="GET" action="{{ route('admin.contact.index') }}">
            <div class="col-md-5">
                <input type="text" name="keyword" value="{{ $keyword }}" class="form-control" placeholder="Tên / email / số điện thoại">
            </div>
            <div class="col-md-2">
                <select name="limit" class="form-select">
                    <option value="5" {{ (int) $limit === 5 ? 'selected' : '' }}>5 / trang</option>
                    <option value="10" {{ (int) $limit === 10 ? 'selected' : '' }}>10 / trang</option>
                    <option value="20" {{ (int) $limit === 20 ? 'selected' : '' }}>20 / trang</option>
                    <option value="50" {{ (int) $limit === 50 ? 'selected' : '' }}>50 / trang</option>
                </select>
            </div>
            <div class="col-md-5 d-flex gap-2">
                <button class="btn btn-primary">Tìm</button>
                <a href="{{ route('admin.contact.index') }}" class="btn btn-outline-secondary">Đặt lại</a>
            </div>
        </form>
    </div>

    <div class="admin-table-wrap">
        <div class="table-responsive">
            <table class="table admin-table align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Điện thoại</th>
                        <th>Nội dung</th>
                        <th>Thời gian</th>
                        <th width="140"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $msg)
                        <tr>
                            <td class="fw-semibold">{{ $msg->fullname }}</td>
                            <td>{{ $msg->email }}</td>
                            <td>{{ $msg->phone ?: '-' }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($msg->message, 80) }}</td>
                            <td>{{ $msg->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="text-nowrap">
                                <a href="{{ route('admin.contact.show', $msg->id) }}" class="btn btn-sm btn-outline-primary">Xem</a>
                                <form method="POST" action="{{ route('admin.contact.del', $msg->id) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa liên hệ này?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4">Không có liên hệ</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <small class="text-muted">Hiển thị {{ $messages->firstItem() ?? 0 }} - {{ $messages->lastItem() ?? 0 }} / {{ $messages->total() }} liên hệ</small>
        <div>{{ $messages->links('pagination::bootstrap-5') }}</div>
    </div>
</div>
@endsection

