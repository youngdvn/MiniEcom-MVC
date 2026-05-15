<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Không tìm thấy trang</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div style="min-height:100vh;display:flex;justify-content:center;align-items:center;background:linear-gradient(135deg,#3b82f6,#60a5fa);padding:20px;">
        <div style="max-width:620px;width:100%;background:#fff;border-radius:14px;padding:40px;box-shadow:0 18px 40px rgba(0,0,0,0.15);text-align:center;">
            <h1 style="font-size:64px;font-weight:800;color:#2563eb;margin-bottom:0;">404</h1>
            <h2 style="font-size:28px;font-weight:700;margin-top:8px;">Không tìm thấy trang</h2>
            <p style="color:#475569;margin-top:12px;margin-bottom:28px;">
                Trang bạn đang tìm có thể đã bị xóa, đổi đường dẫn hoặc quay lại sau.
            </p>

            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary" style="padding:10px 18px;border-radius:10px;">Về trang chủ</a>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary ms-2" style="padding:10px 18px;border-radius:10px;">Quay lại</a>
        </div>
    </div>
</body>
</html>
