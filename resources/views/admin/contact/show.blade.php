@extends('admin.layouts.app')
@section('title', 'Chi tiết liên hệ')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 fw-bold">Chi tiết liên hệ</h4>
        <a href="{{ route('admin.contact.index') }}" class="btn btn-outline-secondary btn-sm">Quay lại</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <p><strong>Họ tên:</strong> {{ $message->fullname }}</p>
            <p><strong>Email:</strong> {{ $message->email }}</p>
            <p><strong>Số điện thoại:</strong> {{ $message->phone ?: 'Không có' }}</p>
            <p><strong>Thời gian gửi:</strong> {{ $message->created_at?->format('d/m/Y H:i') }}</p>
            <hr>
            <h6 class="fw-bold">Nội dung</h6>
            <div class="rounded border bg-light p-3" style="white-space:pre-line;">{{ $message->message }}</div>
        </div>
    </div>
</div>
@endsection
