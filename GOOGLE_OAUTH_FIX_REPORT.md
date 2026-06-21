# Google OAuth Fix Report

## Masalah

Google OAuth berhasil kembali dari Google, tetapi gagal saat menyimpan user karena tabel `users` belum memiliki kolom `avatar`.

Error:

```text
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'avatar' in 'field list'
```

Penyebabnya adalah migration lama `2026_02_26_000004_add_google_id_to_users_table` sudah pernah dijalankan sebelum kolom `avatar` ditambahkan. Laravel tidak menjalankan ulang migration yang sudah berstatus `Ran`, sehingga perubahan di file migration lama tidak masuk ke database existing.

## Migration Yang Dibuat

File:

```text
database/migrations/2026_06_21_000001_ensure_google_columns_on_users_table.php
```

Isi migration:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->unique()->after('email');
            }

            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('google_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'avatar')) {
                $table->dropColumn('avatar');
            }
        });
    }
};
```

Migration ini aman untuk data user lama karena hanya menambahkan kolom nullable dan tidak melakukan truncate/drop table/update massal.

## Perubahan User Model

File:

```text
app/Models/User.php
```

Field berikut sudah ada di `$fillable`:

```php
'google_id',
'avatar',
```

## Perubahan GoogleController

File:

```text
app/Http/Controllers/Auth/GoogleController.php
```

Controller sudah menyimpan dan membaca:

```php
'google_id' => $googleId,
'avatar' => $googleUser->getAvatar(),
```

Alur callback:

1. Ambil payload Google dengan `Socialite::driver('google')->user()`.
2. Cari user berdasarkan `google_id`.
3. Jika tidak ada, cari user berdasarkan `email`.
4. Jika email sudah ada, update `google_id` dan `avatar`.
5. Jika user baru, create user dengan `role = customer`, password random terenkripsi, nama, email, `google_id`, dan `avatar`.
6. Login user dan redirect ke dashboard/home customer.

## Validasi

Perintah yang dijalankan:

```bash
php artisan migrate --force
php artisan migrate:status
php -l database/migrations/2026_06_21_000001_ensure_google_columns_on_users_table.php
php -l app/Models/User.php
php -l app/Http/Controllers/Auth/GoogleController.php
```

Hasil:

```text
2026_06_21_000001_ensure_google_columns_on_users_table [2] Ran
No syntax errors detected
```

Kolom aktual di tabel `users` sudah mencakup:

```text
google_id
avatar
```
