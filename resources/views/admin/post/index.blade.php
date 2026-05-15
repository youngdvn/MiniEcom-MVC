@extends('admin.layouts.app')

@section('title', 'Quản lý bài viết')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div>
            <h4 class="mb-0 fw-bold">Quản lý bài viết</h4>
            <small class="text-secondary">Theo dõi nội dung blog và trạng thái hiển thị</small>
        </div>
        <a href="{{ route('admin.post.create') }}" class="btn btn-primary">+ Thêm bài viết</a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <small class="text-muted">Tổng bài viết</small>
                <h4 class="mt-1 mb-0 fw-bold">{{ number_format($stats['total']) }}</h4>
            </div></div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <small class="text-muted">Đang hiển thị</small>
                <h4 class="mt-1 mb-0 fw-bold text-success">{{ number_format($stats['published']) }}</h4>
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
        <form method="GET" action="{{ route('admin.post.index') }}">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="keyword" class="form-control" placeholder="Tìm tiêu đề hoặc slug..." value="{{ $keyword }}">
                </div>
                <div class="col-md-3">
                    <select name="userid" class="form-select">
                        <option value="">Tất cả tác giả</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ (int) $userid === (int) $user->id ? 'selected' : '' }}>
                                {{ $user->username }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
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
                    <a href="{{ route('admin.post.index') }}" class="btn btn-outline-secondary">Đặt lại</a>
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
                        <th width="140">Tác giả</th>
                        <th>Tiêu đề</th>
                        <th>Slug</th>
                        <th>Nội dung</th>
                        <th width="100">Trạng thái</th>
                        <th width="190">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($list as $post)
                        <tr>
                            <td class="text-center">{{ $post->id }}</td>
                            <td>{{ $post->user->username ?? 'Không rõ' }}</td>
                            <td class="fw-semibold">{{ $post->title }}</td>
                            <td>{{ $post->slug }}</td>
                            <td>{{ \Illuminate\Support\Str::limit(strip_tags($post->content), 90) }}</td>
                            <td class="text-center">
                                <span class="badge {{ $post->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $post->status == 1 ? 'Hiển thị' : 'Ẩn' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('post.show', ['slug' => $post->slug]) }}" target="_blank" class="btn btn-outline-info btn-sm">Xem</a>
                                <a href="{{ route('admin.post.edit', ['id' => $post->id]) }}" class="btn btn-outline-primary btn-sm">Sửa</a>
                                <form action="{{ route('admin.post.del', ['id' => $post->id]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">Không có dữ liệu bài viết.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <small class="text-muted">Hiển thị {{ $list->firstItem() ?? 0 }} - {{ $list->lastItem() ?? 0 }} / {{ $list->total() }} bài viết</small>
        <div>{{ $list->links('pagination::bootstrap-5') }}</div>
    </div>
</div>
@endsection
