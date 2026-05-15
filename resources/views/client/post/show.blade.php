@extends('client.layout.app')
@section('title', $post->title)

@section('content')
    <x-client.partials.breadcrumb :items="[
        ['label' => 'Trang chủ', 'url' => route('home')],
        ['label' => 'Bài viết', 'url' => route('post.index')],
        ['label' => $post->title]
    ]" />

    <article class="rounded-lg border border-slate-200 bg-white p-5">
        <p class="text-sm text-slate-500">Bài viết - {{ $post->created_at?->format('d/m/Y') }}</p>
        <h1 class="mt-2 text-3xl font-extrabold text-slate-900">{{ $post->title }}</h1>

        <img src="{{ $post->image ?: asset('img/default.png') }}" alt="{{ $post->title }}" class="mt-4 h-100 w-full rounded-md object-cover">

        <div class="prose mt-5 max-w-none text-slate-700">
            {!! $post->content !!}
        </div>
    </article>

    <section class="mt-6">
        <h2 class="mb-3 text-lg font-bold text-slate-900">Bài viết liên quan</h2>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            @forelse($relatedPosts as $item)
                <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <a href="{{ route('post.show', $item->slug) }}">
                        <img src="{{ $item->image ?: asset('img/default.png') }}" alt="{{ $item->title }}" class="h-36 w-full object-cover">
                    </a>
                    <div class="p-3">
                        <p class="text-xs text-slate-500">{{ $item->created_at?->format('d/m/Y') }}</p>
                        <a href="{{ route('post.show', $item->slug) }}" class="mt-1 line-clamp-2 block text-sm font-bold text-slate-800 hover:text-sky-700">
                            {{ $item->title }}
                        </a>
                    </div>
                </article>
            @empty
                <p class="text-sm text-slate-500">Không có bài viết liên quan.</p>
            @endforelse
        </div>
    </section>
@endsection
