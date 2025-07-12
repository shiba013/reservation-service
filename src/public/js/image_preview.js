document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const fileName = document.getElementById('fileName');

    if (imageInput) {
        imageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (file && imagePreview) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }

            if (fileName) {
                fileName.textContent = file ? file.name : '未選択';
            }
        });

        if (imagePreview && !imagePreview.getAttribute('src')) {
            imagePreview.style.display = 'none';
        } else {
            imagePreview.style.display = 'block';
        }
    }
});