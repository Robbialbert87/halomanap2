# Inventory & Audit — Produksi (minipacs / 103.147.236.138)

> Audit dilakukan **read-only** (2026-08-02). Dokumen = sumber kebenaran kondisi
> server saat ini, berguna juga untuk recovery & migrasi.

## 1. Server

| Item | Nilai |
|------|-------|
| Host | minipacs (`103.147.236.138`) |
| User deploy | `mini_pacs` |
| Root proyek | `/home/mini_pacs/projects/` |
| Repo | `https://github.com/Robbialbert87/halomanap2` (branch `docker`) |

## 2. Stack Docker (3 project)

| Stack | Project dir | Compose | Status |
|-------|-------------|---------|--------|
| halo-manap | `~/projects/halo-manap` | `docker-compose.yml` (HILANG di disk, regen: `docker/docker-compose.production.yml`) | running(4) |
| ttd_online | `~/projects/ttd_online` | `docker-compose.prod.yml` | running(2) |
| waha | `docker-compose.yaml` | — | running(1) |

Stack **halo-manap** berisi 4 container (`docker compose ls`):

| Container | Image | Port | Peran |
|-----------|-------|------|-------|
| `halomanap-app` | `halo-manap-app` | 9000/tcp | Laravel 13.8 (PHP 8.4-fpm) |
| `halomanap-queue` | `halo-manap-queue` | 9000/tcp | `queue:work --tries=3` |
| `halomanap-db` | `mariadb:11.8` | 3306/tcp | MariaDB |
| `halomanap-nginx` | `nginx:1.27-alpine` | `8080→80`, `8443→443` | Reverse proxy |

**Container terkait lintas project (network `halo-manap_default`)**:
`ttd-verifier` (`8000`), `ttd-admin` (`8000`), `halomanap-{app,nginx,queue,db}` — semua join network `halo-manap_default`.

## 3. Jaringan (network)

- `halo-manap_default` (bridge): host semua service internal; `ttd-admin` & `ttd-verifier` join network ini via probe.
- `waha_default` (bridge): stack WAHA.
- `bridge` default; `dcm4chee_default` (proyek lain); `ingress` (swarm leftover).

## 4. Volume & Mount

**Volume named** (`docker volume ls`):
- `halo-manap_db_data` → `/var/lib/mysql` (MariaDB, **data hidup — JANGAN sentuh**)

**Mount penting (nginx)**:
```
~/projects/halo-manap/devpanel            → /var/www/devpanel
~/projects/halo-manap/public              → /var/www/html/public
~/projects/halo-manap/storage             → /var/www/html/storage
~/projects/halo-manap/docker/nginx/conf.d  → /etc/nginx/conf.d        (config proxy)
~/projects/halo-manap/docker/nginx/nginx.conf → /etc/nginx/nginx.conf
~/projects/halo-manap/ssl-cert            → /etc/nginx/ssl
~/projects/halo-manap/ssl-cert/www        → /var/www/acme
```
**app**: `.env`, `storage`, `public/uploads`, `php.ini` (semua `:ro` kecuali storage/uploads).

## 5. Git status

| Item | Nilai |
|------|-------|
| Branch aktif | `docker` |
| HEAD | `cb932b9` |
| vs `origin/docker` | **behind 47 commit** |
| Mainline | `docker` (ahead 71 dari `main`) |
| Working tree | **255 file beda** (209 konten; app/resources nyata) |
| Stash | `stash@{0}` WIP — **diff config live prod** (nginx mount + conf.d) |

## 6. Konfigurasi server-spesifik (JANGAN commit)

| File | Status |
|------|--------|
| `.env` | hidup; sumber konfig secret (APP_KEY, DB_pass, WAHA) |
| `docker/nginx/conf.d/*.conf` | hidup; `default/ dev.conf` memuat blok `/ttd/`, `/ttd-admin/`, `auth_basic` |
| `.htpasswd` | kredensial Basic Auth `/ttd-admin/` |
| `ssl-cert/` | sertifikat |
| `devpanel/` | halaman statis |
| `docker-compose.yml` | **hilang** — dijaddi production dari inspect |

## 7. Data

| Store | Lokasi | Status backup |
|-------|--------|---------------|
| MariaDB | volume `halo-manap_db_data` | ❌ belum ada |
| SQLite TTD | `~/projects/ttd_online/data/database/signatures.db` | ❌ belum |
| uploads | `public/uploads` | ❌ belum |
| storage/ | `/storage` | ❌ belum |

## 8. Masalah terbuka (berdasarkan audit)

1. `docker-compose.yml` utama **hilang** → jalur recover `docker/docker-compose.production.yml`.
2. **Permission `storage/` milik root** → `git pull` gagal unlink `.gitignore`.
3. **Working tree 47-commit dirty** → snapshot dulu sebelum pull.
4. **Source di-bake image** → perubahan host harus `docker compose build`, bukan hanya pull.
5. **reverse proxy digabung dalam `halo-manap`** → target refactor: pisah ke `infrastructure/reverse-proxy/`.