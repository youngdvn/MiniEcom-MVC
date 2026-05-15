<header class="admin-header">
    <div class="h-100 d-flex align-items-center justify-content-between px-4">
        <div>
            <h6 class="mb-0 fw-bold text-slate-800">Bảng điều khiển quản trị</h6>
            <small class="text-secondary">Quản lý dữ liệu hệ thống và vận hành cửa hàng</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill text-bg-light border px-3 py-2">Xin chào, {{ Auth::user()->fullname }}</span>
            <form action="{{route('admin.logout')}}" method="post" class="mb-0">
                @csrf
                <button class="btn btn-sm btn-outline-danger" type="submit">Đăng xuất</button>
            </form>
        </div>
    </div>
</header>
