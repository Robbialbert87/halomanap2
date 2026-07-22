# Setup SSL & Domain

## Pilih Metode

| Metode | Cocok | Tingkat Kesulitan |
|--------|-------|-------------------|
| [A. Let's Encrypt (Certbot)](#a-lets-encrypt-certbot) | VPS / internet | Sedang |
| [B. Nginx Proxy Manager](#b-nginx-proxy-manager) | Suka GUI | Mudah |
| [C. Cloudflare Proxy](#c-cloudflare-proxy) | Domain di Cloudflare | Mudah |
| [D. Self-Signed](#d-self-signed) | Internal / testing | Mudah |

---

## A. Let's Encrypt (Certbot) — Recommended

### Prasyarat

- Domain sudah pointing ke IP server (A record)
- Port 80 dan 443 terbuka
- Server bisa diakses dari internet

### Langkah

#### 1. Dapatkan Sertifikat

Stop Nginx container sementara:

```bash
docker compose stop nginx
```

Install certbot di **host**:

```bash
# Ubuntu/Debian
sudo apt install certbot -y

# Dapatkan sertifikat (standalone mode)
sudo certbot certonly --standalone -d domainanda.com -d www.domainanda.com
```

Sertifikat akan tersimpan di `/etc/letsencrypt/live/domainanda.com/`.

#### 2. Copy Sertifikat ke Project

```bash
# Buat folder (jika belum ada)
mkdir -p docker/ssl

# Copy sertifikat
sudo cp /etc/letsencrypt/live/domainanda.com/fullchain.pem docker/ssl/
sudo cp /etc/letsencrypt/live/domainanda.com/privkey.pem docker/ssl/

# Copy options SSL nginx dari certbot
sudo cp /etc/letsencrypt/options-ssl-nginx.conf docker/ssl/

# Ubah ownership (docker user)
sudo chown -R $USER:$USER docker/ssl/
chmod 600 docker/ssl/privkey.pem
```

#### 3. Buat Konfigurasi Nginx SSL

Buat file `docker/nginx/default-ssl.conf`:

```nginx
upstream php-backend {
    server app:9000;
}

server {
    listen 80;
    server_name domainanda.com www.domainanda.com;

    location /.well-known/acme-challenge/ {
        root /var/www/html/public;
    }

    location / {
        return 301 https://$host$request_uri;
    }
}

server {
    listen 443 ssl http2;
    server_name domainanda.com www.domainanda.com;

    ssl_certificate /etc/nginx/ssl/fullchain.pem;
    ssl_certificate_key /etc/nginx/ssl/privkey.pem;
    ssl_trusted_certificate /etc/nginx/ssl/fullchain.pem;

    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers on;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;

    root /var/www/html/public;
    index index.php;

    charset utf-8;
    client_max_body_size 50m;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico {
        access_log off;
        log_not_found off;
    }

    location = /robots.txt {
        access_log off;
        log_not_found off;
    }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass php-backend;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
        fastcgi_param SERVER_NAME $host;
        fastcgi_param HTTPS on;
        fastcgi_read_timeout 600;
        fastcgi_buffers 16 16k;
        fastcgi_buffer_size 32k;
    }

    location ^~ /livewire {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~* \.(css|js|jpg|jpeg|gif|png|ico|svg|woff2?|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
        log_not_found off;
    }

    location ~ /\.(?!well-known) {
        deny all;
    }

    location ~* (?:\.(?:bak|conf|dist|fla|in[ci]|log|psd|sh|sql|sw[op])|~)$ {
        deny all;
    }

    access_log /dev/stdout;
    error_log /dev/stderr;
}
```

#### 4. Aktifkan SSL

Start ulang dengan override production:

```bash
docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d
```

#### 5. Auto-Renew (Cron Job)

Sertifikat Let's Encrypt berlaku 90 hari. Set auto-renew:

```bash
sudo crontab -e
```

Tambahkan baris:

```cron
0 3 * * * certbot renew --quiet && docker compose -f /path/to/halomanap2/docker-compose.yml exec nginx nginx -s reload 2>&1 | logger -t certbot
```

---

## B. Nginx Proxy Manager

### Cara

1. Jalankan Nginx Proxy Manager sebagai container terpisah:

```bash
docker run -d \
  --name nginx-proxy-manager \
  -p 80:80 -p 443:443 -p 8091:81 \
  -v npm_data:/data \
  jc21/nginx-proxy-manager:latest
```

2. Buka `http://IP_SERVER:8091`, login default `admin@example.com` / `changeme`

3. Tambahkan **Proxy Host**:
   - Domain: `domainanda.com`
   - Forward IP: `halomanap-nginx`
   - Forward Port: `80`
   - ✅ Cache Assets
   - ✅ Block Common Exploits

4. SSL tab → Request Let's Encrypt → isi email

### Kelebihan

- GUI untuk manage domain & SSL
- Support multi-site
- Auto-renew SSL

---

## C. Cloudflare Proxy

### Cara

1. Di Cloudflare Dashboard → DNS → tambahkan A record pointing ke IP server (proxy ✅)

2. Di Cloudflare → SSL/TLS → pilih **Full (strict)**

3. Di server, gunakan **Origin Server Certificate** dari Cloudflare:

```bash
# Cloudflare Dashboard → SSL/TLS → Origin Server → Create Certificate
# Simpan certificate.pem dan private.key ke docker/ssl/
```

4. Atau gunakan `docker-compose.prod.yml` seperti metode A, tapi dengan cert dari Cloudflare

### Kelebihan

- DDoS protection
- Caching statis via CDN
- SSL gratis tanpa perlu install certbot

---

## D. Self-Signed Certificate

### Untuk testing internal / intranet

```bash
# Generate self-signed certificate
mkdir -p docker/ssl
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout docker/ssl/privkey.pem \
  -out docker/ssl/fullchain.pem \
  -subj "/C=ID/ST=Jambi/L=Jambi/O=HaloMANAP/CN=halomanap.local"
```

Kemudian aktifkan override SSL seperti metode A. Browser akan menampilkan warning, tetapi koneksi tetap terenkripsi.
