# Panduan Deploy Laravel ke cPanel via Git Version Control

Panduan ini untuk cPanel yang memiliki fitur **Git Version Control** (tanpa SSH/terminal).

> **Catatan:** Metode ini tetap membutuhkan struktur folder `bakery/` + `public_html/` yang sudah dijelaskan di `DEPLOY_CPANEL.md`. Pastikan file `public_html/index.php` sudah dimodifikasi path-nya ke `../bakery/...` sebelum deploy.

---

## Prasyarat

- Repo GitHub sudah berisi semua file projek (termasuk `vendor/`, `node_modules/`, dan `.env` bisa di-ignore)
- Struktur folder di repo: normal Laravel (bukan struktur `bakery/` + `public_html/`)
- File `public/index.php` original (belum dimodifikasi) — modifikasi dilakukan **di server** via File Manager

---

## Langkah-langkah

### 1. Clone Repo via Git Version Control

- Login ke cPanel
- Cari menu **Git Version Control**
- Klik **Create**
- Isi:

| Field | Isi |
|-------|-----|
| Repository name | `bakery` |
| Clone URL | `https://github.com/ardhikaxx/toko-roti.git` |
| Repository path | `/home/username/bakery` |
| Clone Automatically | Centang |

- Klik **Create**

Proses clone akan berjalan. Hasilnya: seluruh projek berada di `/home/username/bakery/`.

### 2. Setup Deployment

Setelah clone selesai, klik **Manage** di samping repository `bakery`.

#### a. Buat Deployment Directory untuk `public_html/`

Masih di halaman Manage, cari **Deployment** (atau **Deploy Head Commit**):

| Field | Isi |
|-------|-----|
| Deployment path | `/home/username/public_html` |
| Deployment action | **Files and folders** |
| Files/folders to include | `public/` |

> **Penjelasan:** Ini akan meng-ekstrak isi folder `public/` dari repo ke `/home/username/public_html/` setiap kali deploy.

Klik **Create Deployment** atau **Save**.

#### b. Jalankan Deploy Pertama

- Klik **Deploy Head Commit** (atau **Deploy Now**)
- Tunggu proses selesai

#### c. Modifikasi `public_html/index.php`

Karena hasil deploy hanya menyalin folder `public/`, file `public_html/index.php` masih berisi path `__DIR__.'/../...` yang mengarah ke folder parent dari `public_html/` (bukan ke `bakery/`).

Buka **File Manager** → `/home/username/public_html/index.php` dan ubah semua path:

```php
// BEFORE (original)
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// AFTER (diubah)
require __DIR__.'/../bakery/vendor/autoload.php';
$app = require_once __DIR__.'/../bakery/bootstrap/app.php';
```

Lihat `DEPLOY_CPANEL.md` → bagian **Modifikasi File Index** untuk file `index.php` yang sudah lengkap.

### 3. Setting `.env` & Permissions

- Copy/sesuaikan file `.env` di `/home/username/bakery/.env` (lihat panduan di `DEPLOY_CPANEL.md`)
- Set permission `bakery/storage/` dan `bakery/bootstrap/cache/` ke 755

### 4. Import Database

Import database via phpMyAdmin seperti biasa.

---

## Update Setelah Ada Perubahan Baru di GitHub

Setiap kali kamu push perubahan ke GitHub, lakukan ini di cPanel:

1. Buka **Git Version Control**
2. Klik **Manage** di repository `bakery`
3. Di tab **Pull or Fetch**, klik **Fetch & Pull** (atau **Update**)
4. Klik **Deploy Head Commit** atau **Deploy Now**
5. Path: `/home/username/public_html`
6. Selesai

> **Catatan:** Perubahan pada file di luar folder `public/` (misal `app/`, `config/`, `routes/`) otomatis ter-update di `bakery/` setelah Pull. Perubahan pada folder `public/` akan otomatis tersalin ke `public_html/` setelah Deploy.

---

## Kelebihan & Kekurangan

### Kelebihan

- Tidak perlu upload ZIP manual setiap ada update
- Riwayat commit terlihat di cPanel
- Proses update lebih cepat (tinggal klik Pull + Deploy)
- Cocok untuk tim yang push ke repo yang sama

### Kekurangan

- File `public_html/index.php` harus dimodifikasi manual **setiap kali deploy** jika ada update dari repo yang mengubah `public/index.php`
- Tidak bisa menjalankan `php artisan ...` di server — cache harus disiapkan dari lokal
- Jika ada file baru di folder `public/` (misal gambar, CSS baru), path di index.php tetap aman selama tidak ada perubahan struktural
- Masih perlu setting `.env` & permission manual sekali di awal

---

## Tips

### Agar `public_html/index.php` tidak perlu diedit ulang tiap deploy

Setelah deploy pertama, backup file `public_html/index.php` yang sudah dimodifikasi ke tempat aman. Setiap kali deploy, cek apakah `public/index.php` di repo berubah:

- Jika **tidak berubah**: deploy langsung tanpa perlu edit ulang
- Jika **berubah**: upload ulang backup `index.php` yang sudah dimodifikasi ke `public_html/`

Atau, buat file hook/custom script di sisi GitHub (GitHub Actions) untuk otomatis mengganti path sebelum push — tapi ini butuh setup CI tambahan.
