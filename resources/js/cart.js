document.addEventListener('submit', function (e) {
    const form = e.target.closest('.form-add-cart');
    if (!form) return;

    e.preventDefault();
    addToCart(form);
});

function addToCart(form) {
    const url = form.action;
    const formData = new FormData(form);

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            Accept: 'application/json',
        },
    })
        .then((res) => {
            return res.json().then((data) => {
                if (!res.ok) {
                    const message = data?.message || ('HTTP ' + res.status);
                    throw new Error(message);
                }
                return data;
            });
        })
        .then((data) => {
            const cartCount = document.getElementById('cart-count');
            if (cartCount && data.cart_count !== undefined) {
                cartCount.innerText = data.cart_count;
            }

            window.dispatchEvent(new CustomEvent('app:toast', {
                detail: { message: data.message, type: 'success' }
            }));
        })
        .catch((err) => {
            console.error('Cart error:', err);
            window.dispatchEvent(new CustomEvent('app:toast', {
                detail: { message: err.message || 'Có lỗi xảy ra', type: 'error' }
            }));
        });
}
