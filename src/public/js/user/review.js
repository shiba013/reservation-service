//ソート機能
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('sortForm');
    const sortSelect = document.getElementById('sortSelect');

    sortSelect.addEventListener('change', () => form.submit());
});

//投稿ダイアログ表示
window.openCreateReview = function () {
    const overlay = document.getElementById('overlay-create');
    const createForm = document.getElementById('create');
    const isAuth = createForm.dataset.isAuth === '1';

    if (!isAuth) {
        window.location.href = '/login?message=login_required';
        return;
    }

    overlay.style.display = 'flex';
    createForm.style.display = 'block';
}

//投稿ダイアログ非表示
window.closeCreateReview = function () {
    const overlay = document.getElementById('overlay-create');
    const createForm = document.getElementById('create');
    overlay.style.display = 'none';
    createForm.style.display = 'none';
    location.reload();
}

//評価・コメント送信
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    document.querySelectorAll('.create-review-form').forEach(form => {
        const stars = form.querySelectorAll('.star');
        const comment = form.querySelector('.comment');
        const evaluationAlert = form.querySelector('.evaluation-alert');
        const commentAlert = form.querySelector('.comment-alert');
        const submit = form.querySelector('.create-btn');
        const shopId = form.dataset.shopId;
        let evaluation = 0;

        //星のクリック
        stars.forEach((star, index) => {
            star.addEventListener('click', () => {
                evaluation = parseInt(star.dataset.value);
                stars.forEach((s, i) => {
                    s.classList.toggle('selected', i < evaluation);
                });
            });
        });

        //投稿ボタン
        submit.addEventListener('click', async () => {
            if (evaluation === 0) {
                evaluationAlert.textContent = '評価を入力してください';
                return;
            }

            const commentValue = comment.value;

            if (commentValue.length > 500) {
                commentAlert.textContent = 'コメントは500文字以内で入力してください';
                return;
            }

            //投稿処理
            try {
                const response = await fetch('/review/' + shopId, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        shop_id: shopId,
                        evaluation: evaluation,
                        comment: commentValue,
                    }),
                });
                const result = await response.json();
                if (result.status === 'success') {
                    window.location.href = '/review/' + shopId;
                }
            } catch (err) {
                console.error('通信エラー', err);
            }
        });
    });
});

//口コミ編集フォーム表示
window.openEditReview = function (reviewId) {
    const overlay = document.getElementById('overlay-edit' + reviewId);
    const editForm = document.getElementById('edit' + reviewId);
    const isAuth = editForm.dataset.isAuth === '1';

    if (!isAuth) {
        window.location.href = '/login?message=login_required';
        return;
    }

    overlay.style.display = 'flex';
    editForm.style.display = 'block';
}

//口コミ編集フォーム非表示
window.closeEditReview = function (reviewId) {
    const overlay = document.getElementById('overlay-edit' + reviewId);
    const editForm = document.getElementById('edit' + reviewId);
    overlay.style.display = 'none';
    editForm.style.display = 'none';
    location.reload();
}

//評価・コメント更新
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    document.querySelectorAll('.update-review-form').forEach(form => {
        const stars = form.querySelectorAll('.update-star');
        const evaluationInput = form.querySelector('.evaluation-input');
        const comment = form.querySelector('.comment');
        const evaluationAlert = form.querySelector('.evaluation-alert');
        const commentAlert = form.querySelector('.comment-alert');
        const submit = form.querySelector('.update-btn');
        const shopId = form.dataset.shopId;
        const reviewId = form.querySelector('input[name="id"]').value;

        //星の初期表示
        const initialValue = parseInt(evaluationInput.value);
        stars.forEach((s, i) => {
            s.classList.toggle('filled', i < initialValue);
        });

        //星のクリック
        stars.forEach(star => {
            star.addEventListener('click', () => {
                const value = parseInt(star.dataset.value);
                evaluationInput.value = value;

                stars.forEach((s, i) => {
                    s.classList.toggle('filled', i < value);
                });
            });
        });

        //更新ボタン
        submit.addEventListener('click', async () => {
            const evaluationValue = parseInt(evaluationInput.value);
            const commentValue = comment.value;

            if (evaluationValue === 0) {
                evaluationAlert.textContent = '評価を入力してください';
                return;
            }

            if (commentValue.length > 500) {
                commentAlert.textContent = 'コメントは500文字以内で入力してください';
                return;
            }

            //更新処理
            try {
                const response = await fetch('/review/update', {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: reviewId,
                        evaluation: evaluationValue,
                        comment: commentValue,
                    }),
                });
                const result = await response.json();
                if (result.status === 'success') {
                    window.location.href = '/review/' + shopId;
                }
            } catch (err) {
                console.error('通信エラー', err);
            }
        });
    });
});