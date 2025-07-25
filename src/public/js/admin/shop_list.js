window.openShopDelete = function (shopId) {
    const overlay = document.getElementById('overlay-delete' + shopId);
    const deleteForm = document.getElementById('delete-form' + shopId);

    overlay.style.display = 'flex';
    deleteForm.style.display = 'block';
}

window.closeShopDelete = function (shopId) {
    const overlay = document.getElementById('overlay-delete' + shopId);
    const deleteForm = document.getElementById('delete-form' + shopId);

    overlay.style.display = 'none';
    deleteForm.style.display = 'none';
}