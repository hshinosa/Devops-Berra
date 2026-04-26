# Progress Anggota 2 — User Management dan Profile

**Nama**: Soraya  
**Branch**: `feature/soraya-user-profile`  
**Fokus**: User Management dan Profile

---

## Daftar Perubahan

### Perubahan 1: Perbaikan Validasi CRUD User
- [ ] Perbaiki validasi di `UserController::store()` — tambah konfirmasi password, format telepon
- [ ] Perbaiki validasi di `UserController::update()` — tambah pesan error yang lebih jelas
- [ ] Perbaiki `ProfileUpdateRequest` — gunakan `Admin::class` bukan `User::class`

### Perubahan 2: Penyempurnaan Fitur Update Profile
- [ ] Perbaiki `UpdateProfileInformationForm.jsx` — hapus field yang tidak dipakai, tambah feedback sukses
- [ ] Perbaiki `UpdatePasswordForm.jsx` — tambah feedback sukses, label Indonesia
- [ ] Pastikan profile update berfungsi dengan benar untuk model Admin

### Perubahan 3: Penambahan Search/Filter User
- [ ] Tambah filter berdasarkan status toko (ada/tidak ada nama toko)
- [ ] Perbaiki UI search/filter di `Users/Index.jsx`

---

## Commit Log

| No | Commit | Deskripsi |
|----|--------|-----------|
| 1  | -      | Tracking doc |

---

## Status: 🔄 Dalam Pengerjaan
