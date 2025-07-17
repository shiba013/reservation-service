document.addEventListener('DOMContentLoaded', function () {
    const date = document.getElementById('date');
    const form = document.querySelector('.date__search-form');

    if (date && form) {
        date.addEventListener('change', function () {
            if (date.value) {
                form.submit();
            }
        });
    }
});