# Deployment Guide — Halo MANAP

## Requirements

- Docker & Docker Compose (versi 27+)
- Git
- Domain (untuk production)
- Server dengan minimal 2GB RAM (karena Chromium/whatsapp)

---

## 1. Clone & Siapkan Environment

```bash
git clone <repo-url> halomanap2
cd halomanap2

# Buat .env dari template production
cp .env.production.example .env

# Edit .env — isi wajib:
#   APP_KEY         -> hasil dari: openssl rand -base64 32
#   APP_URL         -> https://domainanda.com
#   DB_PASSWORD     -> password database
#   DB_ROOT_PASSWORD -> root password database
nano .env
```

> **Catatan:** `APP_KEY` adalah base64 32-byte random. Generate dengan:
> ```bash
> docker compose run --rm app php artisan key:generate --show
> ```

---

## 2. Build & Start Semua Service

```bash
docker compose up -d --build
```

Proses ini akan:
- Build image PHP (install Composer deps, build Vite assets)
- Build image WhatsApp (install Node.js + Chromium)
- Pull MariaDB & Nginx images
- Start semua container

Cek status:

```bash
docker compose ps
make ps
```

Semua container harus `Up` dan `healthy`.

---

## 3. Setup Database & Aplikasi

```bash
# Jalankan migrasi
make migrate

# (Opsional) seed data awal
make seed

# Cache config/rute/view untuk performa
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache

# Storage link
docker compose exec app php artisan storage:link
```

---

## 4. Pairing WhatsApp

WhatsApp API berjalan di container `halomanap-whatsapp` port 3000.

```bash
# Lihat QR code (akan muncul di log)
docker compose logs -f whatsapp
```

Atau buka `http://IP_SERVER:3000/status` di browser. Scan QR code dengan WhatsApp di HP.

Cek status:

```bash
make whatsapp-status
```

Response `{"isReady": true}` berarti sudah siap.

---

## 5. Verifikasi

Buka `http://IP_SERVER` di browser. Login sebagai admin, tes fitur:
- Kirim WhatsApp test dari halaman Admin → WhatsApp → Test Kirim WA
- Cek status WhatsApp gateway

---

## 6. Setup SSL & Domain (Opsional)

Lihat panduan terpisah: [SSL & Domain](ssl-domain.md)

---

## Maintenance

### Backup Database

```bash
docker compose exec db mariadb-dump -u root -p${DB_ROOT_PASSWORD} ${DB_DATABASE} > backup-$(date +%Y%m%d).sql
```

### Lihat Log

```bash
make logs              # semua container
docker compose logs -f nginx   # nginx saja
docker compose logs -f app     # PHP saja
docker compose logs -f queue   # queue worker
docker compose logs -f whatsapp # WhatsApp API
```

### Update Aplikasi

```bash
git pull
docker compose up -d --build --no-deps app queue whatsapp
make deploy
```

### Restart Service

```bash
make restart           # semua
docker compose restart queue   # queue worker saja
```

### Hentikan Semua

```bash
make down
```

---

## Troubleshooting

| Masalah | Solusi |
|---------|--------|
| Container `app` restart terus | Cek log: `docker compose logs app`. Mungkin DB belum ready atau `.env` salah |
| WhatsApp tidak connect | Hapus volume session: `docker compose down -v wa_session && docker compose up -d` |
| Error 500 di browser | Cek log: `docker compose logs app`. Jalankan ulang `make deploy` |
| File upload gagal | Pastikan `storage/` dan `public/uploads/` writable |
