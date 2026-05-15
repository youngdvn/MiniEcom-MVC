<div class="relative z-30 border-b border-slate-200 bg-white/95 backdrop-blur shadow-sm">
    <div class="mx-auto flex w-full max-w-7xl flex-wrap items-center gap-2 px-4 py-2">
        <a href="{{ route('home') }}" class="shrink-0 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 no-underline hover:bg-sky-50 hover:text-sky-700">
            Trang chủ
        </a>
        <a href="{{ route('product.index') }}" class="shrink-0 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 no-underline hover:bg-sky-50 hover:text-sky-700">
            Sản phẩm
        </a>
        <div class="group relative shrink-0">
            <button type="button" class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700">
                Danh mục
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                </svg>
            </button>
            <div class="invisible absolute left-1 top-full z-50 grid w-107.5 grid-cols-2 gap-1 rounded-xl border border-slate-200 bg-white p-3 opacity-0 shadow-xl transition duration-150 group-hover:visible group-hover:opacity-100">
                @foreach($categories as $item)
                    <a class="rounded-md px-2 py-2 text-sm text-slate-700 no-underline hover:bg-sky-50 hover:text-sky-700" href="{{ route('product.category', ['slug' => $item->slug]) }}">
                        {{ $item->catename }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="group relative shrink-0">
            <button type="button" class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-sky-50 hover:text-sky-700">
                Thương hiệu
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.51a.75.75 0 0 1-1.08 0l-4.25-4.51a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                </svg>
            </button>
            <div class="invisible absolute left-1 top-full z-50 grid w-107.5 grid-cols-2 gap-1 rounded-xl border border-slate-200 bg-white p-3 opacity-0 shadow-xl transition duration-150 group-hover:visible group-hover:opacity-100">
                @foreach($brands as $item)
                    <a class="rounded-md px-2 py-1.5 text-sm text-slate-700 no-underline hover:bg-sky-50 hover:text-sky-700" href="{{ route('product.brand', ['slug' => $item->slug]) }}">
                        {{ $item->brandname }}
                    </a>
                @endforeach
            </div>
        </div>
        <a href="{{ route('post.index') }}" class="shrink-0 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 no-underline hover:bg-sky-50 hover:text-sky-700">
            Bài viết
        </a>
        <a href="{{ route('contact') }}" class="shrink-0 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 no-underline hover:bg-sky-50 hover:text-sky-700">
            Liên hệ
        </a>

        
    </div>
</div>
