@extends('admin.layouts.app')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div>
            <h4 class="mb-0 fw-bold">Quản lý người dùng</h4>
            <small class="text-secondary">Quản trị tài khoản admin và khách hàng</small>
        </div>
        <a href="{{ route('admin.user.create') }}" class="btn btn-primary">+ Thêm người dùng</a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <small class="text-muted">Tổng tài khoản</small>
                <h4 class="mt-1 mb-0 fw-bold">{{ number_format($stats['total']) }}</h4>
            </div></div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <small class="text-muted">Quản trị viên</small>
                <h4 class="mt-1 mb-0 fw-bold text-danger">{{ number_format($stats['admin']) }}</h4>
            </div></div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <small class="text-muted">Khách hàng</small>
                <h4 class="mt-1 mb-0 fw-bold text-secondary">{{ number_format($stats['client']) }}</h4>
            </div></div>
        </div>
    </div>

    <div class="admin-section mb-3">
        <form method="GET" action="{{ route('admin.user.index') }}">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="keyword" class="form-control" placeholder="Tìm username, tên, email..." value="{{ $keyword }}">
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">Tất cả vai trò</option>
                        <option value="1" {{ $role === '1' ? 'selected' : '' }}>Admin</option>
                        <option value="0" {{ $role === '0' ? 'selected' : '' }}>User</option>
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
                    <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary">Đặt lại</a>
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
                        <th>Tên đăng nhập</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th width="110">Vai trò</th>
                        <th width="260">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($list as $user)
                        <tr>
                            <td class="text-center">#{{ $user->id }}</td>
                            <td class="fw-semibold">{{ $user->username }}</td>
                            <td>{{ $user->fullname }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">
                                @if($user->role == 1)
                                    <span class="badge bg-danger">Admin</span>
                                @else
                                    <span class="badge bg-secondary">User</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.user.edit', ['id' => $user->id]) }}" class="btn btn-outline-primary btn-sm">Sửa</a>
                                <a href="{{ route('admin.user.password.edit', ['id' => $user->id]) }}" class="btn btn-secondary btn-sm">Đổi mật khẩu</a>
                                <form action="{{ route('admin.user.del', ['id' => $user->id]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">Không có dữ liệu người dùng.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <small class="text-muted">Hiển thị {{ $list->firstItem() ?? 0 }} - {{ $list->lastItem() ?? 0 }} / {{ $list->total() }} tài khoản</small>
        <div>{{ $list->links('pagination::bootstrap-5') }}</div>
    </div>
</div>
@endsection

