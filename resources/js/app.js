

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

function ensureToastContainer() {
    let el = document.getElementById('toast-container');
    if (!el) {
        el = document.createElement('div');
        el.id = 'toast-container';
        el.className = 'toast-container';
        document.body.appendChild(el);
    }
    return el;
}

window.tafToast = function (type, message) {
    const container = ensureToastContainer();
    container.querySelectorAll('.toast').forEach(t => t.remove()); // 1 toast dalam satu waktu

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `<span></span><span class="toast-msg"></span>`;
    toast.querySelector('.toast-msg').textContent = message;
    container.appendChild(toast);

    requestAnimationFrame(() => toast.classList.add('show'));
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 4000);
};