# 🎤 Panduan Presentasi LabManager — Fokus Fitur & Penggunaan Framework

Dokumen ini dirancang sebagai panduan presentasi terstruktur untuk menjelaskan aplikasi **LabManager** (Sistem Peminjaman Alat Lab IT) dengan menekankan **bagaimana fitur-fitur Framework Laravel 11 dimanfaatkan** secara optimal untuk membangun sistem yang aman, efisien, dan terstruktur.

---

## 📅 STRUKTUR PRESENTASI (Total Waktu: 15–20 Menit)

```
┌──────────────────────────────────────────────────────────────┐
│  1. Pendahuluan & Masalah (2 Menit)                           │
│  2. Mengapa Laravel 11? (2 Menit)                             │
│  3. Demo Aplikasi & Alur Bisnis (5 Menit)                     │
│  4. Bedah Fitur & Keunggulan Framework (7 Menit)              │
│     • MVC & Routing   • Eloquent & Relasi   • Race Condition  │
│     • Scheduler       • Middleware Custom   • View Composer   │
│  5. Keamanan & Kesimpulan (2 Menit)                           │
│  6. Sesi Tanya Jawab / Q&A (Beban Kritis)                     │
└──────────────────────────────────────────────────────────────┘
```

---

## 1. PENDAHULUAN & MASALAH (Durasi: 2 Menit)

### 🎙️ Skrip Presentasi (Pembuka):
> *"Halo semuanya, hari ini kami akan mempresentasikan **LabManager**, sebuah sistem informasi manajemen peminjaman alat Laboratorium IT. Aplikasi ini didesain khusus untuk menyelesaikan masalah administrasi manual di lab, seperti risiko kehilangan data, sulitnya melacak ketersediaan alat, lambatnya proses persetujuan bertingkat, serta potensi terjadinya tabrakan peminjaman (double-booking)."*

### Poin Utama Slide:
- **Latar Belakang**: Buku fisik peminjaman rawan hilang, rusak, dan tidak transparan.
- **Tantangan Utama**: Bagaimana membuat alur persetujuan yang bertingkat secara digital dengan manajemen stok yang real-time dan aman dari *race condition*.

---

## 2. TECH STACK & MENGAPA LARAVEL 11? (Durasi: 2 Menit)

### 🎙️ Skrip Presentasi:
> *"Untuk membangun aplikasi ini, kami memilih **Laravel 11** sebagai fondasi backend utama. Dibandingkan menulis PHP native, Laravel menyediakan struktur proyek modern berbasis MVC (Model-View-Controller) serta ekosistem bawaan yang matang. Kami memadukannya dengan **SQLite** sebagai database yang portabel, **Tailwind CSS** untuk antarmuka responsif dengan efek glassmorphism, serta **Alpine.js** untuk interaktivitas dinamis."*

### Poin Penting Penggunaan Framework Laravel 11:
1. **Struktur Project Lebih Bersih (Slimmer Skeleton)**: Laravel 11 memangkas file konfigurasi default sehingga aplikasi terasa lebih ringan dan fokus pada logika bisnis.
2. **Kombinasi Blade & Alpine.js**: Menghasilkan interaksi UI yang mulus tanpa kompleksitas Framework Single Page Application (SPA) seperti React atau Vue.
3. **Database Driver Agnostik**: Memudahkan migrasi dari SQLite (development) ke MySQL atau PostgreSQL (production) hanya dengan mengubah file `.env`.

---

## 3. DEMO APLIKASI & ALUR BISNIS (Durasi: 5 Menit)

*Lakukan demo aplikasi secara langsung dengan mengikuti 2 skenario utama ini:*

### Skenario 1: Peminjaman Alat Umum (1 Tingkat Approval)
1. **Login Mahasiswa** (`mahasiswa@lab.test`):
   - Buka menu **Katalog Alat**. Tunjukkan katalog yang ditarik secara real-time.
   - Lakukan pengajuan peminjaman **Laptop ASUS ROG** (alat kategori umum).
   - Tunjukkan bahwa stok langsung berkurang secara otomatis demi mencegah mahasiswa lain mengajukan peminjaman melebihi stok yang ada.
2. **Login Laboran** (`laboran@lab.test`):
   - Tunjukkan notifikasi masuk di navbar.
   - Setujui pengajuan tersebut. Status berubah menjadi **Siap Diambil (Ready for Pickup)**.
   - Klik tombol **Serah Terima** ketika mahasiswa mengambil alat (Status: **Sedang Dipinjam / Active**).
   - Setelah mahasiswa mengembalikan alat, klik **Proses Pengembalian** (Status: **Selesai / Completed**). Stok otomatis kembali bertambah.

### Skenario 2: Peminjaman Alat Khusus (2 Tingkat Approval)
1. **Login Mahasiswa**:
   - Ajukan peminjaman **Cisco Switch** (kategori khusus).
2. **Login Laboran**:
   - Laboran melakukan review dan klik **Setujui**. Status berubah menjadi **Disetujui Laboran**.
   - Jelaskan bahwa pada tahap ini mahasiswa belum bisa mengambil alat karena membutuhkan persetujuan akhir.
3. **Login Kepala Lab** (`kepalalab@lab.test`):
   - Kepala Lab menyetujui peminjaman tersebut. Status berubah menjadi **Disetujui Kepala Lab** (Siap Diambil).
4. **Login Laboran**:
   - Laboran memproses serah terima dan pengembalian seperti biasa.

---

## 4. BEDAH FITUR & KEUNGGULAN FRAMEWORK (Durasi: 7 Menit)

*Bagian ini adalah nilai tambah terbesar Anda. Jelaskan bagaimana fitur Laravel diimplementasikan di balik layar.*

### 🛠️ 4.1 Routing & Route Model Binding (Laravel Router)
- **Implementasi**: Di dalam `routes/web.php`, kita menggunakan parameter routing dinamis `{borrowing}`.
- **Keunggulan Framework**: Laravel secara otomatis melakukan **Route Model Binding**. Contohnya pada routing berikut:
  ```php
  Route::get('/borrowings/{borrowing}', [BorrowingController::class, 'show']);
  ```
  Di Controller, Laravel otomatis mencari baris database yang sesuai dan menyuntikkannya sebagai objek model:
  ```php
  public function show(Borrowing $borrowing) {
      return view('borrowings.show', compact('borrowing'));
  }
  ```
  *Tips Q&A*: *"Kita tidak perlu menulis query `SELECT * FROM borrowings WHERE id = $id` secara manual. Laravel yang menangani pencarian objek tersebut dan otomatis mengembalikan error 404 jika ID tidak ditemukan."*

### 🛠️ 4.2 Eloquent ORM: Relasi, Scopes, & Accessors
- **Eloquent Relationship**: Menghubungkan antar tabel dengan sintaksis OOP yang intuitif.
  - Model `Borrowing` memiliki relasi `belongsTo(User::class)` dan `belongsTo(Equipment::class)`.
  - Hal ini mempermudah pemanggilan data berelasi tanpa JOIN SQL yang rumit:
    ```php
    $borrowing->user->name;      // Mendapatkan nama peminjam
    $borrowing->equipment->name; // Mendapatkan nama alat
    ```
- **Local Query Scope**: Digunakan untuk membuat query kueri yang reusable. Contohnya pada `Equipment::scopeAvailable()`:
  ```php
  public function scopeAvailable($query) {
      return $query->where('status', 'good')->where('available_stock', '>', 0);
  }
  ```
  Di controller kita cukup memanggil `Equipment::available()->get()`.
- **Eloquent Accessor**: Mengubah format data database secara dinamis saat diakses. Contohnya mengubah string status database menjadi label UI:
  ```php
  // Mengakses $borrowing->status_label secara otomatis menjalankan match case
  public function getStatusLabelAttribute(): string {
      return match ($this->status) {
          'pending' => 'Menunggu Persetujuan',
          'active'  => 'Sedang Dipinjam',
          ...
      };
  }
  ```

### 🛠️ 4.3 Pencegahan Race Condition (Database Transaction & Lock)
- **Masalah**: Jika stok alat sisa 1, lalu 2 mahasiswa klik "Pinjam" di milidetik yang sama, query normal bisa membuat stok menjadi `-1` (double-booking).
- **Solusi Laravel**: Memanfaatkan `DB::transaction()` dan `lockForUpdate()` (Pessimistic Locking).
- **Kode di `BorrowingController@store`**:
  ```php
  $borrowing = DB::transaction(function () use ($validated) {
      // Mengunci baris alat agar request lain mengantre
      $equipment = Equipment::lockForUpdate()->find($validated['equipment_id']);

      if ($equipment->available_stock <= 0) {
          throw new \Exception('Stok alat tidak tersedia.');
      }

      $equipment->decrement('available_stock');
      return Borrowing::create([...]);
  });
  ```
- **Penjelasan**: *"Dengan fitur Transaction dari Laravel, seluruh operasi pengurangan stok dan pembuatan record peminjaman dijamin bersifat **atomic** (semua berhasil atau tidak sama sekali). Penggunaan `lockForUpdate` memastikan baris database tersebut dikunci selama proses transaksi berlangsung."*

### 🛠️ 4.4 Laravel Scheduler & Custom Artisan Commands (Otomatisasi)
- **Fitur**: Deteksi keterlambatan pengembalian secara otomatis.
- **Implementasi**: 
  1. Membuat Artisan Command Custom: `php artisan borrowings:mark-overdue`.
  2. Menjadwalkannya di `routes/console.php` menggunakan **Laravel Scheduler**:
     ```php
     Schedule::command(MarkOverdueBorrowings::class)
         ->dailyAt('00:01')
         ->withoutOverlapping()
         ->runInBackground();
     ```
- **Keunggulan Framework**: *"Alih-alih menyusun cron job mentah di server Linux yang sulit dikelola, Laravel memungkinkan kami mendefinisikan jadwal tugas otomatis langsung di dalam codebase menggunakan sintaks PHP yang bersih."*

### 🛠️ 4.5 Middleware Custom (RBAC & Dynamic Session)
Aplikasi menggunakan **Middleware** untuk menyaring request sebelum masuk ke Controller:
1. **Role Middleware (`RoleMiddleware`)**:
   Membatasi hak akses berdasarkan role pengguna.
   ```php
   // Di routes/web.php
   Route::middleware('role:laboran|kepala_lab')->group(function () { ... });
   ```
2. **Dynamic Session Middleware (`EnforceSessionLifetime`)**:
   Menerapkan durasi login dinamis:
   - Tanpa mencentang "Remember Me": sesi bertahan maksimal 3 jam.
   - Dengan mencentang "Remember Me": sesi bertahan hingga 24 jam.
   Middleware ini membaca konfigurasi sesi runtime dan otomatis memaksa logout jika pengguna tidak aktif melampaui batas waktu tersebut.

### 🛠️ 4.6 View Composer (Prinsip DRY - Don't Repeat Yourself)
- **Implementasi**: `EquipmentImageComposer` terdaftar di `AppServiceProvider`.
- **Konsep**: View Composer digunakan untuk menginjeksi data (peta gambar alat) langsung ke view Blade yang membutuhkan (`equipments.catalog` dan `equipments.index`) tanpa harus melewatkan variabel tersebut di setiap metode Controller terkait.
  ```php
  View::composer(['equipments.catalog', 'equipments.index'], EquipmentImageComposer::class);
  ```

---

## 5. KEAMANAN APLIKASI (Durasi: 2 Menit)

Jelaskan bagaimana Laravel menjaga keamanan sistem:
- **Autentikasi Aman**: Menggunakan paket resmi **Laravel Breeze** dengan enkripsi password menggunakan algoritma **Bcrypt** yang kuat secara default.
- **Proteksi CSRF**: Setiap form HTML secara otomatis diverifikasi oleh middleware `VerifyCsrfToken` Laravel untuk mencegah serangan pembajakan sesi formulir.
- **Pencegahan SQL Injection**: Seluruh query database yang dijalankan lewat Eloquent ORM secara default menggunakan teknik *PDO parameter binding*, sehingga input berbahaya dari pengguna tidak bisa mengeksekusi perintah SQL liar.
- **Ownership Verification**: Sistem memverifikasi bahwa mahasiswa hanya dapat melihat dan membatalkan transaksi milik mereka sendiri melalui pengecekan ID pengguna aktif (`auth()->id()`).

---

## 6. TIPS MENJAWAB PERTANYAAN DOSEN (Q&A)

Berikut adalah beberapa pertanyaan teknis yang sering ditanyakan dosen dan cara menjawabnya dengan menonjolkan keunggulan Laravel:

#### 💬 Q: Mengapa Anda membuat command scheduler sendiri untuk overdue? Mengapa tidak menggunakan trigger di database saja?
> **Jawab**: *"Trigger di database memang cepat, tetapi memiliki kekurangan dalam hal keterbacaan kode (logika bisnis terpisah dari aplikasi) dan keterbatasan aksi. Dengan menggunakan Artisan Command dan Laravel Scheduler, logika keterlambatan tetap berada di codebase aplikasi, mudah diuji (*unit testing*), dan kami bisa langsung memicu efek samping aplikasi lainnya, seperti **mengirimkan notifikasi langsung ke akun mahasiswa** dan **mencatat log audit** secara bersamaan saat status berubah menjadi overdue."*

#### 💬 Q: Bagaimana jika koneksi SQLite bermasalah saat transaksi rollback?
> **Jawab**: *"Laravel menggunakan driver PDO SQLite yang sepenuhnya mendukung fitur database ACID (Atomicity, Consistency, Isolation, Durability). Saat terjadi eksepsi di dalam closure `DB::transaction()`, Laravel akan mendeteksi kegagalan tersebut dan otomatis mengirim perintah `ROLLBACK` ke SQLite, mengembalikan stok alat ke kondisi semula sebelum transaksi dimulai."*

#### 💬 Q: Apa fungsi file `vite.config.js` di dalam proyek ini?
> **Jawab**: *"Vite adalah build tool modern yang direkomendasikan oleh Laravel 11. Vite berfungsi untuk melakukan kompilasi aset frontend seperti file JavaScript dan Tailwind CSS menjadi bundle production yang ringan dan cepat dimuat oleh browser. Selama proses development, Vite menyediakan fitur Hot Module Replacement (HMR) sehingga perubahan tampilan langsung terlihat tanpa reload halaman penuh."*

#### 💬 Q: Di mana Anda mendaftarkan middleware custom `role` Anda agar bisa dipanggil di route?
> **Jawab**: *"Pada Laravel 11, pendaftaran middleware dilakukan di dalam file `bootstrap/app.php` pada bagian konfigurasi `$middleware->alias()`. Kami mendaftarkannya dengan alias `'role'` sehingga bisa langsung disematkan pada grup rute di `routes/web.php`."*

---

> [!TIP]
> **Saran Tambahan**: Saat presentasi, tekankan bahwa keunggulan utama aplikasi Anda bukan hanya pada antarmuka visual yang indah, melainkan pada **kokohnya sistem di backend** (seperti penanganan *race condition*, penjadwalan otomatis, dan keamanan data) berkat pemanfaatan arsitektur Framework Laravel 11 secara maksimal.
