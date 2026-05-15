<div class="border-b border-slate-800 bg-slate-900 text-white ">
    <div class="mx-auto flex w-full max-w-7xl flex-col gap-3 px-4 py-4 lg:flex-row lg:items-center lg:justify-between">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-white no-underline">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-sky-400 font-extrabold text-black">M</span>
            <span class="block text-lg font-extrabold leading-none">MiniEcom</span>
        </a>
        <div class="flex items-center justify-between gap-2">
            <form action="{{ route('product.search') }}" method="GET" class="relative w-full max-w-2xl">
                <input type="text" name="keyword" class="h-11 w-full rounded-full border border-slate-600 bg-white pl-4 pr-12 text-slate-800  focus:outline-none" placeholder="Tìm kiếm sản phẩm..." value="{{ request('keyword') }}">
                <button type="submit" aria-label="Tìm kiếm" class="absolute right-1.5 top-1/2 inline-flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full bg-sky-400 text-white cursor-pointer hover:bg-sky-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 3a6 6 0 1 0 3.87 10.586l3.272 3.272a1 1 0 0 0 1.415-1.414l-3.273-3.273A6 6 0 0 0 9 3Zm-4 6a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" clip-rule="evenodd" />
                    </svg>
                </button>
            </form>
            @auth
                <a href="{{ route('wishlist.index') }}" class=" px-3 py-2 text-sm font-semibold  text-sky-300 no-underline">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 hover:fill-sky-300 hover:scale-110">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                </svg>
                </a>
            @endauth
            <a href="{{ route('cart.show') }}" class="relative inline-flex items-center justify-center rounded-lg bg-sky-400 px-3 py-2 text-sm font-bold text-white no-underline hover:bg-sky-300">
                <svg xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z"/>
                </svg>
                <span id="cart-count"
                    class="absolute -top-1 -right-1 min-w-5 h-5 flex items-center justify-center rounded-full bg-slate-900 px-1 text-[10px] font-bold text-white">
                    {{ count(session('cart', [])) }}
                </span>
            </a>
            @guest
                <button
                    type="button"
                    onclick="window.location.href='{{ route('client.login') }}'"
                    class="whitespace-nowrap rounded-lg border border-sky-400/60 px-3 py-2 text-sm font-semibold text-sky-300 hover:bg-sky-400/10"
                >
                    Đăng nhập
                </button>

                <button
                    type="button"
                    onclick="window.location.href='{{ route('client.register') }}'"
                    class="whitespace-nowrap rounded-lg bg-sky-400 px-3 py-2 text-sm font-bold text-white/85 hover:bg-sky-300"
                >
                    Đăng ký
                </button>
            @else
                <div class="group relative">
                    <button type="button" class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-semibold text-slate-100 hover:bg-slate-800">
                        {{-- {{ auth()->user()->username }} --}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>

                    </button>
                    <div class="invisible absolute left-0 top-full z-50 w-40 rounded-lg border border-slate-700 bg-slate-900 p-2 opacity-0 shadow-lg transition duration-150 group-hover:visible group-hover:opacity-100">
                        <a href="{{ route('account.edit') }}" class="rounded px-3 py-2 text-sm text-slate-200 no-underline hover:bg-slate-800 flex items-center gap-1">
Tài khoản 
                        </a>
                        <a href="{{ route('order.index') }}" class="flex items-center gap-1 rounded px-3 py-2 text-sm text-slate-200 no-underline hover:bg-slate-800">
                            
                            Đơn hàng
                    </a>
                        <form action="{{ route('client.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-1 w-full rounded px-3 py-2 text-left text-sm font-semibold text-rose-300 hover:bg-slate-800">
                            Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            @endguest


        </div>
    </div>
</div>
