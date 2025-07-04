//お気に入り登録
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    document.querySelectorAll('.favorite-button').forEach(button => {
        button.addEventListener('click', async function () {
            const shopId = this.dataset.shopId;
            const wasFavorite = this.dataset.wasFavorite === '1';
            const img = this.querySelector('img');

            try {
                const response = await fetch('/favorite/' + shopId, {
                    'method': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({}),
                });
                const result = await response.json();
                if (result.status === 'added') {
                    this.dataset.wasFavorite = '1';
                    img.classList.remove('off');
                    img.classList.add('on');

                } else if (result.status === 'removed') {
                    this.dataset.wasFavorite = '0';
                    img.classList.remove('on');
                    img.classList.add('off');
                }
            } catch (err) {
                console.error('通信エラー', err);
            }
        });
    });
});