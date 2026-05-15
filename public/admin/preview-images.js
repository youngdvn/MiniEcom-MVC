document.querySelectorAll(".img-input").forEach((input) => {
    input.addEventListener("change", function () {
        // this.closest('.group-img'): block cha
        // querySelector('.preview-image'): tìm element con(.preview-image)
        const preview =
            this.closest(".img-group").querySelector(".img-preview");
        preview.innerHTML = "";
        Array.from(this.files).forEach((file) => {
            const img = document.createElement("img");
            img.src = URL.createObjectURL(file);
            img.width = 150;
            img.style.margin = "5px";

            preview.appendChild(img);
        });
    });
});
