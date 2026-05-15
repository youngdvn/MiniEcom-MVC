@props(['paginator'])

@if($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $window = 2;

        $pages = [1];
        for ($i = max(2, $current - $window); $i <= min($last - 1, $current + $window); $i++) {
            $pages[] = $i;
        }
        if ($last > 1) {
            $pages[] = $last;
        }
        $pages = array_values(array_unique($pages));
    @endphp

    <nav class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between" role="navigation" aria-label="Pagination Navigation">
        <p class="text-sm text-slate-600">
            Hiển thị
            <span class="font-semibold">{{ $paginator->firstItem() ?? 0 }}</span>
            -
            <span class="font-semibold">{{ $paginator->lastItem() ?? 0 }}</span>
            trên
            <span class="font-semibold">{{ $paginator->total() }}</span>
            kết quả
        </p>

        <div class="flex flex-wrap items-center gap-1.5">
            @if($paginator->onFirstPage())
                <span class="inline-flex h-9 items-center rounded-lg border border-slate-200 bg-slate-100 px-3 text-sm font-medium text-slate-400">
                    Trước
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex h-9 items-center rounded-lg border border-slate-200 bg-white px-3 text-sm font-medium text-slate-700 hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700">
                    Trước
                </a>
            @endif

            @php $prev = null; @endphp
            @foreach($pages as $page)
                @if(!is_null($prev) && $page - $prev > 1)
                    <span class="inline-flex h-9 items-center px-2 text-sm text-slate-500">...</span>
                @endif

                @if($page == $current)
                    <span class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg bg-sky-500 px-3 text-sm font-semibold text-white">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $paginator->url($page) }}" class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg border border-slate-200 bg-white px-3 text-sm font-medium text-slate-700 hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700">
                        {{ $page }}
                    </a>
                @endif
                @php $prev = $page; @endphp
            @endforeach

            @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex h-9 items-center rounded-lg border border-slate-200 bg-white px-3 text-sm font-medium text-slate-700 hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700">
                    Sau
                </a>
            @else
                <span class="inline-flex h-9 items-center rounded-lg border border-slate-200 bg-slate-100 px-3 text-sm font-medium text-slate-400">
                    Sau
                </span>
            @endif
        </div>
    </nav>
@endif
