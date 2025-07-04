//検索フォーム即時送信
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('searchForm');
    const areaSelect = document.getElementById('areaSelect');
    const genreSelect = document.getElementById('genreSelect');
    const keywordInput = document.getElementById('keywordInput');
    let timer = null;

    areaSelect.addEventListener('change', () => form.submit());
    genreSelect.addEventListener('change', () => form.submit());

    //inputタグは入力が停止してから1秒後に送信
    keywordInput.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(() => {
            form.submit();
        }, 1000);
    });
});