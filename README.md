# Sistem Informasi K3 PT PLN (Persero)

Aplikasi web **Keselamatan dan Kesehatan Kerja (K3)** berbasis Laravel 11 dengan Supabase PostgreSQL.

## Teknologi

| Layer | Teknologi |
|---|---|
| Backend | Laravel 11 |
| Frontend | Blade + Bootstrap 5 |
| Database | Supabase PostgreSQL |
| Storage | Supabase Storage |
| Chart | Chart.js 4 |
| Icons | Bootstrap Icons |

## Fitur

### Situs Publik (Tanpa Login)
| Halaman | URL |
|---|---|
| Beranda | `/` |
| Profil PLN | `/profil` |
| Identifikasi Bahaya | `/identifikasi-bahaya` |
| Risiko K3 | `/risiko-k3` |
| APD | `/apd` |
| SOP Keselamatan | `/sop` |
| Prosedur Evakuasi | `/prosedur-evakuasi` |
| Program Kesehatan | `/program-kesehatan` |
| Struktur Tim K3 | `/struktur-tim-k3` |
| Denah Lokasi | `/denah-lokasi` |
| Kesimpulan & Saran | `/kesimpulan-saran` |

### Admin Panel (Login Required)
| Modul | URL |
|---|---|
| Dashboard + Chart | `/admin/dashboard` |
| CRUD APD | `/admin/apd` |
| CRUD SOP | `/admin/sop` |
| CRUD Bahaya | `/admin/hazard` |
| CRUD Insiden | `/admin/incident` |
| CRUD Tim K3 | `/admin/team` |
| CRUD Prog. Kesehatan | `/admin/health` |
| Audit Log | `/admin/audit-log` |

## Setup

Lihat **SETUP.md** untuk panduan lengkap setup Supabase dan konfigurasi environment.

```bash
composer install
cp .env.example .env
# Edit .env dengan kredensial Supabase
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

**Login Admin:** `admin@k3pln.com` / `admin123`

## Struktur Project

```
app/
  Http/Controllers/
    Auth/LoginController.php
    Admin/                      ← CRUD controllers
    PublicController.php        ← Public pages
  Models/                       ← Eloquent models
  Services/SupabaseStorageService.php
  Support/AuditLogger.php

resources/views/
  layouts/public.blade.php      ← Layout publik
  layouts/admin.blade.php       ← Layout admin
  public/                       ← 11 halaman publik
  admin/                        ← CRUD views
  auth/login.blade.php

database/
  migrations/                   ← PostgreSQL compatible
  seeders/                      ← Data default K3
```
