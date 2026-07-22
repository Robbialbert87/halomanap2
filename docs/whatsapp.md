# Panduan WhatsApp API

## Arsitektur

WhatsApp API berjalan di container terpisah (`halomanap-whatsapp`) sebagai service Node.js Express menggunakan library `whatsapp-web.js` dengan Chromium (Puppeteer).

```
┌─────────────────┐     HTTP POST /send      ┌──────────────────────┐
│  Laravel App     │ ──────────────────────►  │  WhatsApp API        │
│  (queue worker)  │                          │  localhost:3000      │
│                  │ ◄──────────────────────  │  (Chromium + WA Web) │
│  SendWhatsApp    │      Response {status}   │                      │
│  Notification    │                          └──────────────────────┘
└─────────────────┘                                    │
                                                        ▼
                                                 WhatsApp Web
                                                 (QR Scan)
```

## Endpoint API

| Endpoint | Method | Deskripsi |
|----------|--------|-----------|
| `/status` | GET | Status koneksi: `isReady`, `isAuthenticated`, `qr` |
| `/send` | POST | Kirim pesan (body: `number`, `message`) |
| `/reset` | POST | Logout & reset koneksi |

## Pairing WhatsApp

### Via QR Code di Browser

Buka `http://IP_SERVER:3000/status` di browser. Halaman akan menampilkan QR code. Scan dengan WhatsApp di HP (Settings → Linked Devices → Link a Device).

### Via Terminal

```bash
# Lihat QR code dari log container
docker compose logs -f whatsapp
```

Atau:

```bash
# Filter QR code saja
docker compose logs whatsapp 2>&1 | grep -oP 'data:image/png;base64[^"]*' | head -1
```

### Verifikasi

```bash
curl -s http://localhost:3000/status
```

Response sukses:

```json
{
  "isReady": true,
  "isAuthenticated": true,
  "qr": null
}
```

---

## Test Kirim Pesan

### Via Aplikasi

Buka menu **Admin → WhatsApp → Test Kirim WA**.

### Via Curl Langsung

```bash
curl -X POST http://localhost:3000/send \
  -H "Content-Type: application/json" \
  -d '{"number":"6281234567890","message":"Halo, ini test dari Halo MANAP!"}'
```

### Via Laravel Artisan (jika ada)

```bash
docker compose exec app php artisan tinker
```

```php
dispatch(new App\Jobs\SendWhatsAppNotification(
    '6281234567890',
    'Test pesan dari artisan'
));
```

---

## Troubleshooting

### QR Code Tidak Muncul

```bash
# Restart container WhatsApp
docker compose restart whatsapp

# Cek log
docker compose logs -f whatsapp
```

### Session Expired / Logout

```bash
# Reset session via API
curl -X POST http://localhost:3000/reset

# Atau hapus volume session
docker compose down
docker volume rm halomanap2_wa_session
docker compose up -d
```

Setelah reset, scan QR code lagi.

### Connection Closed / Disconnect

WA Web session bisa disconnect otomatis jika:
- HP kehilangan koneksi internet
- WhatsApp di HP di-unlink
- Lebih dari 14 hari tidak aktif

Cukup scan QR ulang seperti langkah pairing awal.

### Container WhatsApp Crash

Cek log:

```bash
docker compose logs whatsapp
```

Penyebab umum:
- **OOM (Out of Memory)**: Chromium butuh ~500MB RAM. Tambah resource Docker.
- **Chromium update mismatch**: Rebuild image: `docker compose build --no-cache whatsapp`

### Queue Worker Tidak Memproses

Cek status:

```bash
docker compose logs queue
```

Jika ada error, pastikan table `jobs` ada di database. Jalankan ulang migrasi:

```bash
make migrate
```

---

## Keamanan

- Port 3000 sebaiknya **tidak dibuka ke publik** (hanya accessible dari internal Docker network)
- Di `docker-compose.yml`, port whatsapp hanya bind ke `127.0.0.1:3000` untuk akses dari host saja
- Untuk production: set `WHATSAPP_PORT=127.0.0.1:3000` atau jangan expose port sama sekali
