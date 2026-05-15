<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','EcomMini')</title>

    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/style.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="toast-container" class="pointer-events-none fixed top-4 right-4 z-[9999] flex w-[calc(100%-2rem)] max-w-sm flex-col gap-2"></div>
    @if(session('message'))
        <div data-flash-message="{{ session('message') }}" data-flash-type="success" class="hidden"></div>
    @endif
    @if($errors->any())
        <div data-flash-message="{{ $errors->first() }}" data-flash-type="error" class="hidden"></div>
    @endif

    @include('admin.partials.sidebar')
    @include('admin.partials.header')

    <div class="admin-content">
        @yield('content')
    </div>

    @include('admin.partials.footer')

    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @yield('scripts')
</body>
</html>
