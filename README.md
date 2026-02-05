# SIDAPEG - Sistem Informasi Data Pegawai & Absensi Online 🚀

![Banner SIDAPEG](https://via.placeholder.com/1200x400?text=SIDAPEG+Premium+Dashboard+Management)

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![Leaflet](https://img.shields.io/badge/Leaflet-Maps-199900?style=for-the-badge&logo=leaflet&logoColor=white)](https://leafletjs.com/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)

---

## 📖 Tentang Aplikasi

**SIDAPEG** adalah solusi Sistem Informasi Manajemen Kepegawaian (SIMPEG) modern yang dirancang untuk instansi pemerintah maupun swasta. Aplikasi ini tidak hanya menangani pendataan pegawai secara administratif, tetapi juga mengintegrasikan sistem **Absensi Berbasis Geolocation (GPS)** dan **Manajemen Cuti Digital** dalam satu platform terpadu.

Dibangun dengan framework **Laravel 12** terbaru, SIDAPEG memprioritaskan keamanan (Security), kecepatan (Performance), dan kemudahan penggunaan (User Experience). Dengan fitur **Geofencing**, instansi dapat memastikan akurasi kehadiran pegawai berdasarkan lokasi fisik mereka secara real-time.

---

## ✨ Fitur Unggulan & Analisis Modul

### 🏢 1. Modul Administrator (Pusat Kendali)
Administrator memiliki wewenang penuh untuk mengatur seluruh ekosistem kepegawaian:

*   **Intelligence Dashboard**: 
    *   Visualisasi data statistik pegawai (Total, ASN, Non-ASN).
    *   **Widget Monitoring Absensi Hari Ini**: Daftar pegawai yang masuk hari ini lengkap dengan jam absen dan status (Hadir, Terlambat, Izin).
    *   **Aktivitas Terbaru**: Log pembuatan akun pengguna baru.
*   **Manajemen Pegawai Premium**:
    *   Formulir input detail (NIP, Nama, Jabatan, Unit Kerja, Alamat, dll).
    *   Upload foto profil dengan preview instan.
    *   **Laporan Excel Profesional**: Export data menggunakan template yang rapi dan terformat otomatis.
*   **Approval Cuti Terpusat**:
    *   Halaman detail pengajuan khusus (`show.blade.php`) untuk melihat alasan dan lampiran.
    *   Sistem verifikasi satu pintu: Setujui/Tolak dengan catatan feedback admin.
*   **Geofencing & Settings**:
    *   Konfigurasi titik koordinat kantor melalui peta interaktif.
    *   Pengaturan batas radius absensi dan jam masuk kerja.

### 👤 2. Modul Pegawai (Self-Service)
Dirancang untuk memudahkan pegawai dalam melakukan kewajiban administrasi harian:

*   **Absensi Berbasis GPS**:
    *   Deteksi lokasi otomatis menggunakan Browser Geolocation API.
    *   Validasi radius: Tombol absen hanya aktif jika berada di dalam jangkauan kantor.
    *   Wajib Foto Selfie sebagai bukti fisik kehadiran.
*   **E-Cuti (Paperless)**:
    *   Pengajuan cuti online tanpa perlu formulir fisik.
    *   Halaman riwayat cuti yang detail untuk memantau proses persetujuan.
    *   Fitur pembatalan pengajuan jika status masih 'Pending'.
*   **Manajemen Profil Mandiri**:
    *   Halaman profil khusus pegawai yang berbeda layout dengan admin.
    *   Update foto profil dan informasi dasar secara mandiri.

---

## 📂 Arsitektur Proyek (Analisis Teknis)

SIDAPEG mengikuti pola desain **MVC (Model-View-Controller)** yang ketat dengan beberapa penyesuaian untuk fitur-fitur khusus:

### 1. Struktur Controller
*   `Admin/`: Menangani logika manajemen data, approval, dan pengaturan sistem.
*   `Pegawai/`: Menangani logika personal pegawai seperti input absen dan pengajuan cuti.
*   `AuthController`: Pusat kendali autentikasi dan **Role-Based Redirect** setelah login.

### 2. Keamanan & Middleware
Aplikasi menggunakan Middleware custom `auth.admin` (alias dari `CheckAdminSession`) yang memastikan:
- Sesi login tetap terjaga.
- Pembatasan akses antar role (Pegawai tidak bisa masuk ke menu Settings admin).

### 3. Database Integrity
Skema database dirancang dengan constraint yang kuat untuk mencegah data corrupt:
- **Unique NIP & Username**: Menjamin tidak ada duplikasi identitas pegawai.
- **Enum Restriction**: Membatasi input status pegawai (ASN/Non ASN) dan status cuti.

---

## 🛠️ Stack Teknologi

| Komponen | Spesifikasi / Library | Deskripsi |
| :--- | :--- | :--- |
| **Backend** | **Laravel 12.x** | Framework PHP modern untuk aplikasi enterprise. |
| **Mapping** | **Leaflet.js** | Library open-source untuk peta interaktif. |
| **Utility** | **Carbon** | Library manajemen waktu (Date/Time manipulation). |
| **Export** | **PhpSpreadsheet** | Library pengolah file Excel (.xlsx). |
| **UI Kit** | **Bootstrap 5 & FontAwesome** | Framework CSS untuk tampilan responsif & premium. |
| **Dev Tool** | **Faker** | Digunakan untuk seeding data testing otomatis. |

---

## 🚀 Panduan Instalasi & Pengembangan

### 1. Persiapan Lingkungan
- Pastikan **PHP >= 8.2** terinstal.
- Aktifkan ekstensi `gd`, `zip`, `xml`, dan `intl` pada `php.ini`.
- Instal **Composer** dan **Node.js**.

### 2. Langkah Instalasi (Localhost)

```bash
# 1. Clone Project
git clone https://github.com/username/sidapeg.git
cd sidapeg

# 2. Install Dependensi
composer install
npm install

# 3. Setup Konfigurasi
cp .env.example .env
php artisan key:generate

# 4. Setup Database
# Buat database kosong (misal: sidapeg_db) di MySQL
# Sesuaikan DB_DATABASE di file .env

# 5. Migrasi & Seeding
# Ini akan membuat tabel dan mengisi 1 Admin + 10 Pegawai dummy
php artisan migrate --seed
```

### 3. Menjalankan Aplikasi

```bash
php artisan serve
```
Akses di: `http://localhost:8000`

---

## 🧪 Data Testing (Seeding)

Aplikasi ini dilengkapi dengan **UserPegawaiSeeder** yang menggunakan `Faker (id_ID)` untuk membangkitkan data pegawai yang realistis.

*   **Total Data Generasi**: 10 Pegawai.
*   **Format Data**: Nama Indonesia, Alamat Indonesia, NIP 8 digit acak.
*   **Credentials Pegawai**: 
    - Username: (Bisa dilihat di dashboard admin)
    - Password: `pegawai123`
*   **Credentials Admin**:
    - Username: `admin`
    - Password: `password123`

---

## 📚 Dokumentasi Skema Database (Deep Analysis)

Berikut adalah detail struktur tabel utama yang digunakan dalam sistem SIDAPEG:

### 1. Tabel `users` (Pusat Identitas)
Menyimpan kredensial dan biodata lengkap. Field `role` digunakan untuk RBAC (Role-Based Access Control).

| Field | Tipe | Deskripsi |
| :--- | :--- | :--- |
| `nip` | String(8) | ID unik pegawai (Wajib). |
| `jabatan` | String | Posisi saat ini. |
| `status_pegawai`| Enum | ASN atau Non ASN. |
| `tanggal_masuk` | Date | Tanggal mulai bekerja. |
| `role` | Enum | admin atau pegawai. |

### 2. Tabel `absensis` (Log Geolocation)
Menyimpan koordinat GPS dan foto selfie untuk validasi kehadiran.

| Field | Tipe | Deskripsi |
| :--- | :--- | :--- |
| `lokasi_masuk` | Text | Koordinat Lat & Long saat check-in. |
| `foto_masuk` | String | Path file foto selfie masuk. |
| `status` | Enum | Hadir, Izin, Sakit, Alpa. |

---

## 📡 Referensi API Internal

| Method | Endpoint | Fungsi |
| :--- | :--- | :--- |
| `POST` | `/pegawai/fitur/absensi/check-location` | Validasi radius GPS ke server. |
| `PATCH`| `/admin/fitur/cuti/{id}/status` | Mengubah status cuti (Approved/Rejected). |
| `GET`  | `/pegawai/export` | Download rekap pegawai ke format Excel. |

---

## ⚙️ SOP Konfigurasi Geofencing (Admin)

Agar fitur absensi berjalan akurat, Admin harus melakukan langkah berikut:
1.  Buka menu **Pengaturan Lokasi**.
2.  Gunakan peta interaktif untuk menentukan titik pusat kantor (Marker).
3.  Tentukan **Radius** (misal: 100 meter). Pegawai yang berada di jarak 101 meter tidak akan bisa menekan tombol absen.
4.  Atur **Jam Masuk** (misal: 08:00). Sistem secara otomatis menghitung `diffInMinutes` untuk menentukan status "Terlambat" (setelah 10 menit toleransi).

---

## 🔬 Deep Technical Overview (Core Logic)

Aplikasi SIDAPEG mengimplementasikan beberapa algoritma dan logika bisnis kritis yang memastikan validitas data:

### 1. Algoritma Validasi Geolocation (Geofencing)
Logika ini membandingkan koordinat GPS pegawai dengan titik pusat kantor yang disimpan di tabel `settings`. 
- **Sisi Server**: Menggunakan rumus *Haversine* untuk menghitung jarak antara dua titik koordinat (Latitude/Longitude) guna mencegah manipulasi sisi client.
- **Sisi Client**: Menggunakan Leaflet.js untuk plotting marker dan radius visual, memudahkan admin dalam kalibrasi lokasi.

### 2. Logika Deteksi Keterlambatan Otomatis
Sistem memproses `jam_masuk` secara real-time:
```php
$workStartTime = Carbon::createFromFormat('H:i', $work_start_time);
$lateThreshold = $workStartTime->addMinutes(10); // Toleransi 10 menit
if ($actualTime->greaterThan($lateThreshold)) {
    // Status = Terlambat
}
```
Hasil perhitungan ini ditampilkan secara visual di dashboard admin untuk memudahkan pengawasan tanpa perlu membuka laporan manual.

### 3. Arsitektur Role-Based Access Control (RBAC)
Meskipun menggunakan model `User` yang sama, aplikasi membedakan akses melalui field `role`:
- **Role Admin**: Memiliki akses ke prefix rute `/admin`, `/settings`, dan fitur manajemen seluruh pegawai.
- **Role Pegawai**: Dibatasi hanya pada prefix rute `/pegawai`, `/fitur/absensi`, dan `/fitur/cuti`.
Middleware `auth.admin` secara aktif memeriksa variabel session `role` dan `admin_logged_in` pada setiap request rute sensitif.

---

## 🎨 Analisis UI/UX & Desain Sistem

SIDAPEG dibangun dengan konsep **Premium Corporate Aesthetic** yang mengedepankan profesionalisme:

*   **Palet Warna**: Menggunakan kombinasi warna biru profesional (Primary), putih bersih (Background), dan aksen pink/ungu lembut untuk elemen dashboard agar tidak membosankan.
*   **Responsivitas**: 
    - **Sidebar Dinamis**: Sidebar otomatis mengecil pada layar tablet dan menjadi tombol toggle (Hamburger menu) pada layar mobile.
    - **Card Layout**: Informasi statistik menggunakan sistem grid yang menyesuaikan jumlah kolom berdasarkan lebar layar.
*   **Micro-Animations**: 
    - Hover effect pada tombol dan kartu statistik.
    - Transisi halus saat membuka modal atau berpindah halaman.
*   **Form Usability**: 
    - Input password dilengkapi dengan validasi minimal karakter.
    - Preview gambar otomatis sebelum upload foto profil atau bukti absen.

---

## 📔 Glossarium Istilah

Beberapa istilah teknis yang digunakan dalam proyek ini:

- **ASN**: Aparatur Sipil Negara (Pegawai tetap/PNS).
- **Non-ASN**: Pegawai Kontrak atau Honorer.
- **Geofencing**: Penggunaan GPS atau RF untuk menciptakan batas geografis virtual.
- **Seeding**: Proses pengisian database dengan data awal atau data testing secara otomatis.
- **Blade**: Mesin templating bawaan Laravel yang sangat efisien untuk merender HTML di server.
- **Vite**: Alat build modern yang digunakan untuk memproses aset CSS dan JavaScript dengan sangat cepat.
- **Eloquent**: Sistem ORM (Object-Relational Mapping) Laravel untuk berinteraksi dengan database menggunakan sintaks PHP yang elegan.

---

## 🛠️ Panduan Kontribusi & SOP Pengembangan

Kami sangat menghargai kontribusi dari komunitas. Berikut adalah standar prosedur yang harus diikuti oleh pengembang:

### 1. Standar Penulisan Kode (PSR-12)
- Pastikan semua file PHP mengikuti standar **PSR-12**.
- Gunakan nama variabel yang deskriptif (contoh: `$employeeCount` lebih baik daripada `$c`).
- Dokumentasikan fungsi yang kompleks menggunakan PHPDoc.

### 2. Manajemen Database & Migrasi
- Gunakan **Migrations** untuk setiap perubahan skema. Jangan mengedit tabel langsung via phpMyAdmin.
- Selalu update `DatabaseSeeder` jika ada field baru yang bersifat mandatory.
- Pastikan data dummy di `UserPegawaiSeeder` tetap relevan dengan skema terbaru.

### 3. Workflow Git & Versioning
- Gunakan pola *feature-branch*. Jangan melakukan push langsung ke branch `main`.
- Berikan pesan commit yang jelas (contoh: `feat: add location validation to checkout`).
- Lakukan integrasi rutin dengan branch utama untuk menghindari conflict besar.

---

## ❓ Troubleshooting (FAQ Spesialis Pengembang)

Berikut adalah beberapa solusi untuk masalah teknis yang mungkin muncul selama pengembangan:

**Q: Bagaimana jika saya ingin mereset data pegawai dummy?**
A: Jalankan perintah `php artisan migrate:fresh --seed`. **Peringatan**: Ini akan menghapus seluruh data di database dan mengulangi pengisian data awal sesuai seeder.

**Q: Error `Unable to create/write to directory` saat upload foto profil?**
A: Ini biasanya masalah permission pada server Linux/macOS. Jalankan:
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

**Q: Kenapa akurasi GPS sering melesat jauh (lebih dari 100 meter)?**
A: Akurasi sangat bergantung pada hardware dan kondisi sinyal perangkat. Disarankan menggunakan radius minimal **100 meter** untuk mengakomodasi deviasi sinyal GPS di area urban atau dalam gedung.

**Q: Error `Allowed memory size of ... bytes exhausted` saat install DomPDF?**
A: DomPDF dan dependensinya membutuhkan resource yang cukup besar saat proses `composer update`. Jika terjadi error memory atau proses hang, jalankan perintah berikut:
```bash
php -d memory_limit=-1 C:\ProgramData\ComposerSetup\bin\composer.phar update barryvdh/laravel-dompdf
```
*(Sesuaikan path `composer.phar` dengan lokasi instalasi Composer Anda).*

**Q: Kenapa saya tidak perlu khawatir lagi saat melakukan `git clone` di masa depan?**
A: Saya telah memperbarui file `composer.lock`. Sekarang, paket `barryvdh/laravel-dompdf` sudah terdaftar secara resmi di dalam "lock file". Artinya, saat Anda atau pengembang lain menjalankan `composer install` setelah melakukan clone, Composer tidak akan lagi menghitung dependensi dari nol (yang memakan banyak memory), melainkan akan langsung mengunduh versi yang sudah pasti berhasil diinstal.

**Q: Saya ingin menambahkan status absensi baru (contoh: 'Perjalanan Dinas')?**
1. Update constraint akun enum di file migrasi `create_absensis_table`.
2. Tambahkan logika pengolahan warna badge di file `Admin/AbsensiController` dan `dashboard.admin`.
3. Update view `pegawai/absensi/index` untuk menyertakan opsi status baru tersebut.

---

## 📅 Roadmap Pengembangan Masa Depan

Berikut adalah beberapa fitur yang direncanakan untuk pengembangan SIDAPEG selanjutnya:

1.  **Mobile App Integration**: Aplikasi Android/iOS khusus untuk mempermudah absensi via sensor wajah (Face Recognition).
2.  **Modul Penggajian (Payroll)**: Kalkulasi gaji otomatis berdasarkan jumlah kehadiran dan potongan keterlambatan.
3.  **Sistem Notifikasi Push**: Mengirim pengingat absen via WhatsApp API atau Push Notification browser.
4.  **Laporan Rekap Bulanan PDF**: Export laporan kehadiran dalam format PDF yang siap cetak (selain Excel).
5.  **Multi-Office Support**: Mendukung banyak lokasi kantor dengan radius geofencing yang berbeda-beda.

---

## 🤝 Dukungan & Komunitas

Jika Anda menemukan kendala dalam instalasi atau penggunaan, silakan ajukan melalui:
1.  **GitHub Issues**: Untuk melaporkan bug atau meminta fitur baru.
2.  **Pull Request**: Jika ingin berkontribusi langsung memperbaiki kode.

---

> Proyek ini dikembangkan dengan standar kode PSR-12 untuk menjamin keterbacaan dan skalabilitas jangka panjang.

<p align="center">
  <b>Developed with ❤️ for Advanced Employee Management System</b><br>
  &copy; 2026 Tim Pengembang SIDAPEG.
</p>


