document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.stripe-link').forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault();

            const reservationId = this.dataset.id;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = this.href;

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const inputToken = document.createElement('input');
            inputToken.type = 'hidden';
            inputToken.name = '_token';
            inputToken.value = token;
            form.appendChild(inputToken);

            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = 'reservation_id';
            inputId.value = reservationId;
            form.appendChild(inputId);

            document.body.appendChild(form);
            form.submit();
        });
    });
});