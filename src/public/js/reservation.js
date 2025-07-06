//予約変更フォーム表示
function openEditForm(reservationId) {
    const overlay = document.getElementById('overlay-edit' + reservationId);
    const editForm = document.getElementById('edit-form' + reservationId);
    overlay.style.display = 'flex';
    editForm.style.display = 'block';
}

//予約変更フォーム非表示
function closeEditForm(reservationId) {
    const overlay = document.getElementById('overlay-edit' + reservationId);
    const editForm = document.getElementById('edit-form' + reservationId);
    overlay.style.display = 'none';
    editForm.style.display = 'none';
}

//予約更新フォーム表示
function openUpdateForm(reservationId) {
    closeEditForm(reservationId);

    const date = document.querySelector(`#edit-form${reservationId} input[name='date']`).value;
    const time = document.querySelector(`#edit-form${reservationId} select[name='time']`);
    const timeText = time.options[time.selectedIndex].text;
    const number = document.querySelector(`#edit-form${reservationId} select[name='number']`);
    const numberText = number.options[number.selectedIndex].text;

    const timeValue = time.value;
    const numberValue = number.value;

    document.getElementById(`confirm-date${reservationId}`).innerText = date;
    document.getElementById(`confirm-time${reservationId}`).innerText = timeText;
    document.getElementById(`confirm-number${reservationId}`).innerText = numberText;

    document.getElementById(`update-date${reservationId}`).value = date;
    document.getElementById(`update-time${reservationId}`).value = timeValue;
    document.getElementById(`update-number${reservationId}`).value = numberValue;

    const overlay = document.getElementById('overlay-update' + reservationId);
    const updateForm = document.getElementById('update-form' + reservationId);
    overlay.style.display = 'flex';
    updateForm.style.display = 'block'
}

//予約更新フォーム非表示
function closeUpdateForm(reservationId) {
    const overlay = document.getElementById('overlay-update' + reservationId);
    const updateForm = document.getElementById('update-form' + reservationId);
    overlay.style.display = 'none';
    updateForm.style.display = 'none'
}

//予約削除フォーム表示
function openDeleteForm(reservationId) {
    const overlay = document.getElementById('overlay-delete' + reservationId);
    const deleteForm = document.getElementById('delete-form' + reservationId);
    overlay.style.display = 'flex';
    deleteForm.style.display = 'block';
}

//予約削除フォーム非表示
function closeDeleteForm(reservationId) {
    const overlay = document.getElementById('overlay-delete' + reservationId);
    const deleteForm = document.getElementById('delete-form' + reservationId);
    overlay.style.display = 'none';
    deleteForm.style.display = 'none';
}