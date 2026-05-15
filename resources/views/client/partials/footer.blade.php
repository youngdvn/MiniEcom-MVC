<footer class="mt-10 bg-slate-950 text-slate-200">
    <div class="mx-auto grid w-full max-w-7xl gap-8 px-4 py-10 md:grid-cols-2 lg:grid-cols-4">
        <div>
            <h4 class="text-lg font-extrabold text-white">MiniEcom</h4>
            <p class="mt-3 text-sm leading-6 text-slate-300">Nền tảng mua sắm trực tuyến với sản phẩm đa dạng, giá tốt và quy trình đặt hàng nhanh chóng.</p>
        </div>

        <div>
            <h5 class="text-sm font-bold uppercase tracking-wide text-white">Danh mục</h5>
            <ul class="mt-3 space-y-2 text-sm">
                <li><a href="{{ route('product.search') }}" class="hover:text-sky-300">Tất cả sản phẩm</a></li>
                <li><a href="{{ route('cart.show') }}" class="hover:text-sky-300">Giỏ hàng</a></li>
                <li><a href="{{ route('cart.checkout') }}" class="hover:text-sky-300">Thanh toán</a></li>
            </ul>
        </div>

        <div>
            <h5 class="text-sm font-bold uppercase tracking-wide text-white">Chính sách</h5>
            <ul class="mt-3 space-y-2 text-sm text-slate-300">
                <li>Đổi trả trong 7 ngày</li>
                <li>Hỗ trợ khách hàng 8:00 - 22:00</li>
                <li>Thanh toán bảo mật</li>
            </ul>
        </div>

        <div>
            <h5 class="text-sm font-bold uppercase tracking-wide text-white">Liên hệ</h5>
            <ul class="mt-3 space-y-2 text-sm text-slate-300">
                <li>Email: support@miniecom.vn</li>
                <li>Hotline: 1900 1234</li>
                <li>Địa chỉ: TP. Hồ Chí Minh</li>
            </ul>
        </div>
    </div>

    <div class="border-t border-slate-800">
        <div class="mx-auto flex w-full max-w-7xl flex-col gap-2 px-4 py-4 text-xs text-slate-400 sm:flex-row sm:items-center sm:justify-between">
            <p>© 2026 MiniEcom. Bản quyền thuộc về MiniEcom.</p>
            <a href="#top" class="font-semibold text-slate-300 hover:text-sky-300">Lên đầu trang</a>
        </div>
    </div>
</footer>
