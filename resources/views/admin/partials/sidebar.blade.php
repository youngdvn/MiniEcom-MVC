<aside class="admin-sidebar p-3 text-white">
    <div class="mb-3 px-2">
        <div class="d-flex align-items-center gap-2 rounded-3 p-2" style="background:rgba(255,255,255,.06)">
            <div class="d-inline-flex h-9 w-9 align-items-center justify-content-center rounded-3 fw-bold text-dark" style="background:#38bdf8;">A</div>
            <div>
                <div class="fw-bold">Admin CMS</div>
                <small class="text-white-50">Control Panel</small>
            </div>
        </div>
    </div>

    <div class="admin-menu-title">Tổng quan</div>
    <a class="admin-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>

    <div class="admin-menu-title">Quản lý nội dung</div>
    <a class="admin-link {{ request()->routeIs('admin.product.*') ? 'active' : '' }}" href="{{ route('admin.product.index') }}">Sản phẩm</a>
    <a class="admin-link {{ request()->routeIs('admin.cate.*') ? 'active' : '' }}" href="{{ route('admin.cate.index') }}">Danh mục</a>
    <a class="admin-link {{ request()->routeIs('admin.brand.*') ? 'active' : '' }}" href="{{ route('admin.brand.index') }}">Thương hiệu</a>
    <a class="admin-link {{ request()->routeIs('admin.post.*') ? 'active' : '' }}" href="{{ route('admin.post.index') }}">Bài viết</a>
    <a class="admin-link {{ request()->routeIs('admin.banner.*') ? 'active' : '' }}" href="{{ route('admin.banner.index') }}">Banner / Slide</a>

    <div class="admin-menu-title">CRM</div>
    <a class="admin-link {{ request()->routeIs('admin.order.*') ? 'active' : '' }}" href="{{ route('admin.order.index') }}">Đơn hàng</a>
    <a class="admin-link {{ request()->routeIs('admin.coupon.*') ? 'active' : '' }}" href="{{ route('admin.coupon.index') }}">Mã giảm giá</a>
    <a class="admin-link {{ request()->routeIs('admin.contact.*') ? 'active' : '' }}" href="{{ route('admin.contact.index') }}">Liên hệ</a>
    <a class="admin-link {{ request()->routeIs('admin.user.*') ? 'active' : '' }}" href="{{ route('admin.user.index') }}">Người dùng</a>
</aside>
