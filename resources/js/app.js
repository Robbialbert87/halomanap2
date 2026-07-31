/**
 * Toast system — pengganti flash banner yang dipakai bertahap per halaman.
 * API: window.toast(message, type)  |  type: success | error | warning | info
 * Panggil dari mana saja (termasuk hasil live filter via fetch).
 */

window.toast = function (message, type = 'success', duration = 4200) {
    const container = document.getElementById('admin-toast-container');
    if (!container) return;

    const icons = { success: 'fa-circle-check', error: 'fa-circle-xmark', warning: 'fa-triangle-exclamation', info: 'fa-circle-info' };

    const el = document.createElement('div');
    el.className = 'admin-toast admin-toast-' + type + ' admin-rise';
    el.setAttribute('role', 'alert');
    el.innerHTML =
        '<i class="fa-solid ' + (icons[type] || icons.info) + '"></i>' +
        '<span>' + message + '</span>' +
        '<button type="button" class="admin-toast-close" aria-label="Tutup">' +
        '<i class="fa-solid fa-xmark"></i></button>';

    el.querySelector('.admin-toast-close').addEventListener('click', function () {
        dismiss(el);
    });

    container.appendChild(el);

    const timer = setTimeout(function () { dismiss(el); }, duration);
    el.addEventListener('mouseenter', function () { clearTimeout(timer); });
    el.addEventListener('mouseleave', function () { setTimeout(function () { dismiss(el); }, 1500); });

    // Batasi tumpukan toast maksimal 4, buang yang paling lama
    while (container.children.length > 4) {
        container.removeChild(container.firstChild);
    }
};

function dismiss(el) {
    el.style.opacity = '0';
    el.style.transform = 'translateY(-8px)';
    el.style.transition = 'opacity .25s ease, transform .25s ease';
    setTimeout(function () { el.remove(); }, 260);
}

/* Skeleton helper untuk tabel live filter (menjaga layout saat loading) */
window.adminSkeletonRows = function (cols, rows = 5) {
    let html = '';
    for (let r = 0; r < rows; r++) {
        html += '<tr>';
        for (let c = 0; c < cols; c++) {
            html += '<td><span class="admin-skeleton" style="display:block;height:.875rem;width:' + (70 + ((r * 7 + c * 13) % 30)) + '%"></span></td>';
        }
        html += '</tr>';
    }
    return html;
};
