# 🔬 LabManager — Sistem Peminjaman Alat Laboratorium IT

> Aplikasi web berbasis **Laravel 11** untuk mengelola peminjaman alat di laboratorium IT, dengan sistem persetujuan multi-level (Mahasiswa → Laboran → Kepala Lab).

---

## 📋 Daftar Isi

- [Tentang Proyek](#-tentang-proyek)
- [Fitur Utama](#-fitur-utama)
- [Alur Peminjaman](#-alur-peminjaman)
- [Role & Akses](#-role--akses)
- [Tech Stack](#-tech-stack)
- [Struktur Database](#-struktur-database)
- [Struktur Direktori](#-struktur-direktori)
- [Instalasi & Setup](#-instalasi--setup)
- [Akun Default (Seeder)](#-akun-default-seeder)
- [Alat yang Tersedia (Seeder)](#-alat-yang-tersedia-seeder)
- [Artisan Commands](#-artisan-commands)
- [Konfigurasi Penting](#-konfigurasi-penting)

---

## 📌 Tentang Proyek

**LabManager** adalah sistem informasi manajemen peminjaman alat laboratorium yang dirancang khusus untuk **Lab IT**. Sistem ini memungkinkan mahasiswa mengajukan peminjaman alat secara online, dengan alur persetujuan bertingkat berdasarkan kategori alat:

- **Alat Umum** → cukup disetujui oleh **Laboran**
- **Alat Khusus** → wajib disetujui **Laboran** *dan* **Kepala Lab**

Setiap transaksi dicatat dalam **audit log** (riwayat aktivitas) untuk keperluan tracking dan akuntabilitas.

---

## ✨ Fitur Utama

| Fitur | Keterangan |
|---|---|
| 🔐 Autentikasi | Login, logout, "ingat saya" (3 jam / 1 hari) |
| 👥 Multi-Role | Mahasiswa, Laboran, Kepala Lab |
| 📦 Manajemen Alat | CRUD alat, kategori, status, stok |
| 🖼️ Gambar Alat | Upload & tampilan gambar alat (via upload form + `EquipmentImageComposer`) |
| 📋 Katalog Alat | Halaman katalog bagi Mahasiswa untuk melihat alat tersedia |
| 📝 Pengajuan Peminjaman | Form peminjaman dengan validasi waktu (08:00–20:00) |
| ✅ Approval Multi-Level | Laboran + Kepala Lab (untuk alat khusus) |
| 🤝 Serah Terima | Laboran mengkonfirmasi penyerahan alat ke peminjam |
| 🔄 Pengembalian | Proses pengembalian dengan catatan kondisi alat |
| ❌ Penolakan | Laboran / Kepala Lab dapat menolak dengan alasan |
| 🚫 Pembatalan | Mahasiswa dapat membatalkan pengajuan yang masih pending |
| ⚠️ Laporan Masalah | Mahasiswa laporkan masalah alat, Laboran menangani/resolve |
| 🔔 Notifikasi | Notifikasi otomatis ke mahasiswa saat status berubah |
| 👤 Manajemen User | CRUD user oleh Laboran & Kepala Lab |
| 📄 Export Laporan | Export data peminjaman ke PDF (dompdf) & CSV |
| 📊 Dashboard | Dashboard spesifik per role dengan statistik |
| 📜 Audit Log | Riwayat setiap aksi tersimpan di `borrowing_logs` |
| ⏰ Overdue Otomatis | Scheduler harian menandai peminjaman yang terlambat |
| 🛡️ Race Condition Guard | Locking DB saat pengajuan untuk mencegah double-booking |
| 🔒 Session Management | Middleware custom untuk enforce session lifetime dinamis |
| 🌗 Dark Mode | Toggle mode gelap/terang dengan transisi animasi halus |
| 👤 Profil | User dapat mengedit nama, email, dan password |

---

## 🔄 Alur Peminjaman

### Alat Umum (Kategori: `umum`)

```
Mahasiswa → [pending] → Laboran Setujui → [ready_for_pickup]
         → Laboran Serah Terima → [active]
         → Laboran Proses Kembali → [completed]
```

### Alat Khusus (Kategori: `khusus`)

```
Mahasiswa → [pending] → Laboran Setujui → [approved_by_laboran]
         → Kepala Lab Setujui → [approved_by_kepala_lab]
         → Laboran Serah Terima → [active]
         → Laboran Proses Kembali → [completed]
```

### Status Peminjaman

| Status | Label | Keterangan |
|---|---|---|
| `pending` | Menunggu Persetujuan | Baru diajukan mahasiswa |
| `approved_by_laboran` | Disetujui Laboran | Menunggu persetujuan Kepala Lab (alat khusus) |
| `approved_by_kepala_lab` | Disetujui Kepala Lab | Menunggu serah terima oleh Laboran |
| `ready_for_pickup` | Siap Diambil | Alat umum sudah disetujui Laboran |
| `active` | Sedang Dipinjam | Alat sudah diserahkan ke peminjam |
| `completed` | Selesai | Alat sudah dikembalikan |
| `rejected` | Ditolak | Ditolak oleh Laboran / Kepala Lab |
| `overdue` | Terlambat | Melewati waktu pengembalian (otomatis via scheduler) |
| `issue_reported` | Ada Masalah | Status untuk pelaporan masalah |

---

## 👤 Role & Akses

### Mahasiswa
- Melihat katalog alat yang tersedia
- Mengajukan permintaan peminjaman
- Membatalkan pengajuan yang masih pending
- Melaporkan masalah pada alat yang sedang dipinjam
- Melihat riwayat peminjaman sendiri
- Melihat detail & status peminjaman
- Menerima notifikasi otomatis saat status berubah

### Laboran
- Melihat semua permintaan peminjaman
- Menyetujui / menolak permintaan (`pending`)
- Melakukan serah terima alat (`ready_for_pickup`, `approved_by_kepala_lab`)
- Memproses pengembalian alat (`active`, `overdue`)
- Menangani laporan masalah dari mahasiswa (`issue_reported`)
- Mengelola alat (tambah, edit, hapus, upload gambar)
- Mengelola user (tambah, edit, hapus)
- Export laporan peminjaman ke PDF & CSV
- Dashboard dengan statistik lengkap

### Kepala Lab
- Menyetujui / menolak peminjaman alat khusus (`approved_by_laboran`)
- Melihat semua data peminjaman
- Mengelola user (tambah, edit, hapus)
- Export laporan peminjaman ke PDF & CSV
- Dashboard dengan ringkasan persetujuan

---

## 🛠️ Tech Stack

### Backend

| Komponen | Versi / Detail |
|---|---|
| **PHP** | `^8.2` |
| **Laravel** | `^11.31` |
| **Laravel Breeze** | `^2.4` (Auth scaffolding) |
| **Laravel DomPDF** | `barryvdh/laravel-dompdf` (Export PDF) |
| **Laravel Tinker** | `^2.9` |
| **Database** | SQLite (default), dapat diubah ke MySQL/PostgreSQL |
| **Session Driver** | Database |
| **Queue Driver** | Database |
| **Cache Driver** | Database |
| **Timezone** | `Asia/Jakarta` |

### Frontend

| Komponen | Versi / Detail |
|---|---|
| **Vite** | `^6.0.11` (build tool) |
| **Tailwind CSS** | `^3.1.0` (utility classes) |
| **Alpine.js** | `^3.4.2` (reactive UI) |
| **Axios** | `^1.7.4` (HTTP client) |
| **PostCSS** | `^8.4.31` |
| **Autoprefixer** | `^10.4.2` |

### Dev Tools

| Komponen | Keterangan |
|---|---|
| **Laravel Pint** | Code style fixer (PSR-12) |
| **Laravel Pail** | Log viewer di terminal |
| **Laravel Sail** | Docker environment |
| **PHPUnit** | `^11.0.1` — unit & feature testing |
| **Faker PHP** | `^1.23` — data dummy untuk testing |
| **Mockery** | `^1.6` — mock objects |

---

## 🗄️ Struktur Database

### Tabel `users`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | integer | Primary key |
| `name` | varchar | Nama lengkap |
| `email` | varchar | Email (unique) |
| `password` | varchar | Password (bcrypt) |
| `role` | varchar | `mahasiswa` / `laboran` / `kepala_lab` |
| `email_verified_at` | datetime | Nullable |
| `remember_token` | varchar | Nullable |
| `created_at` / `updated_at` | datetime | Timestamps |

### Tabel `equipments`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | integer | Primary key |
| `name` | varchar | Nama alat |
| `description` | text | Deskripsi alat |
| `total_stock` | integer | Total stok keseluruhan |
| `available_stock` | integer | Stok yang tersedia untuk dipinjam |
| `category` | enum | `umum` / `khusus` |
| `status` | enum | `good` / `maintenance` |
| `image` | varchar | Nama file gambar (nullable, hasil upload) |
| `created_at` / `updated_at` | datetime | Timestamps |

### Tabel `borrowings`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | integer | Primary key |
| `user_id` | FK → users | Peminjam |
| `equipment_id` | FK → equipments | Alat yang dipinjam |
| `start_date` | time | Waktu mulai pinjam (HH:MM) |
| `end_date` | time | Waktu pengembalian (HH:MM) |
| `purpose` | text | Tujuan peminjaman |
| `status` | enum | Status peminjaman (lihat tabel status di atas) |
| `return_condition` | text | Kondisi alat saat dikembalikan (nullable) |
| `reject_reason` | text | Alasan penolakan (nullable) |
| `created_at` / `updated_at` | datetime | Timestamps |

> **Catatan:** `start_date` dan `end_date` menyimpan **waktu dalam sehari** (format `HH:MM`), bukan tanggal penuh. Sistem peminjaman berlaku untuk hari yang sama, dalam jam operasional **08:00 – 20:00 WIB**.

### Tabel `borrowing_logs`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | integer | Primary key |
| `borrowing_id` | FK → borrowings | Referensi peminjaman |
| `user_id` | FK → users | Siapa yang melakukan aksi |
| `action_description` | text | Deskripsi aksi yang dilakukan |
| `created_at` / `updated_at` | datetime | Timestamps |

### Tabel `notifications`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | integer | Primary key |
| `user_id` | FK → users | Penerima notifikasi |
| `title` | varchar | Judul notifikasi |
| `message` | text | Isi pesan notifikasi |
| `type` | varchar | Tipe: `success` / `info` / `danger` |
| `link` | varchar | URL terkait (nullable) |
| `read_at` | datetime | Waktu dibaca (nullable) |
| `created_at` / `updated_at` | datetime | Timestamps |

---

## 📁 Struktur Direktori

```
project-frame(lab IT)/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── MarkOverdueBorrowings.php   # Command scheduler overdue
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── AuthenticatedSessionController.php
│   │   │   ├── BorrowingController.php     # Logika peminjaman lengkap
│   │   │   ├── DashboardController.php     # Dashboard per-role
│   │   │   ├── EquipmentController.php     # CRUD alat + katalog
│   │   │   ├── NotificationController.php  # Daftar & mark-read notifikasi
│   │   │   ├── ProfileController.php       # Edit profil & password
│   │   │   ├── UserController.php          # CRUD user (Laboran & KepLab)
│   │   │   └── Controller.php
│   │   ├── Middleware/
│   │   │   ├── RoleMiddleware.php          # Guard akses berbasis role
│   │   │   └── EnforceSessionLifetime.php  # Dynamic session TTL
│   │   └── Requests/
│   │       └── Auth/
│   │           └── LoginRequest.php
│   ├── Models/
│   │   ├── Borrowing.php      # Model + status helpers
│   │   ├── BorrowingLog.php   # Model audit log
│   │   ├── Equipment.php      # Model + scope available()
│   │   ├── Notification.php   # Model + static send() helper
│   │   └── User.php           # Model + role helpers
│   ├── Providers/
│   │   └── AppServiceProvider.php          # Register EquipmentImageComposer
│   └── View/
│       └── Composers/
│           └── EquipmentImageComposer.php  # Inject $imageMap ke views alat
├── database/
│   ├── migrations/             # 9 migration files
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   ├── EquipmentSeeder.php # 15 alat lab IT
│   │   └── UserSeeder.php      # 3 akun default
│   └── database.db             # SQLite database file
├── resources/
│   └── views/
│       ├── auth/               # Halaman login
│       ├── borrowings/
│       │   ├── create.blade.php    # Form pengajuan peminjaman
│       │   ├── index.blade.php     # Daftar peminjaman
│       │   ├── report-pdf.blade.php # Template laporan PDF
│       │   └── show.blade.php      # Detail + aksi peminjaman
│       ├── dashboard/
│       │   ├── mahasiswa.blade.php
│       │   ├── laboran.blade.php
│       │   └── kepala-lab.blade.php
│       ├── equipments/
│       │   ├── catalog.blade.php  # Katalog untuk mahasiswa
│       │   ├── create.blade.php   # Form tambah alat
│       │   ├── edit.blade.php     # Form edit alat
│       │   └── index.blade.php    # Manajemen alat (laboran)
│       ├── errors/              # Halaman error custom
│       ├── notifications/
│       │   └── index.blade.php  # Daftar notifikasi user
│       ├── profile/
│       │   └── edit.blade.php   # Edit profil & password
│       ├── users/
│       │   ├── create.blade.php # Form tambah user
│       │   ├── edit.blade.php   # Form edit user
│       │   └── index.blade.php  # Daftar user
│       └── layouts/
│           ├── app.blade.php    # Layout utama (sidebar + navbar)
│           └── guest.blade.php  # Layout login
├── routes/
│   ├── web.php      # Semua route aplikasi
│   ├── auth.php     # Route autentikasi (Breeze)
│   └── console.php  # Jadwal scheduler
└── public/
    └── images/
        └── equipments/  # Gambar alat (.png)
```

---

## 🚀 Instalasi & Setup

### Persyaratan Sistem

- **PHP** >= 8.2 (dengan ekstensi: `pdo_sqlite`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `fileinfo`)
- **Composer** >= 2.x
- **Node.js** >= 18.x & **NPM**
- **Git**

### Langkah Instalasi

**1. Clone repository**
```bash
git clone <url-repository> project-lab-it
cd project-lab-it
```

**2. Install dependensi PHP**
```bash
composer install
```

**3. Salin file environment**
```bash
cp .env.example .env
```

**4. Generate application key**
```bash
php artisan key:generate
```

**5. Buat file database SQLite**
```bash
# Windows (PowerShell)
New-Item -ItemType File -Path database\database.db

# Linux / macOS
touch database/database.db
```

**6. Jalankan migrasi & seeder**
```bash
php artisan migrate --seed
```

**7. Install dependensi JavaScript**
```bash
npm install
```

**8. Jalankan development server**
```bash
# Jalankan semua sekaligus (Laravel + Vite)
composer run dev

# ATAU jalankan terpisah:
php artisan serve   # Backend → http://localhost:8000
npm run dev         # Frontend (Vite hot-reload)
```

**9. Akses aplikasi**

Buka browser dan kunjungi: **http://localhost:8000**

---

### Menggunakan Laragon (Windows)

Jika menggunakan **Laragon**, letakkan folder project di `C:\laragon\www\`, lalu:

1. Pastikan Laragon sudah berjalan (Apache/Nginx + PHP)
2. Akses via: `http://project-frame(lab IT).test` atau `http://localhost/project-frame(lab IT)/public`
3. Jalankan `npm run dev` untuk assets Vite

---

## 👥 Akun Default (Seeder)

Setelah menjalankan `php artisan migrate --seed`, tersedia 3 akun berikut:

| Role | Nama | Email | Password |
|---|---|---|---|
| 🎓 Mahasiswa | Ahmad Mahasiswa | `mahasiswa@lab.test` | `password` |
| 🔬 Laboran | Budi Laboran | `laboran@lab.test` | `password` |
| 🏛️ Kepala Lab | Dr. Citra Kepala Lab | `kepalalab@lab.test` | `password` |

---

## 🧰 Alat yang Tersedia (Seeder)

15 alat laboratorium IT telah di-seed dengan data realistis:

| No | Nama Alat | Kategori | Stok |
|---|---|---|---|
| 1 | Laptop ASUS ROG | Umum | 15 |
| 2 | Raspberry Pi 5 | Umum | 20 |
| 3 | Arduino Mega 2560 | Umum | 25 |
| 4 | Cisco Router 2901 | Khusus | 8 |
| 5 | Cisco Switch Catalyst 2960 | Khusus | 10 |
| 6 | Server Dell PowerEdge | Khusus | 3 |
| 7 | Monitor LG UltraWide 34" | Umum | 12 |
| 8 | VR Headset Meta Quest 3 | Khusus | 5 |
| 9 | 3D Printer Creality Ender | Khusus | 4 |
| 10 | Kabel UTP Cat6 + RJ45 Kit | Umum | 30 |
| 11 | GPU Workstation NVIDIA A4000 | Khusus | 2 |
| 12 | Sensor Kit IoT | Umum | 20 |
| 13 | Oscilloscope Digital Rigol | Khusus | 6 |
| 14 | Webcam Logitech C920 | Umum | 15 |
| 15 | External HDD 2TB | Umum | 10 (maintenance) |

---

## ⚙️ Artisan Commands

### Perintah Standar

```bash
# Jalankan migrasi
php artisan migrate

# Jalankan migrasi + seeder (reset data)
php artisan migrate:fresh --seed

# Cek status migrasi
php artisan migrate:status

# Lihat daftar route
php artisan route:list

# Bersihkan cache
php artisan optimize:clear
```

### Command Kustom

```bash
# Tandai peminjaman aktif yang terlambat sebagai 'overdue'
php artisan borrowings:mark-overdue

# Mode preview (tidak ada perubahan ke database)
php artisan borrowings:mark-overdue --dry-run
```

### Penjadwalan Otomatis (Scheduler)

Command `borrowings:mark-overdue` dijadwalkan berjalan setiap hari pukul **00:01** via `routes/console.php`.

Untuk mengaktifkan scheduler di server (Linux):
```bash
# Tambahkan ke crontab
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🔧 Konfigurasi Penting

### Database

File `.env` menggunakan **SQLite** secara default:
```env
DB_CONNECTION=sqlite
# File database: database/database.db
```

Untuk beralih ke **MySQL**:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lab_manager
DB_USERNAME=root
DB_PASSWORD=secret
```

### Jam Operasional Peminjaman

Sistem hanya menerima peminjaman antara **08:00 – 20:00 WIB** (satu hari yang sama).
Konfigurasi validasi ada di `BorrowingController::store()`.

### Session

| Kondisi | Durasi |
|---|---|
| Tanpa "Ingat Saya" | 180 menit (3 jam) |
| Dengan "Ingat Saya" | 1440 menit (1 hari) |

Session driver: `database` (tabel `sessions`).

### Gambar Alat

Gambar alat disimpan di `public/images/equipments/` dan dapat ditambahkan melalui **dua cara**:

1. **Upload via form** — Saat menambah/mengedit alat, Laboran bisa upload gambar (JPEG, PNG, GIF, WebP, max 2MB). Gambar disimpan di kolom `image` pada tabel `equipments`.
2. **Legacy imageMap** — Pemetaan nama alat → file gambar di-hardcode pada `EquipmentImageComposer` (`app/View/Composers/`), digunakan untuk data seeder bawaan.

Prioritas tampilan: Upload (kolom `image`) → Legacy imageMap → Placeholder.

---

## 🔒 Middleware & Keamanan

| Middleware | Fungsi |
|---|---|
| `auth` | Wajib login untuk akses halaman |
| `role:mahasiswa` | Membatasi akses khusus mahasiswa |
| `role:laboran` | Membatasi akses khusus laboran |
| `role:kepala_lab` | Membatasi akses khusus kepala lab |
| `role:laboran\|kepala_lab` | Akses gabungan (contoh: fitur tolak) |
| `EnforceSessionLifetime` | Logout otomatis saat sesi habis |

### Proteksi Race Condition

Saat mahasiswa mengajukan peminjaman, sistem menggunakan **`DB::transaction()` + `lockForUpdate()`** untuk mencegah dua mahasiswa meminjam alat yang sama secara bersamaan ketika stok hanya tersisa 1 unit.

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan **akademik / Lab IT**. Tidak ada lisensi open-source khusus yang diterapkan.

---

> Dibuat dengan ❤️ menggunakan **Laravel 11** — *The PHP Framework for Web Artisans*
