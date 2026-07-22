## Objective
- Menyamakan seluruh tampilan dengan gaya PayApp (glossy glassmorphism, compact, font Source Sans Pro + Roboto) untuk semua halaman admin dan publik.
- Menambahkan fitur download tiket PDF profesional setelah user membuat laporan.

## Important Details
- **Font:** Source Sans Pro (heading via `font-heading`) + Roboto (body via `font-sans`) di `layouts/app.blade.php`, ditambah CSS global `h1–h6 { font-family: 'Source Sans Pro' }`
- **Desktop** sudah di-PayApp-kan (homepage).
- **Notifikasi:** Kolom `notification_seen_at` di tabel tickets. View Composer hanya menghitung tiket `status = 'NEW' AND notification_seen_at IS NULL`.
- **Live search HP sudah dihapus** — kembali ke form submit biasa.
- **`border-white/50`** diganti `border-gray-200` di semua form input.
- **Custom error pages:** 7 halaman (401, 403, 404, 419, 429, 500, 503) dengan PayApp glassmorphism.
- **Download Tiket PDF:** Menggunakan `barryvdh/laravel-dompdf`, menghasilkan tiket profesional dengan desain seperti tiket fisik (header gradient, nomor tiket besar, detail lengkap, barcode visual, stub dengan track URL).

## Work State
### Completed
- **Font & Layout:** Tailwind config, CSS global, semua heading otomatis pakai Source Sans Pro.
- **9 halaman admin di-PayApp-kan:** Dispositions, Roles, Users, Jabatans, Units, Rooms, Categories, Unit-Types, Monitoring.
- **Homepage desktop & mobile:** Keduanya sudah PayApp glossy glassmorphism.
- **Live search HP:** Dihapus (revert).
- **Border fix:** `border-white/50` → `border-gray-200` di semua form input.
- **Custom error pages:** 7 halaman (401, 403, 404, 419, 429, 500, 503).
- **Notifikasi fix:** Migrasi + View Composer filter.
- **Pengaduan create page:** Restyle PayApp penuh.
- **Pagination PayApp:** `vendor/pagination/tailwind.blade.php`.
- **Download Tiket PDF:** 
  - Library `barryvdh/laravel-dompdf` terinstall.
  - Route `GET /pengaduan/tiket/{ticket_number}/download` → `PengaduanController@downloadTicket`.
  - View `resources/views/pengaduan/ticket-pdf.blade.php` — desain tiket profesional dengan header gradient biru, nomor tiket besar (dashed box), detail lengkap (tanggal, status, unit, kategori, pelapor), stub berlubang dengan barcode visual dan link lacak.
  - Tombol download di halaman sukses (`pengaduan/sukses/{ticket}`).
  - Tombol download di halaman lacak status (`/lacak`).
- **Push:** 6+ commit sudah di-push ke `main`.

### Active
- *(none)*

### Blocked
- *(none)*

## Relevant Files
- `resources/views/pengaduan/ticket-pdf.blade.php`: Template PDF tiket profesional
- `resources/views/pengaduan/success.blade.php`: Halaman sukses + tombol download tiket + copy tiket
- `resources/views/pengaduan/track.blade.php`: Halaman lacak + tombol download tiket
- `app/Http/Controllers/PengaduanController.php`: Method `downloadTicket()` + import PDF facade
- `routes/web.php`: Route `pengaduan.ticket-download`
- `composer.json`: `barryvdh/laravel-dompdf` added
- `resources/views/home.blade.php`: Homepage PayApp desktop + mobile
- `resources/views/layouts/app.blade.php`: Font & CSS global
- `resources/views/admin/*`: 9 halaman admin PayApp
- `resources/views/errors/*.blade.php`: 7 custom error pages
- `resources/views/pengaduan/create.blade.php`: Form pengaduan PayApp
