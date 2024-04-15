document.querySelector('#menu-opener').addEventListener('click', function () {
    this.classList.toggle('active');
    document.querySelector('#main-menu').classList.toggle('active');
});

document.querySelector('#login-btn').addEventListener('click', function () {
    document.querySelector("#login-modal").classList.add('active');
});

document.querySelector('#login-modal .modal-overlay').addEventListener('click', function () {
    document.querySelector("#login-modal").classList.remove('active');
})