# Prompt Pengembangan Website Photobooth "HOLD' MOMENT"

Gunakan spesifikasi teknis, alur aplikasi, dan panduan desain berikut untuk membuat aplikasi web **Photobooth "HOLD' MOMENT"** dari awal menggunakan stack Laravel, MySQL, Blade, dan Tailwind CSS.

---

## 1. Stack Teknologi & Persyaratan Utama

- **Framework Backend:** Laravel 11 (atau Laravel 10)
- **Database:** MySQL
- **Template Engine:** Blade (`.blade.php`)
- **CSS Framework:** Tailwind CSS (disesuaikan dengan skema warna kustom)
- **Frontend Interactivity:** Alpine.js / Vanilla JavaScript (khusus integrasi WebRTC Camera & Canvas API)
- **Desain UI/UX:** Mengikuti secara presisi tata letak, elemen, dan palet warna pada sampel gambar acuan.

---

## 2. Sistem Desain & Palet Warna (Tailwind Custom Colors)

Konfigurasikan file `tailwind.config.js` dengan palet warna berikut agar presisi dengan gambar sampel:

```javascript
module.exports = {
  theme: {
    extend: {
      colors: {
        'brand-bg': '#F6F4EE',        // Warna latar belakang utama (Cream / Off-white)
        'brand-orange': '#D95B32',    // Warna aksen utama (Terracotta Orange)
        'brand-orange-hover': '#C44E27',
        'brand-dark': '#1C1C1C',      // Warna kartu gelap (Charcoal / Soft Black)
        'brand-card-light': '#EFECE6',// Warna kartu sekunder / input background
        'brand-text': '#1A1A1A',      // Warna teks utama
        'brand-muted': '#737373',     // Warna teks sekunder
      },
      fontFamily: {
        serif: ['"Playfair Display"', 'Georgia', 'serif'],
        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
      }
    }
  }
}
```

---

## 3. Struktur Navigasi & Header Komponen

**Komponen Navigation (`resources/views/components/navbar.blade.php`):**
- **Logo (Kiri):** Teks `HOLD' MOMENT` berpola klip film retro (font serif bold tebal bergaris putus-putus atas/bawah).
- **Pill Menu (Tengah):** Latar belakang putih bulat (`bg-white rounded-full px-6 py-2 shadow-sm`), berisi link:
  - `Home`
  - `Template`
  - `How it works`
  - `About us`
  - `Camera`
  - *Satu menu aktif memiliki warna teks `brand-orange` dan garis bawah.*
- **Action Buttons (Kanan):**
  - Button `Login`: Outline pill orange (`border border-brand-orange text-brand-orange rounded-full px-4 py-1.5 hover:bg-brand-orange hover:text-white transition`)
  - Button `Sign up`: Outline pill orange (`border border-brand-orange text-brand-orange rounded-full px-4 py-1.5 hover:bg-brand-orange hover:text-white transition`)

---

## 4. Rincian Spesifikasi Setiap Halaman

### Halaman 1: Home (`/`)
- **Hero Section:** Judul utama "Abadikan Momen Serumu Bersama HOLD' MOMENT", deskripsi singkat, serta CTA Button "Coba Sekarang!" yang mengarah ke `/camera`.
- **Fitur Utama:** Grid yang menampilkan keunggulan (Tanpa install aplikasi, pilihan template melimpah, cetak & unduh instan).
- **Preview Template:** Showcase strip foto populer.

---

### Halaman 2: About Us (`/about`)
*(Mengacu pada `About us.png`)*
- **Latar Belakang:** `bg-brand-bg`
- **Top Bar:** Navbar lengkap dengan indikator aktif pada "About us".
- **Judul Utama:** Font serif retro besar **"About us"** di tengah halaman.
- **Sub-deskripsi:** *"Kami percaya setiap momen seru bersama teman dan keluarga layak diabadikan dengan cara yang menyenangkan — cukup lewat browser, tanpa aplikasi tambahan."*
- **Metrik Statistik (3 Kolom):**
  - **99k+** (Teks besar `text-brand-orange font-bold text-5xl`) - *Foto diambil*
  - **12k+** (Teks besar `text-brand-orange font-bold text-5xl`) - *Pengguna Aktif*
  - **4.5/5** (Teks besar `text-brand-orange font-bold text-5xl`) - *Rating Pengguna*
- **Kartu Call-to-Action Gelap (`bg-brand-dark rounded-3xl p-10 text-white text-center`):**
  - Judul: **"Siap buat kenangan pertama mu?"**
  - Subtitle: *"Gratis dan langsung dari browsermu !"*
  - Button: **"Mulai sekarang"** (`bg-brand-orange hover:bg-brand-orange-hover text-white rounded-full px-8 py-3 font-semibold`) mengarah ke `/camera`.

---

### Halaman 3: How It Works (`/how-it-works`)
*(Mengacu pada `How it works.png`)*
- **Latar Belakang:** `bg-brand-bg`
- **Timeline Langkah-langkah (Vertical Timeline dengan garis konektor di sisi kiri):**
  1. **Icon Kamera Bulat Orange (`bg-brand-orange text-white`):**
     - Judul: **Buka halaman camera**
     - Keterangan: *Klik "Try it now!" dari home*
  2. **Icon Grid Bulat Orange:**
     - Judul: **Pilih format strip**
     - Keterangan: *2x2 atau 2x3, lewat panel di sisi kiri*
  3. **Icon Filter Bulat Orange:**
     - Judul: **Pilih filter kamera**
     - Keterangan: *Normal, B&W, Vintage atau warm*
  4. **Icon Hourglass/Timer Bulat Orange:**
     - Judul: **Atur waktu hitung mundur**
     - Keterangan: *3 detik, 5 detik, dan 10 detik*
  5. **Icon Kamera Foto Bulat Orange:**
     - Judul: **Jepret foto mu**
     - Badges: `[✓ Klik "Try it now!" dari home]` dan `[🔄 Kurang puas? Retake]`
  6. **Icon Kuas/Tema Bulat Orange:**
     - Judul: **Pilih tema strip**
     - Keterangan: *Klik "Next", lalu pilih desain photostrip favoritmu*
  7. **Icon Checkmark Gelap (`bg-black text-white`):**
     - **Kartu Hitam Gelap (`bg-brand-dark rounded-2xl p-6 text-white`):**
       - Title: **Strip-mu siap!**
       - 3 Opsi Tombol Aksi di dalam kartu: `Cetak` (Icon printer), `Simpan` (Icon unduh), `Bagikan` (Icon share).

---

### Halaman 4: Camera / Photobooth Session dengan Form Biodata (`/camera`)
- **Tahap 1: Modal / Form Biodata Pengguna (Sebelum Kamera Aktif):**
  - Tampilkan form input dengan desain kartu krem (`bg-white rounded-3xl p-8 shadow-xl max-w-md mx-auto`):
    - Input 1: **Nama Lengkap** (`required`)
    - Input 2: **Username Media Sosial / Instagram** (`required`)
    - Button: **"Lanjutkan ke Kamera"** (`bg-brand-orange text-white rounded-full py-3 font-semibold w-full`)
  - Biodata ini disimpan sementara di JS state/session untuk disimpan bersama hasil foto.
- **Tahap 2: Live Camera Viewport:**
  - Menggunakan API HTML5 `navigator.mediaDevices.getUserMedia({ video: true })`.
  - Overlay hitung mundur (Countdown overlay: 3, 2, 1) sebelum foto diambil.
- **Panel Sisi Kiri (Control Panel):**
  - Pilihan Format Grid: `2x2`, `2x3`, atau `Strip 1x4`.
  - Pilihan Filter Canvas: `Normal`, `B&W (Grayscale)`, `Vintage (Sepia)`, `Warm`.
  - Timer Delay: `3s`, `5s`, `10s`.
- **Panel Sisi Kanan / Bawah:**
  - Tombol **"Jepret Foto"** / **"Mulai Sesi"**.
  - Thumbnail hasil foto sementara.
  - Tombol **"Selanjutnya"** setelah seluruh slot foto terisi (menggabungkan gambar + data biodata, lalu mengirim via AJAX/Fetch ke Backend).

---

### Halaman 5: Login (`/login`)
*(Mengacu pada `Login.png`)*
- **Layout:** Card terbagi 2 bagian (Split Card dengan rounded corners `rounded-3xl shadow-xl overflow-hidden`):
  - **Panel Kiri (Terracotta Orange `bg-brand-orange text-white p-12`):**
    - Logo `HOLD' MOMENT` di pojok kiri atas.
    - Teks Headline Besar: **"Abadikan momen serumu dalam sekejap."**
    - Subteks: *"Masuk untuk mengakses riwayat foto dan template favoritmu."*
  - **Panel Kanan (Putih `bg-white p-12`):**
    - Judul: **"Selamat datang kembali!"**
    - Subtitle: *"Masuk untuk melanjutkan sesi photobooth-mu"*
    - **Form Input:**
      - Label `Email`: Input text pill (`rounded-xl border border-gray-300 px-4 py-2.5 w-full`), placeholder `nama@gmail.com`.
      - Label `Password`: Input password pill (`rounded-xl border border-gray-300 px-4 py-2.5 w-full`), placeholder `Password`.
      - Link Kanan: `"Lupa kata sandi?"` (warna orange).
      - Tombol Utama: **"Masuk"** (`bg-brand-orange text-white rounded-full py-3 font-semibold hover:bg-brand-orange-hover`).
      - Tombol Alternatif: **"G Lanjutkan dengan Google"** (Outline pill button).
      - Footer Link: *"Belum punya akun? "* **`Daftar`** (teks orange bold).

---

### Halaman 6: Sign Up (`/register`)
*(Mengacu pada `Sign up.png`)*
- **Layout:** Split Card serupa dengan halaman Login.
  - **Panel Kiri (`bg-brand-orange text-white p-12`):**
    - Logo `HOLD' MOMENT`.
    - Teks Headline Besar: **"Mulai buat kenangan seru bersama teman."**
    - Subteks: *"Daftar gratis dan simpan semua hasil foto strip favoritmu."*
  - **Panel Kanan (`bg-white p-12`):**
    - Judul: **"Buat akun baru"**
    - Subtitle: *"Gratis, cukup beberapa detik untuk mulai"*
    - **Form Input:**
      - Label `Nama Lengkap`: Input text pill, placeholder `Masukan nama lengkap anda`.
      - Label `Email`: Input text pill, placeholder `nama@gmail.com`.
      - Label `Password`: Input password pill, placeholder `Password`.
      - Link Kanan: `"Lupa kata sandi?"` (warna orange).
      - Tombol Utama: **"Daftar"** (`bg-brand-orange text-white rounded-full py-3 font-semibold hover:bg-brand-orange-hover`).
      - Tombol Alternatif: **"G Daftar dengan Google"** (Outline pill button).
      - Footer Link: *"Sudah punya akun? "* **`Masuk`** (teks orange bold).

---

### Halaman 7: Save / Final Result Page (`/photo/{id}/result`)
*(Mengacu pada `Save.png`)*
- **Latar Belakang:** `bg-brand-bg` dengan logo `HOLD' MOMENT` di pojok kiri atas.
- **Sisi Kiri:**
  - Headline Teks Serif Besar: **"Foto-mu sudah jadi!"**
  - Subtitle Teks Bold: **"Pilih opsi untuk ... Kenangan"**
- **Sisi Kanan (Kartu Modal Melayang Warna Putih `bg-white rounded-3xl p-8 shadow-2xl`):**
  - Header: **"Pilih Tindakan"** (`font-bold text-lg mb-4`)
  - **Opsi 1 - Cetak Strip:**
    - Box Krem Light (`bg-brand-card-light rounded-2xl p-4 flex items-center space-x-4 mb-3`)
    - Icon Printer Orange.
    - Teks: **Cetak Strip** | *Cetak langsung dari sini!*
  - **Opsi 2 - Simpan foto:**
    - Box Krem Light (`bg-brand-card-light rounded-2xl p-4 flex items-center space-x-4 mb-3`)
    - Icon Download Orange.
    - Teks: **Simpan foto** | *Simpan sebagai foto atau PDF*
  - **Opsi 3 - Bagikan ke media sosial:**
    - Box Krem Light (`bg-brand-card-light rounded-2xl p-4 mb-6`)
    - Icon Share Orange.
    - Teks: **Bagikan ke media sosial** | *Langsung dari sini*
    - **Baris Icon Sosmed (Putih Bulat):** Icon Instagram, Icon WhatsApp, Icon Link Share.
  - **Footer Action Buttons:**
    - Tombol Kiri: **"← Ambil Ulang"** (Outline pill `border border-gray-400 text-gray-700 rounded-full px-6 py-2.5 hover:bg-gray-100`) mengarah ke `/camera`.
    - Tombol Kanan: **"Selesai"** (`bg-brand-orange text-white rounded-full px-12 py-2.5 font-semibold hover:bg-brand-orange-hover`) mengarah ke `/`.

---

### Halaman 8: Panel Admin (`/admin/dashboard`)
- **Akses:** Khusus user dengan role `admin` (menggunakan middleware auth & admin check).
- **Tema Visual:** Tetap menggunakan warna konsisten (`brand-bg`, `brand-orange`, `brand-dark`).
- **Fitur Utama Admin Panel:**
  1. **Statistik Ringkasan:** Card total pengguna, total foto yang diambil, dan statistik harian.
  2. **Manajemen Hasil Foto Sesi (Data Submisio):**
     - Tabel daftar foto yang diambil dari kamera.
     - Menampilkan kolom: ID, Nama Pengunjung (dari Form Biodata), Username Media Sosial, Preview Foto Strip, Tanggal/Waktu Sesi, Aksi (Download, Delete).
  3. **Manajemen Frame/Template:**
     - CRUD Template Frame (Upload frame PNG transparan, tentukan tipe layout `2x2`, `2x3`, `1x4`).
  4. **Manajemen User Account:**
     - Tabel kelola pengguna terdaftar dan ganti role (Admin / User).

---

## 5. Skema Database MySQL (`database/migrations/`)

```php
// Migration Users
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password')->nullable();
    $table->string('google_id')->nullable();
    $table->enum('role', ['user', 'admin'])->default('user');
    $table->timestamps();
});

// Migration Templates
Schema::create('templates', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('layout_type'); // 2x2, 2x3, strip_1x4
    $table->string('frame_image'); // path frame overlay
    $table->timestamps();
});

// Migration Photos (Menyimpan foto & biodata visitor)
Schema::create('photos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
    $table->string('visitor_name');      // Biodata Nama
    $table->string('visitor_social');    // Biodata Media Sosial (Instagram/TikTok/dll)
    $table->foreignId('template_id')->nullable()->constrained();
    $table->string('result_image_path'); // Path file hasil akhir foto strip
    $table->timestamps();
});
```

---

## 6. Contoh Route Laravel (`routes/web.php`)

```php
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PhotoboothController;
use App\Http\Controllers\AdminController;

// Public Pages
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/how-it-works', [PageController::class, 'howItWorks'])->name('how-it-works');

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Photobooth Camera & Result Process
Route::get('/camera', [PhotoboothController::class, 'camera'])->name('camera');
Route::post('/photo/save', [PhotoboothController::class, 'savePhoto'])->name('photo.save');
Route::get('/photo/{id}/result', [PhotoboothController::class, 'result'])->name('photo.result');

// Admin Panel Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/photos', [AdminController::class, 'photos'])->name('admin.photos');
    Route::delete('/photos/{id}', [AdminController::class, 'deletePhoto'])->name('admin.photos.delete');
    Route::get('/templates', [AdminController::class, 'templates'])->name('admin.templates');
    Route::post('/templates', [AdminController::class, 'storeTemplate'])->name('admin.templates.store');
});
```

---

## 7. Instruksi Eksekusi untuk OpenCode / AI Code Assistant

1. Buat controller `PageController`, `AuthController`, `PhotoboothController`, dan `AdminController`.
2. Buat middleware `AdminMiddleware` untuk memproteksi rute `/admin/*`.
3. Implementasikan komponen Blade layout utama `resources/views/layouts/app.blade.php` dengan tag `<head>` berisi penyiapan Tailwind CSS dan font Google (`Playfair Display` & `Plus Jakarta Sans`).
4. Buat file view Blade untuk setiap rute: `home.blade.php`, `about.blade.php`, `how-it-works.blade.php`, `camera.blade.php`, `login.blade.php`, `register.blade.php`, `save.blade.php`, serta layout admin di `admin/dashboard.blade.php`.
5. Terapkan warna kustom Tailwind (`brand-bg`, `brand-orange`, `brand-dark`, `brand-card-light`) secara konsisten sesuai desain acuan.
6. Buat skrip JavaScript/Alpine.js pada `camera.blade.php` untuk menangani:
   - Pop-up modal pengisian form biodata (Nama & Sosmed).
   - Pengaktifan webcam stream.
   - Pengambilan foto dengan countdown timer & penggabungan frame di Canvas HTML5.
   - Pengiriman payload JSON / FormData berisi `biodata` dan `image base64/blob` ke rute `photo.save`.