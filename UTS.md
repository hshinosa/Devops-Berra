[cite_start]Bachelor of Software Engineering [cite: 1]
[cite_start]School of Computing [cite: 1]
[cite_start]Telkom University [cite: 1]

## [cite_start]UJIAN TENGAH SEMESTER [cite: 2]
### [cite_start]DEVOPS (CCK4IBB3) [cite: 3]
[cite_start]**Program Studi S1 Rekayasa Perangkat Lunak** [cite: 4]
[cite_start]**Fakultas Informatika, Telkom University** [cite: 5]

---

### [cite_start]A. Hasil Proyek (Bobot: 50%) [cite: 6]

[cite_start]Ujian Tengah Semester terbagi menjadi dua bagian yaitu presentasi hasil proyek dan kemampuan teori. [cite: 7] [cite_start]Bagian proyek dikerjakan secara berkelompok dengan ketentuan sebagai berikut: [cite: 8]

#### [cite_start]1. Pipeline Continuous Integration (CI) [cite: 9]
[cite_start]Mahasiswa bekerja dalam kelompok untuk membuat pipeline Continuous Integration (CI) yang secara otomatis menjalankan proses build dan testing setiap kali ada perubahan pada kode menggunakan GitHub Actions. [cite: 10] [cite_start]Setiap mahasiswa diwajibkan untuk melakukan minimal tiga perubahan pada aplikasi yang dibangun. [cite: 11] [cite_start]Semakin kompleks dan banyak perubahan yang dilakukan, semakin tinggi nilai yang akan diperoleh. [cite: 12] [cite_start]Perubahan yang dapat dilakukan: [cite: 12]
* [cite_start]Testing [cite: 13]
* [cite_start]Penambahan Fitur [cite: 14]
* [cite_start]Penambahan Function [cite: 15]

#### [cite_start]2. Testing [cite: 13]
[cite_start]Setelah melakukan perubahan terhadap aplikasi, setiap mahasiswa wajib menambahkan proses testing pada GitHub Actions. [cite: 16] [cite_start]Framework/tools yang direkomendasikan: [cite: 17]

| [cite_start]No [cite: 18] | [cite_start]Bahasa Pemrograman [cite: 18] | [cite_start]Tools/Framework Testing [cite: 18] |
| :--- | :--- | :--- |
| [cite_start]1 [cite: 18] | [cite_start]PHP [cite: 18] | [cite_start]PHPUnit [cite: 18] |
| [cite_start]2 [cite: 18] | [cite_start]Node.js [cite: 18] | [cite_start]Jest atau Mocha [cite: 18] |
| [cite_start]3 [cite: 18] | [cite_start]Java [cite: 18] | [cite_start]JUnit [cite: 18] |

#### [cite_start]3. Analisis Kualitas Kode [cite: 19]
[cite_start]Tahapan selanjutnya adalah melakukan analisis terhadap kualitas kode yang telah ditambahkan dengan menggunakan SonarQube. [cite: 20] [cite_start]Hasil analisis harus didokumentasikan dalam laporan. [cite: 20]

#### [cite_start]4. DevSecOps [cite: 21]
[cite_start]Setiap kelompok wajib menerapkan praktik DevSecOps dalam proyek, mencakup empat elemen berikut: [cite: 22]

**a. [cite_start]Threat Modeling dengan STRIDE** [cite: 22]
[cite_start]Setiap kelompok melakukan analisis ancaman keamanan (threat modeling) menggunakan framework STRIDE sesuai dengan ruang lingkup aplikasi masing-masing. [cite: 23] [cite_start]Analisis harus mencakup identifikasi ancaman untuk setiap kategori STRIDE, pemetaan ancaman ke komponen aplikasi, penentuan tingkat risiko (Low/Medium/High), dan rekomendasi mitigasi. [cite: 24, 28]

UTS DevOps-CCK4IBB3-Hal. [cite_start]1 [cite: 25]

---

[cite_start]Bachelor of Software Engineering [cite: 26]
[cite_start]School of Computing [cite: 27]
[cite_start]Telkom University [cite: 27]

[cite_start]Berikut contoh format tabel analisis STRIDE: [cite: 29]

| [cite_start]No [cite: 30] | [cite_start]Kategori STRIDE [cite: 30] | [cite_start]Contoh Ancaman [cite: 30] | [cite_start]Risiko [cite: 30] | [cite_start]Mitigasi [cite: 30] |
| :--- | :--- | :--- | :--- | :--- |
| [cite_start]1 [cite: 30] | [cite_start]Spoofing [cite: 30] | [cite_start]Penyerang memalsukan identitas user [cite: 30] | [cite_start]High [cite: 30] | [cite_start]Implementasi autentikasi, validasi token [cite: 30] |
| [cite_start]2 [cite: 30] | [cite_start]Tampering [cite: 30] | [cite_start]Modifikasi data pada request/response [cite: 30] | [cite_start]Medium [cite: 30] | [cite_start]Input validation, HTTPS, integrity check [cite: 30] |
| [cite_start]3 [cite: 30] | [cite_start]Repudiation [cite: 30] | [cite_start]User menyangkal aksi yang dilakukan [cite: 30] | [cite_start]Medium [cite: 30] | [cite_start]Logging dan audit trail [cite: 30] |
| [cite_start]4 [cite: 30] | [cite_start]Information Disclosure [cite: 30] | [cite_start]Data sensitif terekspos [cite: 30] | [cite_start]High [cite: 30] | [cite_start]Enkripsi, akses kontrol, GitHub Secrets [cite: 30] |
| [cite_start]5 [cite: 30] | [cite_start]Denial of Service [cite: 30] | [cite_start]Serangan membuat layanan tidak tersedia [cite: 30] | [cite_start]Medium [cite: 30] | [cite_start]Rate limiting, validasi input [cite: 30] |
| [cite_start]6 [cite: 30] | [cite_start]Elevation of Privilege [cite: 30] | [cite_start]User mendapat akses di luar haknya [cite: 30] | [cite_start]High [cite: 30] | [cite_start]Role-based access control, least privilege [cite: 30] |

**b. [cite_start]Dependency Check di Pipeline** [cite: 31]
[cite_start]Setiap kelompok wajib menambahkan langkah dependency check di GitHub Actions menggunakan tools bawaan package manager: [cite: 32]
* [cite_start]**Node.js**: `npm audit` [cite: 33]
* [cite_start]**PHP**: `composer audit` [cite: 34]
* [cite_start]**Java**: `mvn dependency-check` atau `gradle dependencyCheckAnalyze` [cite: 35]

**c. [cite_start]Secret Management** [cite: 36]
[cite_start]Setiap kelompok harus menjelaskan bagaimana pengelolaan credential dan secret dalam proyek. [cite: 37] [cite_start]Tidak diperkenankan adanya hardcoded credentials di dalam kode. [cite: 37] [cite_start]Gunakan GitHub Secrets untuk menyimpan informasi sensitif seperti API key/database credentials/token. [cite: 38]

**d. [cite_start]Branch Protection & Code Review** [cite: 39]
[cite_start]Repository kelompok harus menerapkan branch protection rules pada branch utama (main/master). [cite: 40] [cite_start]Wajib menggunakan Pull Request untuk merge dan minimal satu reviewer sebelum merge diterima. [cite: 41]

#### [cite_start]5. Laporan Proyek [cite: 42]
[cite_start]Setiap kelompok wajib menyusun laporan proyek dengan struktur sebagai berikut: [cite: 43]

| [cite_start]No [cite: 44] | [cite_start]Bagian [cite: 44] | [cite_start]Isi [cite: 44] |
| :--- | :--- | :--- |
| [cite_start]1 [cite: 44] | [cite_start]Deskripsi Aplikasi [cite: 44] | [cite_start]Gambaran umum aplikasi, arsitektur, tech stack, dan pembagian tugas anggota kelompok [cite: 44] |
| [cite_start]2 [cite: 44] | [cite_start]CI Pipeline [cite: 44] | [cite_start]Penjelasan workflow GitHub Actions, tahapan build dan test, screenshot pipeline, penjelasan konfigurasi YAML [cite: 44] |
| [cite_start]3 [cite: 44] | [cite_start]Perubahan Aplikasi [cite: 44] | [cite_start]Daftar perubahan per anggota (fitur/function), commit history, dan penjelasan kontribusi masing-masing [cite: 44] |
| [cite_start]4 [cite: 44] | [cite_start]Testing [cite: 44] | [cite_start]Strategi testing, test cases yang dibuat, hasil eksekusi test, dan code coverage (jika ada) [cite: 44] |
| [cite_start]5 [cite: 44] | [cite_start]Analisis Kualitas Kode [cite: 44] | [cite_start]Hasil scan SonarQube, penjelasan issues (bugs, code smells, vulnerabilities), dan langkah perbaikan [cite: 44] |

UTS DevOps-CCK4IBB3-Hal. [cite_start]2 [cite: 45]

---

| [cite_start]No [cite: 46] | [cite_start]Bagian [cite: 46] | [cite_start]Isi [cite: 46] |
| :--- | :--- | :--- |
| [cite_start]6 [cite: 46] | [cite_start]Analisis DevSecOps [cite: 46] | [cite_start]Threat modeling STRIDE, hasil dependency check, pengelolaan secret, dan penerapan branch protection [cite: 46] |

[cite_start]Bachelor of Software Engineering [cite: 47]
[cite_start]School of Computing [cite: 48]
[cite_start]Telkom University [cite: 49]

#### [cite_start]6. Video Demonstrasi [cite: 50]
[cite_start]Setiap kelompok mempersiapkan video (upload ke YouTube) yang mencakup: deskripsi aplikasi, penjelasan pipeline CI, perubahan yang dilakukan, testing, hasil kualitas kode dari SonarQube, dan penerapan DevSecOps. [cite: 51]

### [cite_start]Kriteria Penilaian Proyek [cite: 52]
[cite_start]Berikut distribusi bobot penilaian UTS: [cite: 53]

| [cite_start]No [cite: 54] | [cite_start]Komponen Penilaian [cite: 54] | [cite_start]Bobot [cite: 54] | [cite_start]Jumlah Kriteria [cite: 54] |
| :--- | :--- | :--- | :--- |
| [cite_start]1 [cite: 54] | [cite_start]Implementasi Pipeline CI [cite: 54] | [cite_start]15% [cite: 54] | [cite_start]4 kriteria [cite: 54] |
| [cite_start]2 [cite: 54] | [cite_start]Perubahan Aplikasi & Kualitas Kode [cite: 54] | [cite_start]10% [cite: 54] | [cite_start]3 kriteria [cite: 54] |
| [cite_start]3 [cite: 54] | [cite_start]DevSecOps [cite: 54] | [cite_start]10% [cite: 54] | [cite_start]4 kriteria [cite: 54] |
| [cite_start]4 [cite: 54] | [cite_start]Dokumentasi & Laporan [cite: 54] | [cite_start]10% [cite: 54] | [cite_start]3 kriteria [cite: 54] |
| [cite_start]5 [cite: 54] | [cite_start]Presentasi & Demonstrasi [cite: 54] | [cite_start]5% [cite: 54] | [cite_start]2 kriteria [cite: 54] |
| [cite_start]**Total Proyek** [cite: 54] | | [cite_start]**50%** [cite: 54] | |
| [cite_start]6 [cite: 54] | [cite_start]Kemampuan Teori (Ujian LMS) [cite: 54] | [cite_start]50% [cite: 54] | [cite_start]50 soal [cite: 54] |
| [cite_start]**Total UTS** [cite: 54] | | [cite_start]**100%** [cite: 54] | |

[cite_start]Setiap kriteria dinilai dengan skala 1-4 (Kurang, Cukup, Baik, Sangat Baik). [cite: 55] [cite_start]Nilai akhir proyek dihitung berdasarkan rata-rata skor per komponen dikalikan bobot masing-masing. [cite: 56]

[cite_start]**Rubrik 1: Implementasi Pipeline CI (15%)** [cite: 57]

| [cite_start]No [cite: 58] | [cite_start]Kriteria [cite: 58] | [cite_start]Sangat Baik (4) [cite: 58] | [cite_start]Baik (3) [cite: 58] | [cite_start]Cukup (2) [cite: 58] | [cite_start]Kurang (1) [cite: 58] |
| :--- | :--- | :--- | :--- | :--- | :--- |
| [cite_start]1 [cite: 58] | [cite_start]Pipeline CI berjalan otomatis pada setiap push/PR [cite: 58] | [cite_start]Pipeline sempurna, multi-stage (build, test, analysis) [cite: 58] | [cite_start]Pipeline berjalan dengan build dan test [cite: 58] | [cite_start]Pipeline berjalan tapi hanya satu stage [cite: 58] | [cite_start]Pipeline tidak berjalan [cite: 58] |
| [cite_start]2 [cite: 58] | [cite_start]Konfigurasi YAML GitHub Actions [cite: 58] | [cite_start]YAML terstruktur, readable, menggunakan best practice [cite: 58] | [cite_start]YAML berjalan dengan baik [cite: 58] | [cite_start]YAML ada tapi ada error minor [cite: 58] | [cite_start]YAML tidak ada / tidak berfungsi [cite: 58] |
| [cite_start]3 [cite: 58] | [cite_start]Testing terintegrasi di pipeline [cite: 58] | [cite_start]Test otomatis berjalan, coverage > 50% [cite: 58] | [cite_start]Test otomatis berjalan di pipeline [cite: 58] | [cite_start]Test ada tapi tidak terintegrasi [cite: 58] | [cite_start]Tidak ada test [cite: 58] |
| [cite_start]4 [cite: 58] | [cite_start]SonarQube terintegrasi [cite: 58] | [cite_start]SonarQube otomatis, hasil dianalisis dan ditindaklanjuti [cite: 58] | [cite_start]SonarQube berjalan otomatis [cite: 58] | [cite_start]SonarQube dijalankan manual [cite: 58] | [cite_start]Tidak menggunakan SonarQube [cite: 58] |

UTS DevOps-CCK4IBB3-Hal. [cite_start]3 [cite: 59]

---

[cite_start]Bachelor of Software Engineering [cite: 60]
[cite_start]School of Computing [cite: 61]
[cite_start]Telkom University [cite: 62]

[cite_start]**Rubrik 2: Perubahan Aplikasi & Kualitas Kode (10%)** [cite: 63]

| [cite_start]No [cite: 64] | [cite_start]Kriteria [cite: 64] | [cite_start]Sangat Baik (4) [cite: 64] | [cite_start]Baik (3) [cite: 64] | [cite_start]Cukup (2) [cite: 64] | [cite_start]Kurang (1) [cite: 64] |
| :--- | :--- | :--- | :--- | :--- | :--- |
| [cite_start]1 [cite: 64] | [cite_start]Jumlah dan kompleksitas perubahan per anggota [cite: 64] | [cite_start]$>3$ perubahan signifikan dengan fitur kompleks [cite: 64] | [cite_start]2-3 perubahan dengan fitur baru [cite: 64] | [cite_start]1 perubahan minimal [cite: 64] | [cite_start]Tidak ada perubahan [cite: 64] |
| [cite_start]2 [cite: 64] | [cite_start]Kualitas kode (hasil SonarQube) [cite: 64] | [cite_start]Tidak ada bugs/vulnerabilities, minimal code smells [cite: 64] | [cite_start]Sedikit code smells, tidak ada bugs [cite: 64] | [cite_start]Ada bugs minor belum diperbaiki [cite: 64] | [cite_start]Banyak bugs dan code smells [cite: 64] |
| [cite_start]3 [cite: 64] | [cite_start]Test cases per perubahan [cite: 64] | [cite_start]$>3$ test cases per fitur, mencakup edge cases [cite: 64] | [cite_start]2-3 test cases per fitur [cite: 64] | [cite_start]1 test case per fitur [cite: 64] | [cite_start]Tidak ada test case [cite: 64] |

[cite_start]**Rubrik 3: DevSecOps (10%)** [cite: 65]

| [cite_start]No [cite: 66] | [cite_start]Kriteria [cite: 66] | [cite_start]Sangat Baik (4) [cite: 66] | [cite_start]Baik (3) [cite: 66] | [cite_start]Cukup (2) [cite: 66] | [cite_start]Kurang (1) [cite: 66] |
| :--- | :--- | :--- | :--- | :--- | :--- |
| [cite_start]1 [cite: 66] | [cite_start]Analisis STRIDE sesuai ruang lingkup aplikasi [cite: 66] | [cite_start]Semua 6 kategori dianalisis dengan ancaman spesifik dan mitigasi [cite: 66] | [cite_start]Semua 6 kategori dianalisis, mitigasi umum [cite: 66] | [cite_start]3-5 kategori dianalisis [cite: 66] | [cite_start]< 3 kategori atau tidak ada [cite: 66] |
| [cite_start]2 [cite: 66] | [cite_start]Dependency check di pipeline [cite: 66] | [cite_start]Dependency check otomatis, hasil dianalisis dan diperbaiki [cite: 66] | [cite_start]Dependency check otomatis di pipeline [cite: 66] | [cite_start]Dependency check manual [cite: 66] | [cite_start]Tidak ada dependency check [cite: 66] |
| [cite_start]3 [cite: 66] | [cite_start]Secret management [cite: 66] | [cite_start]GitHub Secrets digunakan, tidak ada hardcoded credentials [cite: 66] | [cite_start]Menggunakan GitHub Secrets [cite: 66] | [cite_start]Sebagian credential hardcoded [cite: 66] | [cite_start]Semua credential hardcoded [cite: 66] |
| [cite_start]4 [cite: 66] | [cite_start]Branch protection & code review [cite: 66] | [cite_start]Branch protection aktif, PR wajib review, ada bukti review [cite: 66] | [cite_start]Branch protection aktif, PR wajib [cite: 66] | [cite_start]Ada PR tapi tanpa review [cite: 66] | [cite_start]Langsung push ke main [cite: 66] |

[cite_start]**Rubrik 4: Dokumentasi & Laporan (10%)** [cite: 67]

| [cite_start]No [cite: 68] | [cite_start]Kriteria [cite: 68] | [cite_start]Sangat Baik (4) [cite: 68] | [cite_start]Baik (3) [cite: 68] | [cite_start]Cukup (2) [cite: 68] | [cite_start]Kurang (1) [cite: 68] |
| :--- | :--- | :--- | :--- | :--- | :--- |
| [cite_start]1 [cite: 68] | [cite_start]Kelengkapan laporan (7 bagian) [cite: 68] | [cite_start]Semua bagian lengkap, terstruktur, dan detail [cite: 68] | [cite_start]Semua bagian ada, cukup detail [cite: 68] | [cite_start]Beberapa bagian kurang lengkap [cite: 68] | [cite_start]Laporan sangat tidak lengkap [cite: 68] |
| [cite_start]2 [cite: 68] | [cite_start]Video demonstrasi (YouTube) [cite: 68] | [cite_start]Video jelas, mencakup semua aspek, durasi proporsional [cite: 68] | [cite_start]Video mencakup sebagian besar aspek [cite: 68] | [cite_start]Video ada tapi kurang jelas [cite: 68] | [cite_start]Tidak ada video [cite: 68] |
| [cite_start]3 [cite: 68] | [cite_start]Retrospektif tim [cite: 68] | [cite_start]Refleksi mendalam: lessons learned, kendala, rencana perbaikan [cite: 68] | [cite_start]Refleksi cukup: kendala dan perbaikan [cite: 68] | [cite_start]Refleksi singkat tanpa analisis [cite: 68] | [cite_start]Tidak ada retrospektif [cite: 68] |

UTS DevOps-CCK4IBB3-Hal. [cite_start]4 [cite: 69]

---

[cite_start]Bachelor of Software Engineering [cite: 70]
[cite_start]School of Computing [cite: 71]
[cite_start]Telkom University [cite: 72]

[cite_start]**Rubrik 5: Presentasi & Demonstrasi (5%)** [cite: 73]

| [cite_start]No [cite: 74] | [cite_start]Kriteria [cite: 74] | [cite_start]Sangat Baik (4) [cite: 74] | [cite_start]Baik (3) [cite: 74] | [cite_start]Cukup (2) [cite: 74] | [cite_start]Kurang (1) [cite: 74] |
| :--- | :--- | :--- | :--- | :--- | :--- |
| [cite_start]1 [cite: 74] | [cite_start]Penguasaan materi dan demo [cite: 74] | [cite_start]Menguasai semua aspek, demo lancar, jawab pertanyaan baik [cite: 74] | [cite_start]Menguasai sebagian besar, demo lancar [cite: 74] | [cite_start]Kurang menguasai, demo ada kendala [cite: 74] | [cite_start]Tidak menguasai materi [cite: 74] |
| [cite_start]2 [cite: 74] | [cite_start]Kontribusi individu terlihat jelas [cite: 74] | [cite_start]Setiap anggota menjelaskan kontribusi secara spesifik [cite: 74] | [cite_start]Sebagian besar anggota dapat menjelaskan [cite: 74] | [cite_start]Hanya sebagian kecil menjelaskan [cite: 74] | [cite_start]Tidak bisa menjelaskan kontribusi [cite: 74] |

UTS DevOps CCK4IBB3-Hal. [cite_start]5 [cite: 75]

---

[cite_start]Bachelor of Software Engineering [cite: 76]
[cite_start]School of Computing [cite: 77]
[cite_start]Telkom University [cite: 78]

### [cite_start]B. Kemampuan Teori (Bobot: 50%) [cite: 79]

#### [cite_start]1. Cakupan Materi [cite: 80]
[cite_start]Pengujian kemampuan teori dilakukan dengan mengerjakan soal di LMS yang mencakup materi berikut: [cite: 81]

| [cite_start]No [cite: 82] | [cite_start]Materi [cite: 82] |
| :--- | :--- |
| [cite_start]1 [cite: 82] | [cite_start]Introduction of DevOps [cite: 82] |
| [cite_start]2 [cite: 82] | [cite_start]DevOps Culture [cite: 82] |
| [cite_start]3 [cite: 82] | [cite_start]Technical Stack for DevOps [cite: 82] |
| [cite_start]4 [cite: 82] | [cite_start]System Operation (Linux) [cite: 82] |
| [cite_start]5 [cite: 82] | [cite_start]Networking and DevSecOps [cite: 82] |
| [cite_start]6 [cite: 82] | [cite_start]Continuous Integration [cite: 82] |
| [cite_start]7 [cite: 82] | [cite_start]Cloud Provider for DevOps [cite: 82] |

#### [cite_start]2. Jadwal UTS [cite: 83]
[cite_start]*menunggu informasi LAAK [cite: 84]

#### [cite_start]3. Tipe Ujian [cite: 85]
[cite_start]Onsite Ujian LMS (50 soal) [cite: 86]

UTS DevOps CCK4IBB3-Hal. [cite_start]6 [cite: 87]