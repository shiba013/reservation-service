//予約変更フォーム表示
window.openEditForm = function (reservationId) {
    const overlay = document.getElementById('overlay-edit' + reservationId);
    const editForm = document.getElementById('edit-form' + reservationId);
    overlay.style.display = 'flex';
    editForm.style.display = 'block';

    //フォーム定義
    const inputDate = editForm.querySelector('input[name="date"]');
    const selectTime = editForm.querySelector('select[name="time"]');
    const selectNumber = editForm.querySelector('select[name="number"]');

    inputDate.innerHTML = '';
    selectTime.style.display = 'none';
    selectNumber.style.display = 'none';

    //日付選択
    inputDate.addEventListener('change', async function () {
        const selectedDate = this.value;

        //初期化設定
        selectTime.style.display = 'block';
        selectTime.innerHTML = '<option value="">時間を選択してください</option>';
        selectNumber.innerHTML = '';
        if (!selectedDate) return;

        //選択日付の空き時間取得
        const response = await fetch(`/mypage/slots/${reservationId}?date=${selectedDate}`, {
            method: 'GET',
            credentials: 'include',
            headers: {
                'Accept': 'application/json',
            },
        });
        const data = await response.json();

        //予約枠の上限に達した場合
        if (data.length === 0 || data.every(slot => slot.remaining_number <= 0)) {
            selectTime.innerHTML = '<option value="">満席です</option>';
            return;
        }

        //時間スロット作成
        data.forEach(slot => {
            if (slot.remaining_number > 0) {
                const option = document.createElement('option');
                option.value = slot.time;
                option.textContent = `${slot.time}`;
                option.dataset.remaining = slot.remaining_number;
                selectTime.appendChild(option);
            }
        });
    });

    //時間選択
    selectTime.addEventListener('change', async function () {
        const selectedTime = this.value;
        selectNumber.style.display = 'block';
        selectNumber.innerHTML = '<option value="">人数を選択してください</option>';
        if (!selectedTime) return;

        const selectedOption = selectTime.options[selectTime.selectedIndex];
        const remaining = selectedOption.dataset.remaining;

        //予約枠の上限に達した場合
        if (!remaining || remaining == 0) {
            selectNumber.innerHTML = '<option value="">満席です</option>';
            return;
        }

        if (remaining) {
            const max = parseInt(remaining, 10);
            for (let i = 1; i <= max; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = `${i}人`;
                selectNumber.appendChild(option);
            }
        }
    });
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
            element.textContent = '';
        }
    });
}

//内容確認フォーム自動表示
window.addEventListener('DOMContentLoaded', function () {
    //バリデーションエラー時に予約変更フォーム表示
    if (window.reservationData.hasErrors && window.reservationData.oldReservationId) {
        openEditForm(window.reservationData.oldReservationId);
    }

    if (window.reservationData.confirmReservationId && window.reservationData.confirmData) {
        const id = window.reservationData.confirmReservationId;
        const data = window.reservationData.confirmData;
        const time = document.querySelector(`#edit-form${id} select[name='time']`);
        const number = document.querySelector(`#edit-form${id} select[name='number']`);

        document.getElementById(`update-date-${id}`).value = data.date;
        document.getElementById(`update-time-${id}`).value = data.time.length === 5 ? data.time + ':00' : data.time;
        document.getElementById(`update-number-${id}`).value = data.number;

        document.getElementById(`confirm-date-${id}`).innerText = data.date;
        document.getElementById(`confirm-time-${id}`).innerText = data.time;
        document.getElementById(`confirm-number-${id}`).innerText = data.number + '人';

        closeEditForm(id);
        openUpdateForm(id);
    }
});

window.openUpdateForm = function (reservationId) {
    const overlay = document.getElementById('overlay-update' + reservationId);
    const updateForm = document.getElementById('update-form' + reservationId);
    overlay.style.display = 'flex';
    updateForm.style.display = 'block'
}

//内容確認フォーム非表示
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