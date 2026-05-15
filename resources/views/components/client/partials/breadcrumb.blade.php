@props(['items' => []])

<nav aria-label="Breadcrumb" class="mb-4">
    <ol class="flex flex-wrap items-center gap-1.5 text-sm text-slate-500">
        @foreach($items as $item)
            @if(!$loop->first)
                <li>/</li>
            @endif
            <li>
                @if(!empty($item['url']))
                    <a href="{{ $item['url'] }}" class="hover:text-sky-700">{{ $item['label'] }}</a>
                @else
                    <span class="font-semibold text-slate-700">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
