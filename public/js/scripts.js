document.querySelector('#menu-opener').addEventListener('click', function () {
    this.classList.toggle('active');
    document.querySelector('#main-menu').classList.toggle('active');
    // document.querySelector('html').style.overflow = 'hidden';
});

document.querySelectorAll('.open-modal').forEach(elem => {
    elem.addEventListener('click', function() {
        document.querySelectorAll('.modal.active').forEach(activeModal => activeModal.classList.remove('active'));
        const modalId = this.dataset.modal;
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('active');
            const overlay = modal.querySelector('.modal-overlay');
            if (overlay) {
                overlay.addEventListener('click', () => modal.classList.remove('active'));
            }
        }
    });
});