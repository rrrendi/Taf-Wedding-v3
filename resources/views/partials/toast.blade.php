{{--
    Partial global untuk notifikasi toast (window.tafToast).

    KENAPA INI DIPERLUKAN:
    resources/js/app.js berisi definisi window.tafToast, tapi file itu TIDAK PERNAH
    dimuat lewat @vite di layout manapun (public/admin/app semuanya memuat Alpine.js
    langsung dari CDN). Akibatnya window.tafToast selalu undefined di seluruh halaman,
    sehingga setiap pemanggilan window.tafToast(...) — termasuk peringatan "Lanjut" pada
    form pemesanan step-by-step — gagal diam-diam (silent error): tidak ada notifikasi
    yang muncul, DAN langkah tidak berpindah karena baris kode setelah pemanggilan yang
    gagal tersebut tidak sempat dieksekusi.

    Partial ini mendefinisikan window.tafToast secara mandiri (tanpa bergantung pada
    build Vite), lalu di-include di layouts/public.blade.php, layouts/admin.blade.php,
    dan layouts/app.blade.php — persis sebelum script lain yang memanggilnya.
--}}
<script>
    (function () {
        if (window.tafToast) return; // sudah pernah didefinisikan, jangan timpa.

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
            toast.innerHTML = `<span class="toast-msg"></span>`;
            toast.querySelector('.toast-msg').textContent = message;
            container.appendChild(toast);

            requestAnimationFrame(() => toast.classList.add('show'));
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        };
    })();
</script>
