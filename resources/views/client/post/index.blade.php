@extends('client.layout.app')
@section('title', 'Bài viết')

@section('content')
    <x-client.partials.breadcrumb :items="[
        ['label' => 'Trang chủ', 'url' => route('home')],
        ['label' => 'Bài viết']
    ]" />

    <section class="mb-5 rounded-lg border border-slate-400 bg-white p-5 border-dashed">
        <h1 class="text-2xl font-extrabold text-slate-900">Bài viết</h1>
        <p class="mt-1 text-sm text-slate-600">Tin tức, mẹo mua sắm và các cập nhật mới từ MiniEcom.</p>
    </section>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
        @forelse($posts as $post)
            <article class="flex h-full flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <img src="{{ $post->image ?: asset('img/default.png') }}" alt="{{ $post->title }}" class="h-44 w-full object-cover">
                <div class="flex flex-1 flex-col p-4">
                    <h3 class="line-clamp-2 min-h-14 text-base font-bold text-slate-900">{{ $post->title }}</h3>
                    <p class="mt-2 text-xs text-slate-500">{{ $post->created_at?->format('d/m/Y') }}</p>
                    <a href="{{ route('post.show', $post->slug) }}" class="mt-auto ml-auto inline-block w-fit rounded-lg bg-sky-100 px-3 py-1.5 text-sm font-semibold text-sky-700 hover:bg-sky-200">
                        Đọc chi tiết
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-500">
                Chưa có bài viết.
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        <x-client.pagination :paginator="$posts" />
    </div>
@endsection
