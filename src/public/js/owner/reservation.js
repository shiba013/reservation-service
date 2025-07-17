//店舗代表者予約更新フォーム表示
window.openOwnerUpdateForm = function (reservationId) {
    const date = document.querySelector(`#update-form${reservationId} input[name='date']`).value;
    const time = document.querySelector(`#update-form${reservationId} input[name='time']`).value;
    const number = document.querySelector(`#update-form${reservationId} input[name='number']`).value;

    document.getElementById(`update-date${reservationId}`).value = date;
    document.getElementById(`update-time${reservationId}`).value = time;
    document.getElementById(`update-number${reservationId}`).value = number;

    const overlay = document.getElementById('overlay-update' + reservationId);
    const updateForm = document.getElementById('update-form' + reservationId);
    overlay.style.display = 'flex';
    updateForm.style.display = 'block';
}

//店舗代表者予約更新フォーム非表示
window.closeOwnerUpdateForm = function (reservationId) {
    const overlay = document.getElementById('overlay-update' + reservationId);
    const updateForm = document.getElementById('update-form' + reservationId);
    overlay.style.display = 'none';
    updateForm.style.display = 'none';

    const errors = ['date', 'time', 'number'];
    errors.forEach(function (field) {
        const element = document.getElementById(`error-${field}-${reservationId}`);
        if (element) {
            element.style.display = 'none';
        }
    });
    location.reload();
}

//店舗代表者予約削除フォーム表示
window.openOwnerDeleteForm = function (reservationId) {
    const overlay = document.getElementById('overlay-delete' + reservationId);
    const deleteForm = document.getElementById('delete-form' + reservationId);
    overlay.style.display = 'flex';
    deleteForm.style.display = 'block';
}

//店舗代表者予約削除フォーム非表示
window.closeOwnerDeleteForm = function (reservationId) {
    const overlay = document.getElementById('overlay-delete' + reservationId);
    const deleteForm = document.getElementById('delete-form' + reservationId);
    overlay.style.display = 'none';
    deleteForm.style.display = 'none';
}

//店舗代表者予約受付時間設定フォーム表示
window.openSettingForm = function () {
    const overlay = document.getElementById('overlay-setting');
    const settingForm = document.getElementById('setting-form');
    overlay.style.display = 'flex';
    settingForm.style.display = 'block';
}

//店舗代表者予約受付時間設定フォーム非表示
window.closeSettingForm = function () {
    const overlay = document.getElementById('overlay-setting');
    const settingForm = document.getElementById('setting-form');
    overlay.style.display = 'none';
    settingForm.style.display = 'none';
}

//店舗代表者予約停止フォーム表示
window.openStopForm = function (shopId) {
    const overlay = document.getElementById('overlay-stop' + shopId);
    const stopForm = document.getElementById('stop-form' + shopId);
    overlay.style.display = 'flex';
    stopForm.style.display = 'block';
}

//店舗代表者予約停止フォーム非表示
window.closeStopForm = function (shopId) {
    const overlay = document.getElementById('overlay-stop' + shopId);
    const stopForm = document.getElementById('stop-form' + shopId);
    overlay.style.display = 'none';
    stopForm.style.display = 'none';
}
