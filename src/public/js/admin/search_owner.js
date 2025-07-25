document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('searchForm');
    const keyword = document.getElementById('keywordInput');
    let timer = null;

    keyword.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(() => {
            form.submit();
        }, 1000);
    });
});