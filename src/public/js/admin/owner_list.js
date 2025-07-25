window.openOwnerUpdate = function (userId) {
    const overlay = document.getElementById('overlay-update' + userId);
    const detailForm = document.getElementById('update-form' + userId);
    overlay.style.display = 'flex';
    detailForm.style.display = 'block';
}

window.closeOwnerUpdate = function (userId) {
    const overlay = document.getElementById('overlay-update' + userId);
    const detailForm = document.getElementById('update-form' + userId);
    overlay.style.display = 'none';
    detailForm.style.display = 'none';

    const errors = ['name', 'email'];
    errors.forEach(function (field) {
        const element = document.getElementById(`error-${field}-${userId}`);
        if (element) {
            element.style.display = 'none';
        }
    });
    location.reload();
}

window.openOwnerDelete = function (userId) {
    const overlay = document.getElementById('overlay-delete' + userId);
    const detailForm = document.getElementById('delete-form' + userId);
    overlay.style.display = 'flex';
    detailForm.style.display = 'block';
}

window.closeOwnerDelete = function (userId) {
    const overlay = document.getElementById('overlay-delete' + userId);
    const detailForm = document.getElementById('delete-form' + userId);
    overlay.style.display = 'none';
    detailForm.style.display = 'none';
}