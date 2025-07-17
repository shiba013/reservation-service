//時間帯を選んだら人数セレクトを更新する関数を追加
function setupDynamicNumberSelect(reservationId) {
    const timeSelect = document.querySelector(`#edit-form${reservationId} select[name='time']`);
    const numberSelect = document.querySelector(`#edit-form${reservationId} select[name='number']`);

    if (timeSelect && numberSelect) {
        timeSelect.addEventListener('change', () => {
            const selectedOption = timeSelect.options[timeSelect.selectedIndex];
            const remaining = selectedOption.dataset.remaining;

            numberSelect.innerHTML = '<option value="" hidden>人数を選択してください</option>';

            if (remaining) {
                const max = parseInt(remaining, 10);
                for (let i = 1; i <= max; i++) {
                    const option = document.createElement('option');
                    option.value = i;
                    option.textContent = `${i}人`;
                    numberSelect.appendChild(option);
                }
            }
        });
    }
}

//予約変更フォーム表示
window.openEditForm = function (reservationId) {
    const overlay = document.getElementById('overlay-edit' + reservationId);
    const editForm = document.getElementById('edit-form' + reservationId);
    overlay.style.display = 'flex';
    editForm.style.display = 'block';

    setupDynamicNumberSelect(reservationId);
}

//予約変更フォーム非表示
window.closeEditForm = function (reservationId) {
    const overlay = document.getElementById('overlay-edit' + reservationId);
    const editForm = document.getElementById('edit-form' + reservationId);
    overlay.style.display = 'none';
    editForm.style.display = 'none';

    const errors = ['date', 'time', 'number'];
    errors.forEach(function (field) {
        const element = document.getElementById(`error-${field}-${reservationId}`);
        if (element) {
            element.style.display = 'none';
        }
    });
    location.reload();
}

//予約更新フォーム表示
window.openUpdateForm = function (reservationId) {
    //closeEditForm(reservationId);

    const date = document.querySelector(`#edit-form${reservationId} input[name='date']`).value;
    const time = document.querySelector(`#edit-form${reservationId} select[name='time']`);
    const timeText = time.options[time.selectedIndex].text;
    const number = document.querySelector(`#edit-form${reservationId} select[name='number']`);
    const numberText = number.options[number.selectedIndex].text;

    const timeValue = time.value;
    const timeWithSeconds = timeValue.length === 5 ? timeValue + ':00' : timeValue;
    const numberValue = number.value;

    document.getElementById(`confirm-date${reservationId}`).innerText = date;
    document.getElementById(`confirm-time${reservationId}`).innerText = timeText;
    document.getElementById(`confirm-number${reservationId}`).innerText = numberText;

    document.getElementById(`update-date${reservationId}`).value = date;
    document.getElementById(`update-time${reservationId}`).value = timeWithSeconds;
    document.getElementById(`update-number${reservationId}`).value = numberValue;

    const overlay = document.getElementById('overlay-update' + reservationId);
    const updateForm = document.getElementById('update-form' + reservationId);
    overlay.style.display = 'flex';
    updateForm.style.display = 'block'
}

//予約更新フォーム非表示
window.closeUpdateForm = function (reservationId) {
    const overlay = document.getElementById('overlay-update' + reservationId);
    const updateForm = document.getElementById('update-form' + reservationId);
    overlay.style.display = 'none';
    updateForm.style.display = 'none'
}

//予約削除フォーム表示
window.openDeleteForm = function (reservationId) {
    const overlay = document.getElementById('overlay-delete' + reservationId);
    const deleteForm = document.getElementById('delete-form' + reservationId);
    overlay.style.display = 'flex';
    deleteForm.style.display = 'block';
}

//予約削除フォーム非表示
window.closeDeleteForm = function (reservationId) {
    const overlay = document.getElementById('overlay-delete' + reservationId);
    const deleteForm = document.getElementById('delete-form' + reservationId);
    overlay.style.display = 'none';
    deleteForm.style.display = 'none';
}