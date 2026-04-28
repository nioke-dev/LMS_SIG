# Master Test Plan: Learning Coordinator (Enterprise Grade)
**Version:** 1.0  
**Focus:** Strict Service Isolation, DTO Integrity, & Business Logic Consistency.

Dokumen ini berisi daftar uji komprehensif untuk memastikan fitur Learning Coordinator berjalan sesuai standar Enterprise. Silakan berikan laporan hasil uji (PASS/FAIL) beserta catatan jika ditemukan anomali.

---

## 1. Authentication & Role-Based Access Control (RBAC)
Memastikan pintu masuk aman dan hak akses terisolasi sempurna.

| ID | Skenario Uji | Detail Langkah | Ekspektasi Hasil | Status |
|:---|:---|:---|:---|:---|
| 1.1 | Login LC (Portal Back-office) | Masuk melalui URL `/backoffice` dengan kredensial LC. | Berhasil login dan dialihkan otomatis ke `/learning-coordinator/dashboard`. | |
| 1.2 | Proteksi Portal Publik | Mencoba login LC melalui portal login karyawan umum. | Sistem mengenali role manajemen dan memberikan opsi untuk beralih ke portal manajemen. | |
| 1.3 | Isolasi Akses (Admin) | Mencoba akses manual URL `/learning-admin/dashboard`. | Akses ditolak (403 atau Redirect) dengan pesan "Anda tidak memiliki otorisasi". | |
| 1.4 | Isolasi Akses (SME) | Mencoba akses manual URL `/sme/dashboard`. | Akses ditolak. | |
| 1.5 | Logout Integrity | Melakukan logout dari sidebar. | Sesi hancur total, tidak bisa 'back' ke dashboard tanpa login ulang. | |

---

## 2. Organization Scope Logic (OrganizationService)
Memastikan LC hanya bisa melihat karyawan di bawah departemen dan unit-unit turunannya.

| ID | Skenario Uji | Detail Langkah | Ekspektasi Hasil | Status |
|:---|:---|:---|:---|:---|
| 2.1 | Recursive Hierarchy | LC dari Departemen A melihat daftar peserta saat membuat usulan. | Muncul semua karyawan dari Departemen A, Unit A.1, Unit A.1.1, dst. | |
| 2.2 | Cross-Department Isolation | LC dari Departemen A mencari karyawan dari Departemen B. | Karyawan Departemen B TIDAK muncul di daftar pencarian atau tabel. | |
| 2.3 | Empty Scope | LC yang belum memiliki `organization_id` di database. | Sistem tidak crash, melainkan menampilkan tabel kosong dengan pesan informatif. | |

---

## 3. TNA Submission: Data Integrity & DTO
Menguji proses pembuatan usulan pelatihan (TNA).

| ID | Skenario Uji | Detail Langkah | Ekspektasi Hasil | Status |
|:---|:---|:---|:---|:---|
| 3.1 | DTO Factory (Presentation) | Perhatikan tabel peserta di form usulan. | Nama, NIK, dan Unit muncul benar. Avatar Inisial & warna background konsisten. | |
| 3.2 | Validation: Required Fields | Klik "Submit" dengan form kosong. | Muncul pesan error validasi di tiap field (Nama Pelatihan, Kategori, dst). | |
| 3.3 | Validation: Participant Selection | Submit usulan tanpa memilih satu pun peserta. | Validasi gagal, muncul pesan "Minimal satu peserta harus dipilih". | |
| 3.4 | Logic: Training ID Generation | Simpan usulan dan cek ID yang dihasilkan (contoh: TNA-2024-0001). | Format ID benar, berurutan, dan tidak duplikat. | |
| 3.5 | File Upload (Document Service) | Upload file pendukung (PDF/Docx). | File tersimpan di storage private, path tercatat di DB. | |
| 3.6 | Transactional Store (Safety) | Mensimulasikan kegagalan sistem saat proses simpan (simulasi DB error). | Database Rollback: Tidak ada data "sampah" yang tertinggal di tabel TNA atau Peserta. | |

---

## 4. TNA Monitoring & Management
Menguji daftar usulan dan siklus hidup data.

| ID | Skenario Uji | Detail Langkah | Ekspektasi Hasil | Status |
|:---|:---|:---|:---|:---|
| 4.1 | Index Accuracy | Melihat daftar usulan di halaman utama monitoring. | Hanya menampilkan usulan yang dibuat oleh LC tersebut (Self-service isolation). | |
| 4.2 | Status Badge Logic | Mengubah status di DB secara manual (Pending, Approved, Rejected). | Badge warna di UI berubah otomatis mengikuti status (Yellow/Green/Red). | |
| 4.3 | Edit Logic (Pending) | Mengedit usulan yang statusnya masih 'Pending'. | Form terisi otomatis dengan data lama, berhasil di-update. | |
| 4.4 | Edit Protection (Final) | Mencoba akses URL edit untuk TNA yang sudah 'Approved'. | Sistem menolak akses edit karena data sudah bersifat final/dikunci. | |
| 4.5 | Delete Logic | Menghapus usulan status 'Pending'. | Data terhapus beserta relasi pesertanya (Cascading/Manual clean up). | |

---

## 5. UI/UX & Clean Logic (Alpine.js)
Memastikan interaksi di browser mulus dan tidak ada *memory leak*.

| ID | Skenario Uji | Detail Langkah | Ekspektasi Hasil | Status |
|:---|:---|:---|:---|:---|
| 5.1 | Modal Interaction | Buka/Tutup detail peserta di tabel. | Transisi smooth, data di dalam modal sesuai dengan baris yang diklik. | |
| 5.2 | Search Filtering | Mengetik nama peserta di kolom pencarian tabel (Client-side/Server-side). | Tabel terfilter secara instan tanpa reload halaman penuh. | |
| 5.3 | Responsive View | Akses dashboard LC melalui mobile/tablet. | Sidebar menjadi *drawer*, tabel bisa di-scroll horizontal tanpa merusak layout. | |

---

## 6. Error Handling & Custom Exceptions
Memastikan sistem tidak menampilkan "Whitescreen of Death".

| ID | Skenario Uji | Detail Langkah | Ekspektasi Hasil | Status |
|:---|:---|:---|:---|:---|
| 6.1 | Business Logic Exception | Memaksa sistem melanggar aturan bisnis (misal: duplikat peserta). | `TnaSubmissionException` dilempar dan ditangkap Controller, muncul Alert merah di UI. | |
| 6.2 | Graceful Degradation | Mencoba akses data yang sudah dihapus oleh user lain. | Muncul halaman 404 yang cantik atau redirect dengan pesan "Data tidak ditemukan". | |

---
**Catatan untuk Tester:**
Gunakan tools Inspect Element -> Network untuk memastikan tidak ada query DB yang bocor di respons JSON, dan pastikan DTO hanya mengirim data yang benar-benar dibutuhkan oleh View.
