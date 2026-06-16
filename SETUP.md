# 🔧 Panduan Setup K3 PLN Web — Supabase PostgreSQL

## Prasyarat
- PHP 8.2+
- Composer
- Node.js (opsional, untuk assets)
- Akun Supabase (gratis di supabase.com)

---

## 1. Install Dependencies

```bash
cd K3_PLN_Web
composer install
```

---

## 2. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` dan isi nilai berikut:

### Konfigurasi Database (Supabase PostgreSQL)

Buka **Supabase Dashboard → Project Settings → Database → Connection Info**

```
DB_CONNECTION=pgsql
DB_HOST=db.XXXXXXXXXXXXXXXXXXXX.supabase.co   ← dari "Host"
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=YOUR_PASSWORD                       ← dari "Password"
DB_SSLMODE=require
```

### Konfigurasi Supabase API

Buka **Supabase Dashboard → Project Settings → API**

```
SUPABASE_URL=https://XXXXXXXXXXXXXXXXXXXX.supabase.co
SUPABASE_ANON_KEY=eyJhbGciOiJIUzI1NiIsInR5...   ← "anon public"
SUPABASE_SERVICE_ROLE_KEY=eyJhbGciOiJIUzI1...    ← "service_role" (RAHASIA!)
SUPABASE_BUCKET=k3-files
```

---

## 3. Buat Bucket Storage di Supabase

1. Buka **Supabase Dashboard → Storage → New Bucket**
2. Nama bucket: `k3-files`
3. Centang **Public bucket** agar gambar bisa diakses publik
4. Klik **Create Bucket**

---

## 4. Jalankan Migrasi & Seeder

```bash
php artisan migrate
php artisan db:seed
```

Ini akan membuat semua tabel dan mengisi data awal:
- 1 admin (admin@k3pln.com / admin123)
- 8 APD
- 24 langkah SOP
- 8 hazard
- 5 insiden
- 9 anggota tim K3
- 10 program kesehatan

---

## 5. Jalankan Server

```bash
php artisan serve
```

Akses di: http://localhost:8000

- **Situs Publik**: http://localhost:8000
- **Login Admin**: http://localhost:8000/login
  - Email: `admin@k3pln.com`
  - Password: `admin123`

---

## Troubleshooting

### Error: "could not connect to server"
- Pastikan `DB_HOST`, `DB_PASSWORD`, dan `DB_SSLMODE=require` sudah benar
- Cek IP Whitelist di Supabase: **Project Settings → Database → Network Restrictions**
- Pilih "Allow all IPs" untuk development lokal

### Error: "SSL required"
- Pastikan `.env` memiliki `DB_SSLMODE=require`

### Gambar tidak tampil setelah upload
- Pastikan bucket Supabase sudah diset sebagai **Public**
- Cek `SUPABASE_SERVICE_ROLE_KEY` sudah benar di `.env`

### Error 419 (CSRF Token Mismatch)
- Pastikan `SESSION_DRIVER=file` dan folder `storage/framework/sessions` bisa ditulis
- Jalankan: `php artisan storage:link`

---

## Struktur Direktori Penting

```
app/
  Http/Controllers/
    Auth/LoginController.php       ← Login/logout
    Admin/                         ← CRUD admin
    PublicController.php           ← Halaman publik
  Models/                          ← Eloquent models
  Services/SupabaseStorageService.php  ← Upload ke Supabase
  Support/AuditLogger.php          ← Audit trail helper

resources/views/
  layouts/
    public.blade.php               ← Layout situs publik
    admin.blade.php                ← Layout admin panel
  public/                          ← Halaman publik
  admin/                           ← CRUD admin
  auth/login.blade.php             ← Halaman login

database/
  migrations/                      ← Schema PostgreSQL
  seeders/                         ← Data awal

public/assets/
  app.css                          ← Stylesheet K3 PLN
  denah.png                        ← Gambar denah (ganti sesuai kebutuhan)
```
