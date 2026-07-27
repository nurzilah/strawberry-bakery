# Panduan Deploy Laravel ke cPanel (Tanpa Terminal / SSH)

## Struktur Folder Target

```
/home/username/
├── bakery/               ← isi semua folder & file KECUALI public/
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env
│   ├── artisan
│   ├── composer.json
│   └── ...
└── public_html/          ← isi folder public/
    ├── index.php
    ├── .htaccess
    ├── favicon.ico
    ├── robots.txt
    ├── css/
    ├── js/
    └── ...
```

---

## 1. Persiapan di Lokal (XAMPP / PC kamu)

### 1.1 Jalankan cache artisan

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

> **Kenapa?** Karena hosting tidak punya terminal, cache harus disiapkan dari lokal agar Laravel bisa jalan tanpa `php artisan` di server.

### 1.2 Export database

```bash
mysqldump -u root -p strawberry_bakery > strawberry_bakery.sql
```

### 1.3 Dapatkan APP_KEY

```bash
php artisan key:generate --show
```

Simpan output-nya (misal: `base64:abc123...`), nanti dipakai di `.env` hosting.

---

## 2. Modifikasi File Index

Buka `public/index.php` — ubah semua path `__DIR__.'/../` menjadi `__DIR__.'/../bakery/`.

### Hasil akhir `public_html/index.php`:

```php
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../bakery/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../bakery/vendor/autoload.php';

$app = require_once __DIR__.'/../bakery/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
```

Perubahan:
| Baris | Path Lama | Path Baru |
|-------|-----------|-----------|
| Maintenance | `__DIR__.'/../storage/...'` | `__DIR__.'/../bakery/storage/...'` |
| Autoload | `__DIR__.'/../vendor/...'` | `__DIR__.'/../bakery/vendor/...'` |
| Bootstrap | `__DIR__.'/../bootstrap/...'` | `__DIR__.'/../bakery/bootstrap/...'` |

---

## 3. File `.env` untuk Hosting

Buat/sesuaikan file `.env` di folder `bakery/` dengan isi seperti ini:

```env
APP_NAME=StrawberryBakery
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domainkamu.com

LOG_CHANNEL=stack
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nama_database_cpanel
DB_USERNAME=user_database_cpanel
DB_PASSWORD=password_database_cpanel

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Isi dari hasil php artisan key:generate --show
APP_KEY=base64:xxxx...
```

> **WAJIB:** Ganti `APP_KEY` dengan hasil generate dari langkah 1.3. Ganti `DB_*` dengan credential MySQL dari cPanel.

---

## 4. File `.htaccess` (di `public_html/`)

Biarkan saja file `.htaccess` bawaan Laravel (dari folder `public/`). Tidak perlu diubah.

Tapi bisa ditambahkan baris ini di **bagian paling atas** untuk proteksi tambahan:

```apache
# Blokir akses langsung ke folder bakery
RewriteRule ^bakery(/|$) - [F,L]
```

---

## 5. Cara Upload ke cPanel

### 5.1 Buat folder `bakery`

- Login ke cPanel → **File Manager**
- Masuk ke root (`/home/username/`)
- Klik **+ Folder** → beri nama `bakery`

### 5.2 Upload ke `bakery/`

Upload folder dan file berikut ke `/home/username/bakery/`:

```
app/
bootstrap/
config/
database/
lang/
resources/
routes/
storage/
tests/
vendor/
.editorconfig
.env
.env.example
artisan
composer.json
composer.lock
package.json
tailwind.config.js
vite.config.js
webpack.mix.js
```

> **Cara cepat:** Kompres semua folder di atas jadi ZIP, upload ZIP ke `bakery/`, lalu extract.

### 5.3 Upload ke `public_html/`

Upload isi folder `public/` ke `/home/username/public_html/`:

```
index.php       ← yang SUDAH dimodifikasi (path bakery)
.htaccess
favicon.ico
robots.txt
css/
js/
build/
```

### 5.4 Setting Permission

Folder dan file ini harus **writable**:

```
chmod 755 bakery/storage
chmod 755 bakery/storage/framework
chmod 755 bakery/storage/framework/cache
chmod 755 bakery/storage/framework/sessions
chmod 755 bakery/storage/framework/views
chmod 755 bakery/storage/logs
chmod 755 bakery/bootstrap/cache
```

Ulangi `chmod 755` untuk semua subfolder di dalamnya jika perlu.

> Di File Manager cPanel: Klik kanan folder → **Change Permissions** → centang semua Write → OK.

### 5.5 Import Database

- Buka **phpMyAdmin** di cPanel
- Buat database baru (atau pakai yang sudah ada)
- Klik **Import** → pilih file `strawberry_bakery.sql` → **Go**

---

## 6. Cek & Troubleshooting

### Error "No application encryption key"

Penyebab: `APP_KEY` di `.env` kosong atau berbeda dengan yang di cache.

Solusi: Pastikan `APP_KEY` di `.env` hosting sama persis dengan hasil `php artisan key:generate --show` dari lokal. Setelah itu hapus file `bootstrap/cache/config.php` dan upload ulang file hasil `config:cache`.

### Error "Unable to write to cache"

Penyebab: Folder `storage/framework/cache/` tidak writable.

Solusi: Set permission ke 755 atau 775.

### Error 500 (Blank page)

Penyebab: Bisa karena `storage/logs/` tidak writable, atau ada error di kode.

Solusi:
1. Cek `bakery/storage/logs/laravel.log` (buka via File Manager)
2. Pastikan `APP_DEBUG=true` sementara untuk melihat error detail (setelah selesai, kembalikan ke `false`)

### File gambar upload tidak muncul

Karena project sudah pakai route `/uploads/products/{filename}` (bukan `storage:link`), pastikan folder `bakery/storage/uploads/products/` ada dan writable.

---

## 7. Ringkasan Checklist

- [ ] `php artisan config:cache` (di lokal)
- [ ] `php artisan route:cache` (di lokal)
- [ ] `php artisan view:cache` (di lokal)
- [ ] `APP_KEY` sudah dicopy ke `.env` hosting
- [ ] `.env` hosting sudah sesuai (DB, APP_URL, dll)
- [ ] Isi `public/` → `public_html/` (index.php sudah dimodifikasi)
- [ ] Sisanya → `bakery/`
- [ ] `bakery/storage/` & `bakery/bootstrap/cache/` writable
- [ ] Database sudah diimport via phpMyAdmin
- [ ] Domain sudah指向 ke `public_html/`
