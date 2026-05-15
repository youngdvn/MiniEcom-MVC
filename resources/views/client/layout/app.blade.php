<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Shop')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-800">
    <div id="toast-container" class="pointer-events-none fixed top-4 right-4 z-[1000001] flex w-[calc(100%-2rem)] max-w-sm flex-col gap-2"></div>
    @if(session('message'))
        <div data-flash-message="{{ session('message') }}" data-flash-type="success" class="hidden"></div>
    @endif
    @if($errors->any())
        <div data-flash-message="{{ $errors->first() }}" data-flash-type="error" class="hidden"></div>
    @endif

    <div id="client-fixed-header" class="fixed top-0 w-full z-900000">
        @include('client.partials.header')
        @include('client.partials.navbar')
    </div>
    <div id="client-header-spacer" aria-hidden="true"></div>

    <main class="pb-6 min-h-screen">
        <div class="mx-auto w-full max-w-7xl px-4">
            @yield('content')
        </div>
    </main>

    <button
        id="scroll-top-btn"
        type="button"
        aria-label="Cuộn lên đầu trang"
        class="pointer-events-none fixed right-5 bottom-5 z-40 inline-flex h-11 w-11 translate-y-3 items-center justify-center rounded-full bg-sky-500 text-white opacity-0 shadow-lg transition-all duration-300 hover:bg-sky-600"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 0 1 .707.293l4 4a1 1 0 1 1-1.414 1.414L11 6.414V16a1 1 0 1 1-2 0V6.414L6.707 8.707a1 1 0 0 1-1.414-1.414l4-4A1 1 0 0 1 10 3Z" clip-rule="evenodd" />
        </svg>
    </button>

    @include('client.partials.footer')
</body>
</html>
