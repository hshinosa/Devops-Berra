# Progress Tracking - Anggota 1: Authentication, Role, dan Access Control

**Branch**: `feature/anggota-1-auth-access-control`
**Tanggal Mulai**: 2026-04-26

## Daftar Perubahan

### 1. Fix RegisteredUserController - Gunakan Admin model
- **File**: `app/Http/Controllers/Auth/RegisteredUserController.php`
- **Masalah**: Controller menggunakan `User::create()` dan `Auth::login($user)`, padahal guard `web` menggunakan provider `admins` (model `Admin`). Register membuat record di tabel `users` tapi auth via provider `admins` → broken.
- **Solusi**: Ganti `User` → `Admin`, unique check ke tabel `admins`, set `is_super_admin` default false.
- **Status**: ⏳ Pending

### 2. Fix RedirectIfAuthenticated Middleware
- **File**: `app/Http/Middleware/RedirectIfAuthenticated.php`
- **Masalah**: Menggunakan `RouteServiceProvider::HOME` yang tidak ada di Laravel 12.
- **Solusi**: Ganti dengan redirect langsung ke `/dashboard`.
- **Status**: ⏳ Pending

### 3. Buat EnsureAdmin Middleware
- **File**: `app/Http/Middleware/EnsureAdmin.php` (baru)
- **Fungsi**: Memastikan user yang login adalah admin (authenticated via web guard). Redirect guest ke login.
- **Status**: ⏳ Pending

### 4. Buat EnsureSuperAdmin Middleware
- **File**: `app/Http/Middleware/EnsureSuperAdmin.php` (baru)
- **Fungsi**: Memastikan user yang login adalah super admin (`is_super_admin = true`). Return 403 jika bukan.
- **Status**: ⏳ Pending

### 5. Register Middleware di bootstrap/app.php
- **File**: `bootstrap/app.php`
- **Perubahan**: Tambah alias `admin` dan `super_admin` untuk middleware baru.
- **Status**: ⏳ Pending

### 6. Role-based Redirect Setelah Login
- **File**: `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- **Perubahan**: Setelah login, redirect berdasarkan `is_super_admin` → super admin ke dashboard admin, admin biasa ke dashboard.
- **Status**: ⏳ Pending

### 7. Apply Role Middleware ke Routes
- **File**: `routes/web.php`
- **Perubahan**: Tambah middleware `admin` ke route group yang perlu proteksi. Tambah `super_admin` ke route user management.
- **Status**: ⏳ Pending

### 8. Share Role Info via Inertia
- **File**: `app/Http/Middleware/HandleInertiaRequests.php`
- **Perubahan**: Tambah `is_super_admin` ke shared props agar frontend bisa conditional render.
- **Status**: ⏳ Pending

### 9. Improve Login.jsx - Validasi & Feedback
- **File**: `resources/js/Pages/Auth/Login.jsx`
- **Perubahan**: Tambah pesan error yang lebih jelas, loading state, dan feedback visual.
- **Status**: ⏳ Pending

### 10. Improve Register.jsx - Validasi & Feedback
- **File**: `resources/js/Pages/Auth/Register.jsx`
- **Perubahan**: Tambah password strength indicator, pesan error yang lebih jelas, loading state.
- **Status**: ⏳ Pending

### 11. Buat AdminFactory
- **File**: `database/factories/AdminFactory.php` (baru)
- **Fungsi**: Factory untuk model Admin, digunakan di testing.
- **Status**: ⏳ Pending

### 12. Fix Existing Auth Tests
- **File**: `tests/Feature/Auth/AuthenticationTest.php`
- **Masalah**: Menggunakan `User::factory()` padahal guard `web` pakai provider `admins`.
- **Solusi**: Ganti ke `Admin::factory()`.
- **Status**: ⏳ Pending

### 13. Tulis Test Baru untuk Role & Access Control
- **File**: `tests/Feature/Auth/RoleAccessControlTest.php` (baru)
- **Test cases**: guest blocked, role redirect, admin access, super admin access, role protection.
- **Status**: ⏳ Pending

### 14. Jalankan php artisan test
- **Status**: ⏳ Pending

---

## Ringkasan Hasil
- Total file diubah: -
- Total file baru: -
- Total test: -
- Status akhir: ⏳ In Progress
