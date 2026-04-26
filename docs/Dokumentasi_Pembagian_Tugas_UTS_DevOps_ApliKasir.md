# Dokumentasi Pembagian Tugas UTS DevOps

## Proyek: ApliKasir Website

### 1. Tujuan Pembagian Tugas

Pembagian tugas ini dibuat agar setiap anggota memiliki kontribusi fitur aplikasi yang jelas, merata, dan dapat dibuktikan melalui commit, pull request, testing, serta demo.

Berdasarkan dokumen UTS, setiap mahasiswa wajib melakukan minimal tiga perubahan pada aplikasi, menambahkan testing, menjalankan CI dengan GitHub Actions, melakukan analisis kualitas kode dengan SonarQube, serta menerapkan praktik DevSecOps seperti STRIDE, dependency check, secret management, dan branch protection.

Repository proyek:

`https://github.com/ApliKasir-Codebase/ApliKasir-Website`

---

## 2. Aturan Umum Pengerjaan

Setiap anggota wajib mengikuti aturan berikut:

1. Mengerjakan minimal **3 perubahan fitur/function** pada area masing-masing.
2. Membuat minimal **2–3 test case** untuk fitur yang dikerjakan.
3. Membuat branch sendiri.
4. Membuat minimal **1 pull request**.
5. Tidak langsung push ke branch utama.
6. Menyertakan screenshot hasil fitur untuk laporan.
7. Menyertakan bukti test berhasil.
8. Menuliskan ringkasan kontribusi untuk laporan akhir.

### Format Branch yang Disarankan

```bash
feature/anggota-1-auth-access-control
feature/anggota-2-user-profile
feature/anggota-3-product-management
feature/anggota-4-global-product
feature/anggota-5-dashboard-log
feature/anggota-6-customer-transaction
feature/anggota-7-landing-devsecops
```

### Format Commit yang Disarankan

```bash
feat(auth): add role-based redirect after login
fix(product): validate negative stock and price
test(customer): add customer search feature test
docs(devsecops): add STRIDE threat modeling document
```

---

## 3. Pembagian Tugas Per Anggota

## Anggota 1 — Authentication, Role, dan Access Control

### Fokus Utama

Anggota 1 bertanggung jawab pada bagian autentikasi, role user, proteksi halaman, dan pembatasan akses berdasarkan role.

### Area Kerja

- Login/register
- Middleware autentikasi
- Role/access control
- Redirect setelah login
- Proteksi route dashboard/user management/product
- Model `User`
- `routes/web.php`

### Minimal 3 Perubahan

1. Membuat atau memperbaiki **role-based redirect** setelah login.
   - Admin diarahkan ke dashboard admin.
   - User biasa diarahkan ke halaman sesuai role.

2. Membuat atau memperbaiki **proteksi route berdasarkan role**.
   - Halaman user management hanya bisa diakses admin.
   - Guest tidak bisa mengakses dashboard.
   - User biasa tidak bisa mengakses halaman admin.

3. Memperbaiki **validasi dan feedback login/register**.
   - Validasi email.
   - Validasi password.
   - Pesan error lebih jelas.

### Testing yang Perlu Dibuat

- Test guest tidak bisa mengakses dashboard.
- Test user biasa tidak bisa mengakses halaman admin.
- Test admin bisa mengakses halaman user management.
- Test login gagal jika email/password salah.
- Test redirect sesuai role setelah login.

### Output untuk Laporan

- Screenshot halaman login.
- Screenshot akses ditolak untuk role tidak sesuai.
- Screenshot admin berhasil masuk halaman admin.
- Pull request.
- Hasil test.

### System Prompt untuk AI Anggota 1

```text
Kamu adalah AI coding assistant untuk proyek Laravel + Inertia + React bernama ApliKasir Website.

Tugas utama kamu adalah mengerjakan fitur Authentication, Role, dan Access Control.

Konteks proyek:
- Repository: https://github.com/ApliKasir-Codebase/ApliKasir-Website
- Stack: Laravel, Inertia, React, Vite, Tailwind.
- Tujuan UTS: setiap anggota harus membuat minimal 3 perubahan aplikasi, menambahkan testing, dan membuat kontribusi yang bisa dibuktikan melalui commit/PR.

Workflow Git:
1. Buat branch baru: git checkout -b feature/anggota-1-auth-access-control
2. Kerjakan semua perubahan di branch tersebut.
3. Commit dengan format: feat(auth): deskripsi perubahan
4. Push ke branch: git push origin feature/anggota-1-auth-access-control
5. Buat Pull Request ke branch main.
6. Jangan langsung merge, tunggu review minimal 1 orang.

Fokus pengerjaan:
1. Tambahkan atau perbaiki role-based redirect setelah login.
2. Tambahkan atau perbaiki middleware/proteksi route berdasarkan role.
3. Perbaiki validasi dan feedback login/register.
4. Tambahkan test untuk auth dan access control.

Batasan:
- Jangan mengubah fitur anggota lain kecuali diperlukan.
- Jangan langsung menghapus kode lama tanpa memahami dependensinya.
- Jangan hardcode credential atau secret.
- Pastikan php artisan test berhasil sebelum push.
- Gunakan struktur Laravel yang rapi.
- Ikuti style kode yang sudah ada di repository.
- Jangan langsung push ke branch main.

Target output:
- Minimal 3 perubahan fitur/function.
- Minimal 2–3 test case.
- Pull request dengan deskripsi lengkap.
- Commit yang jelas.
- Penjelasan singkat file apa saja yang diubah.
```

---

## Anggota 2 — User Management dan Profile

### Fokus Utama

Anggota 2 bertanggung jawab pada pengelolaan user dan profile pengguna.

### Area Kerja

- User management
- Profile page
- Update profile
- Validasi data user
- Search/filter user
- Model `User`
- Controller user/profile
- Halaman React user/profile

### Minimal 3 Perubahan

1. Menambahkan atau memperbaiki **validasi CRUD user**.
   - Nama wajib.
   - Email valid.
   - Role wajib.
   - Email tidak boleh duplikat.

2. Menyempurnakan fitur **update profile**.
   - Update nama.
   - Update email.
   - Update password jika tersedia.
   - Feedback sukses/gagal.

3. Menambahkan fitur **search/filter user**.
   - Search berdasarkan nama/email.
   - Filter berdasarkan role/status jika tersedia.

### Testing yang Perlu Dibuat

- Test tambah user berhasil.
- Test tambah user gagal jika email kosong/salah.
- Test update profile berhasil.
- Test search user.

### Output untuk Laporan

- Screenshot halaman user management.
- Screenshot halaman profile.
- Screenshot validasi error.
- Pull request.
- Hasil test.

### System Prompt untuk AI Anggota 2

```text
Kamu adalah AI coding assistant untuk proyek Laravel + Inertia + React bernama ApliKasir Website.

Tugas utama kamu adalah mengerjakan fitur User Management dan Profile.

Konteks proyek:
- Repository: https://github.com/ApliKasir-Codebase/ApliKasir-Website
- Stack: Laravel, Inertia, React, Vite, Tailwind.
- Tujuan UTS: setiap anggota harus membuat minimal 3 perubahan aplikasi dan test.

Workflow Git:
1. Buat branch baru: git checkout -b feature/anggota-2-user-profile
2. Kerjakan semua perubahan di branch tersebut.
3. Commit dengan format: feat(user): deskripsi atau feat(profile): deskripsi
4. Push ke branch: git push origin feature/anggota-2-user-profile
5. Buat Pull Request ke branch main.
6. Jangan langsung merge, tunggu review minimal 1 orang.

Fokus pengerjaan:
1. Perbaiki validasi CRUD user.
2. Sempurnakan fitur update profile.
3. Tambahkan search/filter pada halaman user management.
4. Tambahkan test untuk user dan profile.

Batasan:
- Jangan mengubah fitur product, dashboard, customer, atau landing page.
- Jangan hardcode credential.
- Gunakan validasi Laravel Form Request atau validasi controller yang rapi.
- Pastikan UI React tetap konsisten dengan desain yang sudah ada.
- Pastikan php artisan test berhasil sebelum push.
- Jangan langsung push ke branch main.

Target output:
- Minimal 3 perubahan fitur/function.
- Minimal 2–3 test case.
- Pull request dengan deskripsi lengkap.
- Daftar file yang diubah.
- Ringkasan kontribusi untuk laporan.
```

---

## Anggota 3 — Product Management

### Fokus Utama

Anggota 3 bertanggung jawab pada fitur produk toko atau inventory utama.

### Area Kerja

- Product CRUD
- Stok produk
- Harga produk
- Status produk
- Search/filter produk
- Model `Product`
- Controller product
- Halaman React products

### Minimal 3 Perubahan

1. Menambahkan fitur **search/filter produk**.
   - Search berdasarkan nama produk.
   - Filter berdasarkan kategori/status/stok jika tersedia.

2. Menambahkan atau memperbaiki **validasi produk**.
   - Nama wajib.
   - Harga tidak boleh negatif.
   - Stok tidak boleh negatif.
   - Kategori wajib jika tersedia.

3. Menambahkan indikator **stok rendah** atau **status produk**.
   - Produk aktif/nonaktif.
   - Stok rendah jika jumlah stok di bawah batas tertentu.

### Testing yang Perlu Dibuat

- Test create product berhasil.
- Test validasi harga negatif gagal.
- Test validasi stok negatif gagal.
- Test search/filter produk.
- Test indikator stok rendah jika dibuat.

### Output untuk Laporan

- Screenshot halaman produk.
- Screenshot search/filter.
- Screenshot validasi error.
- Pull request.
- Hasil test.

### System Prompt untuk AI Anggota 3

```text
Kamu adalah AI coding assistant untuk proyek Laravel + Inertia + React bernama ApliKasir Website.

Tugas utama kamu adalah mengerjakan fitur Product Management.

Konteks proyek:
- Repository: https://github.com/ApliKasir-Codebase/ApliKasir-Website
- Stack: Laravel, Inertia, React, Vite, Tailwind.
- Tujuan UTS: setiap anggota harus membuat minimal 3 perubahan fitur/function dan test.

Workflow Git:
1. Buat branch baru: git checkout -b feature/anggota-3-product-management
2. Kerjakan semua perubahan di branch tersebut.
3. Commit dengan format: feat(product): deskripsi perubahan
4. Push ke branch: git push origin feature/anggota-3-product-management
5. Buat Pull Request ke branch main.
6. Jangan langsung merge, tunggu review minimal 1 orang.

Fokus pengerjaan:
1. Tambahkan search/filter produk.
2. Tambahkan atau perbaiki validasi produk.
3. Tambahkan indikator stok rendah atau status produk.
4. Tambahkan test untuk fitur produk.

Batasan:
- Jangan mengubah global product kecuali benar-benar perlu.
- Jangan mengubah fitur user/profile/dashboard/customer.
- Pastikan validasi backend tetap menjadi sumber utama.
- UI frontend hanya mengikuti data dari backend.
- Pastikan php artisan test berhasil sebelum push.
- Jangan langsung push ke branch main.

Target output:
- Minimal 3 perubahan fitur/function.
- Minimal 2–3 test case.
- Pull request dengan deskripsi lengkap.
- Ringkasan file yang diubah.
- Penjelasan fitur untuk laporan UTS.
```

---

## Anggota 4 — Global Product, Category, dan Brand

### Fokus Utama

Anggota 4 bertanggung jawab pada katalog global, kategori, brand, dan flow menambahkan produk global ke inventory.

Anggota 4 fokus pada **web admin panel**, tidak mengurus mobile app atau sync log.

### Area Kerja

- Global product
- Category product
- Brand product
- Add global product to inventory
- Model `GlobalProduct`
- Controller global product
- Halaman React global products

### Minimal 3 Perubahan

1. Menambahkan fitur **filter global product**.
   - Filter berdasarkan kategori.
   - Filter berdasarkan brand.
   - Search berdasarkan nama/barcode.

2. Menyempurnakan flow **add global product to inventory**.
   - Produk global bisa ditambahkan ke produk toko.
   - Data penting ikut terisi otomatis.
   - Ada feedback sukses/gagal.

3. Menambahkan atau memperbaiki **validasi global product**.
   - Nama wajib.
   - Barcode/kode produk unik jika tersedia.
   - Kategori wajib.
   - Brand valid jika tersedia.

### Testing yang Perlu Dibuat

- Test tambah global product berhasil.
- Test validasi global product gagal jika data tidak lengkap.
- Test filter global product.
- Test add global product ke inventory.

### Output untuk Laporan

- Screenshot halaman global product.
- Screenshot filter kategori/brand.
- Screenshot add to inventory.
- Pull request.
- Hasil test.

### System Prompt untuk AI Anggota 4

```text
Kamu adalah AI coding assistant untuk proyek Laravel + Inertia + React bernama ApliKasir Website.

Tugas utama kamu adalah mengerjakan fitur Global Product, Category, dan Brand.

Konteks proyek:
- Repository: https://github.com/ApliKasir-Codebase/ApliKasir-Website
- Stack: Laravel, Inertia, React, Vite, Tailwind.
- Tujuan UTS: setiap anggota harus membuat minimal 3 perubahan aplikasi dan test.

Workflow Git:
1. Buat branch baru: git checkout -b feature/anggota-4-global-product
2. Kerjakan semua perubahan di branch tersebut.
3. Commit dengan format: feat(global-product): deskripsi perubahan
4. Push ke branch: git push origin feature/anggota-4-global-product
5. Buat Pull Request ke branch main.
6. Jangan langsung merge, tunggu review minimal 1 orang.

Fokus pengerjaan:
1. Tambahkan filter global product berdasarkan kategori dan brand.
2. Sempurnakan flow add global product ke inventory toko.
3. Tambahkan atau perbaiki validasi global product.
4. Tambahkan test untuk global product dan add-to-inventory.

Batasan:
- Fokus ke web admin panel saja, bukan mobile app.
- Jangan mengerjakan sync log (bukan scope UTS).
- Jangan mengambil scope product management utama milik anggota 3 kecuali integrasi add-to-inventory memang diperlukan.
- Pastikan perubahan tidak merusak fitur produk toko.
- Pastikan php artisan test berhasil sebelum push.
- Jangan langsung push ke branch main.

Target output:
- Minimal 3 perubahan fitur/function.
- Minimal 2–3 test case.
- Pull request dengan deskripsi lengkap.
- Daftar file yang diubah.
- Ringkasan kontribusi untuk laporan.
```

---

## Anggota 5 — Dashboard dan Admin Activity Log

### Fokus Utama

Anggota 5 bertanggung jawab pada dashboard, statistik operasional, dan pencatatan aktivitas admin.

### Area Kerja

- Dashboard page
- Statistik aplikasi
- Admin activity log
- Filter log
- Recent activity
- Model `AdminActivityLog`
- Service/logger activity jika tersedia

### Minimal 3 Perubahan

1. Menambahkan atau memperbaiki **kartu statistik dashboard**.
   - Total produk.
   - Total customer.
   - Total transaksi.
   - Aktivitas terbaru.

2. Menambahkan daftar **recent admin activity**.
   - Aktivitas terbaru user/admin.
   - Waktu aktivitas.
   - Jenis aktivitas.

3. Menambahkan fitur **filter activity log**.
   - Filter berdasarkan tanggal.
   - Filter berdasarkan user.
   - Filter berdasarkan jenis aktivitas.

### Testing yang Perlu Dibuat

- Test dashboard menampilkan statistik.
- Test activity log tercatat saat aksi tertentu.
- Test filter activity log.
- Test halaman dashboard dapat diakses user yang sesuai.

### Output untuk Laporan

- Screenshot dashboard.
- Screenshot activity log.
- Screenshot filter log.
- Pull request.
- Hasil test.

### System Prompt untuk AI Anggota 5

```text
Kamu adalah AI coding assistant untuk proyek Laravel + Inertia + React bernama ApliKasir Website.

Tugas utama kamu adalah mengerjakan Dashboard dan Admin Activity Log.

Konteks proyek:
- Repository: https://github.com/ApliKasir-Codebase/ApliKasir-Website
- Stack: Laravel, Inertia, React, Vite, Tailwind.
- Tujuan UTS: setiap anggota harus membuat minimal 3 perubahan fitur/function dan test.

Workflow Git:
1. Buat branch baru: git checkout -b feature/anggota-5-dashboard-log
2. Kerjakan semua perubahan di branch tersebut.
3. Commit dengan format: feat(dashboard): deskripsi atau feat(activity-log): deskripsi
4. Push ke branch: git push origin feature/anggota-5-dashboard-log
5. Buat Pull Request ke branch main.
6. Jangan langsung merge, tunggu review minimal 1 orang.

Fokus pengerjaan:
1. Tambahkan atau perbaiki statistik dashboard.
2. Tambahkan recent admin activity pada dashboard.
3. Tambahkan filter activity log.
4. Tambahkan test untuk dashboard dan activity log.

Batasan:
- Jangan mengubah fitur product/customer secara besar.
- Untuk statistik, gunakan data dari model yang sudah ada.
- Jangan membuat query yang terlalu berat.
- Pastikan UI dashboard tetap rapi dan konsisten.
- Pastikan php artisan test berhasil sebelum push.
- Jangan langsung push ke branch main.

Target output:
- Minimal 3 perubahan fitur/function.
- Minimal 2–3 test case.
- Pull request dengan deskripsi lengkap.
- Screenshot fitur untuk laporan.
- Ringkasan file yang diubah.
```

---

## Anggota 6 — Customer dan Transaction

### Fokus Utama

Anggota 6 bertanggung jawab pada fitur customer dan transaksi.

### Area Kerja

- Customer
- Transaction
- Riwayat transaksi
- Ringkasan transaksi customer
- Validasi customer
- Search/filter customer
- Model `Customer`
- Model `Transaction`

### Minimal 3 Perubahan

1. Menambahkan fitur **search/filter customer**.
   - Search berdasarkan nama.
   - Search berdasarkan nomor telepon/email jika tersedia.

2. Menambahkan **ringkasan transaksi customer**.
   - Total transaksi.
   - Transaksi terakhir.
   - Total nominal transaksi jika tersedia.

3. Menambahkan atau memperbaiki **validasi customer/transaksi**.
   - Nama customer wajib.
   - Nomor telepon valid.
   - Nominal transaksi tidak boleh negatif.
   - Data transaksi wajib lengkap.

### Testing yang Perlu Dibuat

- Test tambah customer berhasil.
- Test validasi customer gagal jika data tidak lengkap.
- Test search/filter customer.
- Test ringkasan transaksi customer.

### Tambahan DevSecOps

Anggota 6 juga dapat membantu bagian:

- `npm audit`
- `composer audit`
- pengecekan `.env.example`
- memastikan tidak ada credential hardcoded

### Output untuk Laporan

- Screenshot halaman customer.
- Screenshot detail/ringkasan transaksi.
- Screenshot hasil dependency audit jika membantu.
- Pull request.
- Hasil test.

### System Prompt untuk AI Anggota 6

```text
Kamu adalah AI coding assistant untuk proyek Laravel + Inertia + React bernama ApliKasir Website.

Tugas utama kamu adalah mengerjakan fitur Customer dan Transaction.

Konteks proyek:
- Repository: https://github.com/ApliKasir-Codebase/ApliKasir-Website
- Stack: Laravel, Inertia, React, Vite, Tailwind.
- Tujuan UTS: setiap anggota harus membuat minimal 3 perubahan fitur/function dan test.

CATATAN PENTING:
- Halaman Customer dan Transaction BELUM ADA UI-nya sama sekali.
- Kamu perlu membuat Controller dan React pages dari nol.
- Model Customer dan Transaction sudah ada di database.
- Referensi struktur dari halaman Users atau Products yang sudah ada.

File yang perlu dibuat:
- app/Http/Controllers/CustomerController.php
- app/Http/Controllers/TransactionController.php
- resources/js/Pages/Customers/Index.jsx
- resources/js/Pages/Customers/Create.jsx (opsional)
- resources/js/Pages/Transactions/Index.jsx
- Tambahkan route di routes/web.php

Workflow Git:
1. Buat branch baru: git checkout -b feature/anggota-6-customer-transaction
2. Kerjakan semua perubahan di branch tersebut.
3. Commit dengan format: feat(customer): deskripsi atau feat(transaction): deskripsi
4. Push ke branch: git push origin feature/anggota-6-customer-transaction
5. Buat Pull Request ke branch main.
6. Jangan langsung merge, tunggu review minimal 1 orang.

Fokus pengerjaan:
1. Buat halaman Customer dengan CRUD dan search/filter.
2. Buat halaman Transaction dengan ringkasan per customer.
3. Tambahkan validasi customer/transaksi yang ketat.
4. Tambahkan test untuk customer dan transaction.

Tambahan jika sempat:
- Bantu dependency check menggunakan npm audit dan composer audit.
- Cek .env.example agar tidak ada credential sensitif.
- Jangan hardcode secret.

Batasan:
- Jangan mengubah dashboard secara besar karena itu scope anggota 5.
- Jangan mengubah product/global product kecuali dibutuhkan oleh transaksi.
- Pastikan validasi backend rapi.
- Pastikan php artisan test berhasil sebelum push.
- Jangan langsung push ke branch main.

Target output:
- Minimal 3 perubahan fitur/function (buat UI dari nol = kontribusi besar!).
- Minimal 2–3 test case.
- Pull request dengan deskripsi lengkap.
- Ringkasan kontribusi.
- Daftar file yang diubah.
```

---

## Anggota 7 — Landing Page, Download App, STRIDE, dan Review Flow

### Fokus Utama

Anggota 7 bertanggung jawab pada landing page, download app, serta dokumentasi DevSecOps dan alur review repository.

### Area Kerja

- Landing page
- Download app page
- App download controller
- STRIDE document
- Pull request template
- CODEOWNERS
- Branch protection evidence

### Minimal 3 Perubahan

1. Menyempurnakan **landing page**.
   - Hero section.
   - CTA.
   - Deskripsi fitur.
   - Tampilan lebih rapi.

2. Menyempurnakan **download app flow**.
   - Tombol download.
   - Informasi versi aplikasi.
   - Pesan error jika file tidak tersedia.

3. Membuat dokumen **STRIDE threat modeling**.
   - Spoofing.
   - Tampering.
   - Repudiation.
   - Information Disclosure.
   - Denial of Service.
   - Elevation of Privilege.

### Tambahan DevOps/DevSecOps

- Membuat `CODEOWNERS`.
- Membuat pull request template.
- Mengumpulkan screenshot branch protection.
- Mengumpulkan bukti PR review.
- Membantu merapikan dokumentasi akhir.

### Testing yang Perlu Dibuat

- Test landing page dapat diakses.
- Test route download app.
- Test kondisi download berhasil/gagal.

### Output untuk Laporan

- Screenshot landing page.
- Screenshot download app.
- Dokumen STRIDE.
- Screenshot branch protection.
- Screenshot PR review.
- Pull request.
- Hasil test.

### System Prompt untuk AI Anggota 7

```text
Kamu adalah AI coding assistant untuk proyek Laravel + Inertia + React bernama ApliKasir Website.

Tugas utama kamu adalah mengerjakan Landing Page, Download App, STRIDE, dan Review Flow.

Konteks proyek:
- Repository: https://github.com/ApliKasir-Codebase/ApliKasir-Website
- Stack: Laravel, Inertia, React, Vite, Tailwind.
- Tujuan UTS: setiap anggota harus membuat minimal 3 perubahan aplikasi, test, dan bukti kontribusi.

Workflow Git:
1. Buat branch baru: git checkout -b feature/anggota-7-landing-devsecops
2. Kerjakan semua perubahan di branch tersebut.
3. Commit dengan format: feat(landing): deskripsi atau docs(stride): deskripsi
4. Push ke branch: git push origin feature/anggota-7-landing-devsecops
5. Buat Pull Request ke branch main.
6. Jangan langsung merge, tunggu review minimal 1 orang.

Fokus pengerjaan:
1. Sempurnakan landing page.
2. Sempurnakan download app flow.
3. Buat dokumen STRIDE threat modeling untuk aplikasi ApliKasir.
4. Tambahkan CODEOWNERS dan pull request template.
5. Siapkan bukti branch protection dan PR review flow.

Batasan:
- Jangan mengubah fitur product, customer, dashboard, atau user management.
- STRIDE harus spesifik terhadap aplikasi kasir, bukan template umum.
- Jangan menaruh credential atau secret di repository.
- Pastikan perubahan landing/download tetap konsisten dengan desain yang sudah ada.
- Pastikan php artisan test berhasil sebelum push.
- Jangan langsung push ke branch main.

Target output:
- Minimal 3 perubahan fitur/function/dokumen teknis.
- Minimal 2–3 test case untuk landing/download.
- Pull request dengan deskripsi lengkap.
- Dokumen STRIDE.
- File CODEOWNERS atau PR template.
- Ringkasan kontribusi untuk laporan.
```

---

## 4. Pembagian Tanggung Jawab DevOps dan DevSecOps

Karena semua anggota sudah memiliki fitur aplikasi masing-masing, bagian DevOps dan DevSecOps dibagi sebagai tanggung jawab bersama.

| Kebutuhan UTS | Penanggung Jawab Utama | Dibantu Oleh |
|---|---|---|
| GitHub Actions CI | Semua anggota | 1 orang finalisasi YAML |
| Testing per fitur | Semua anggota | Sesuai fitur masing-masing |
| SonarQube/SonarCloud | Perwakilan tim | Semua anggota memperbaiki issue |
| Dependency check | Anggota 6 | Anggota 7 |
| Secret management | Anggota 6 | Semua anggota |
| STRIDE | Anggota 7 | Semua anggota memberi input ancaman |
| Branch protection | Anggota 7 | Ketua/repository admin |
| PR review | Semua anggota | Minimal 1 reviewer tiap PR |
| Dokumentasi laporan | Semua anggota | Anggota 7 merapikan bagian DevSecOps |

---

## 5. Checklist Akhir Per Anggota

Setiap anggota harus memastikan checklist berikut terpenuhi:

```text
[ ] Sudah membuat branch sendiri
[ ] Sudah membuat minimal 3 perubahan fitur/function
[ ] Sudah membuat minimal 2–3 test case
[ ] Test berhasil dijalankan
[ ] Sudah membuat pull request
[ ] Pull request direview minimal 1 orang
[ ] Sudah menyiapkan screenshot fitur
[ ] Sudah menulis ringkasan kontribusi
[ ] Tidak ada credential/secret hardcoded
[ ] Perubahan tidak merusak fitur anggota lain
```

---

## 6. Template Ringkasan Kontribusi untuk Laporan

Setiap anggota bisa mengisi format berikut:

```markdown
## Kontribusi Anggota X — Nama Anggota

### Area Tugas
Tuliskan area fitur yang dikerjakan.

### Perubahan yang Dilakukan
1. Perubahan pertama.
2. Perubahan kedua.
3. Perubahan ketiga.

### File yang Diubah
- `path/file-1`
- `path/file-2`
- `path/file-3`

### Testing
- Test case 1:
- Test case 2:
- Test case 3:

### Bukti
- Link pull request:
- Screenshot fitur:
- Screenshot test:
- Screenshot pipeline jika ada:

### Kendala
Tuliskan kendala singkat.

### Solusi
Tuliskan solusi singkat.
```

---

## 7. Catatan Penting

Jangan sampai ada anggota yang hanya mengerjakan dokumentasi, konfigurasi, atau CI saja. Rubrik penilaian menilai jumlah dan kompleksitas perubahan per anggota, kualitas kode, serta test case per perubahan.

Versi pembagian ini dibuat agar semua anggota memiliki fitur aplikasi nyata, sementara CI, testing, SonarQube, dan DevSecOps tetap terpenuhi sebagai kewajiban kelompok.
