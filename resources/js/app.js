import "./bootstrap";
import "./cart";

function syncClientHeaderSpacer() {
    const fixedHeader = document.getElementById("client-fixed-header");
    const spacer = document.getElementById("client-header-spacer");
    if (!fixedHeader || !spacer) return;

    spacer.style.height = `${fixedHeader.offsetHeight + 12}px`;
}

function getToastContainer() {
    let container = document.getElementById("toast-container");
    if (container) {
        container.className =
            "pointer-events-none fixed top-4 right-4 z-[1000001] flex w-[calc(100%-2rem)] max-w-sm flex-col gap-2";
        return container;
    }

    container = document.createElement("div");
    container.id = "toast-container";
    container.className =
        "pointer-events-none fixed top-4 right-4 z-[1000001] flex w-[calc(100%-2rem)] max-w-sm flex-col gap-2";
    document.body.appendChild(container);
    return container;
}

function showToast(message, type = "success") {
    if (!message) return;
    const container = getToastContainer();

    const tone =
        type === "error"
            ? "border-rose-200 bg-rose-50 text-rose-700"
            : type === "info"
              ? "border-sky-200 bg-sky-50 text-sky-700"
              : "border-emerald-200 bg-emerald-50 text-emerald-700";

    const toast = document.createElement("div");
    toast.className = `pointer-events-auto translate-y-2 rounded-lg border px-4 py-3 text-sm font-semibold opacity-0 shadow-lg transition-all duration-300 ${tone}`;
    toast.textContent = message;
    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.classList.remove("opacity-0", "translate-y-2");
    });

    setTimeout(() => {
        toast.classList.add("opacity-0", "translate-y-2");
        setTimeout(() => toast.remove(), 280);
    }, 2600);
}

window.showAppToast = showToast;
window.addEventListener("app:toast", (event) => {
    showToast(event.detail?.message, event.detail?.type || "success");
});

document.addEventListener("DOMContentLoaded", () => {
    syncClientHeaderSpacer();
    window.addEventListener("resize", syncClientHeaderSpacer);

    document.querySelectorAll("[data-flash-message]").forEach((flash) => {
        if (flash.dataset.flashMessage) {
            showToast(flash.dataset.flashMessage, flash.dataset.flashType || "success");
        }
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("scroll-top-btn");
    if (!btn) return;

    const toggleButton = () => {
        if (window.scrollY > 280) {
            btn.classList.remove("opacity-0", "translate-y-3", "pointer-events-none");
        } else {
            btn.classList.add("opacity-0", "translate-y-3", "pointer-events-none");
        }
    };

    toggleButton();
    window.addEventListener("scroll", toggleButton, { passive: true });

    btn.addEventListener("click", () => {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const slider = document.querySelector("[data-banner-slider]");
    if (!slider) return;

    const slides = Array.from(slider.querySelectorAll("[data-banner-slide]"));
    const dots = Array.from(slider.querySelectorAll("[data-banner-dot]"));
    const prevButton = slider.querySelector("[data-banner-prev]");
    const nextButton = slider.querySelector("[data-banner-next]");
    if (slides.length <= 1) return;

    let activeIndex = 0;
    let timerId;

    const render = () => {
        slides.forEach((slide, index) => {
            slide.classList.toggle("opacity-100", index === activeIndex);
            slide.classList.toggle("z-10", index === activeIndex);
            slide.classList.toggle("opacity-0", index !== activeIndex);
            slide.classList.toggle("z-0", index !== activeIndex);
            slide.classList.toggle("pointer-events-none", index !== activeIndex);
        });

        dots.forEach((dot, index) => {
            dot.classList.toggle("bg-white", index === activeIndex);
            dot.classList.toggle("bg-white/50", index !== activeIndex);
        });
    };

    const goTo = (index) => {
        activeIndex = (index + slides.length) % slides.length;
        render();
    };

    const startAutoplay = () => {
        clearInterval(timerId);
        timerId = setInterval(() => goTo(activeIndex + 1), 4500);
    };

    prevButton?.addEventListener("click", () => {
        goTo(activeIndex - 1);
        startAutoplay();
    });

    nextButton?.addEventListener("click", () => {
        goTo(activeIndex + 1);
        startAutoplay();
    });

    dots.forEach((dot, index) => {
        dot.addEventListener("click", () => {
            goTo(index);
            startAutoplay();
        });
    });

    slider.addEventListener("mouseenter", () => clearInterval(timerId));
    slider.addEventListener("mouseleave", startAutoplay);

    render();
    startAutoplay();
});

document.addEventListener("DOMContentLoaded", () => {
    const forms = document.querySelectorAll("form");
    if (!forms.length) return;

    const slugify = (value) => {
        return (value || "")
            .toString()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .replace(/đ/g, "d")
            .replace(/Đ/g, "D")
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, "")
            .replace(/\s+/g, "-")
            .replace(/-+/g, "-")
            .replace(/^-|-$/g, "");
    };

    forms.forEach((form) => {
        const slugInput = form.querySelector('input[name="slug"]');
        if (!slugInput) return;

        const sourceInput =
            form.querySelector('input[name="title"]') ||
            form.querySelector('input[name="proname"]') ||
            form.querySelector('input[name="catename"]') ||
            form.querySelector('input[name="brandname"]');

        if (!sourceInput) return;

        slugInput.readOnly = true;

        const syncSlug = () => {
            slugInput.value = slugify(sourceInput.value);
        };

        syncSlug();
        sourceInput.addEventListener("input", syncSlug);
        sourceInput.addEventListener("change", syncSlug);
    });
});
