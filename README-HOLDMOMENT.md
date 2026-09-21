# HOLD' MOMENT — Photobooth Online (Laravel 11 + MySQL + Blade + Tailwind)

Aplikasi photobooth online langsung dari browser: isi biodata → buka kamera (WebRTC) →
pilih format/filter/timer → jepret → gabung jadi photostrip di Canvas → simpan via AJAX →
cetak / unduh / bagikan. Termasuk panel admin.

## 1. Syarat
- PHP >= 8.2, Composer, Node >= 18, MySQL (atau SQLite untuk coba cepat)

## 2. Instalasi
```powershell
cd "D:\.vscode\Projectphotobooth\Website"
composer install
cp .env.example .env   # di Windows: Copy-Item .env.example .env
php artisan key:generate
```

## 3. Database
### Opsi A — MySQL (sesuai spesifikasi)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=holdmoment
DB_USERNAME=root
DB_PASSWORD=
```
```powershell
php artisan migrate --seed
```

### Opsi B — SQLite (coba cepat, sudah terkonfigurasi di .env bawaan)
```powershell
New-Item -ItemType File -Path "database\database.sqlite" -Force
php artisan migrate:fresh --seed
```

Akun admin bawaan (dari seeder): `admin@holdmoment.test` / `password`

## 4. Storage & Frontend
```powershell
php artisan storage:link
npm install
npm run dev     # development (Vite)
# atau
npm run build   # production
```
> Catatan: layout Blade juga memuat Tailwind via CDN + konfigurasi `brand-*`
> yang sama dengan `tailwind.config.js`, sehingga tampilan langsung benar
> bahkan sebelum `npm run dev/build`.

## 5. Jalankan
```powershell
php artisan serve   # http://127.0.0.1:8000
```

## 6. Daftar Route
| URL | Keterangan |
|---|---|
| `/` | Home + hero + CTA `/camera` |
| `/about` | Statistik 99k+ / 12k+ / 4.5/5 + CTA gelap |
| `/how-it-works` | Timeline 7 langkah |
| `/camera` | Modal biodata → WebRTC + countdown + Canvas → `POST /photo/save` |
| `/login`, `/register` | Split card orange/putih |
| `/photo/{id}/result` | Cetak / Unduh PNG+PDF / Share |
| `/admin/dashboard`, `/admin/photos`, `/admin/templates` | Middleware `auth` + `admin` |

## 7. Alur Kamera (`camera.blade.php`)
1. Modal biodata (Nama + Sosmed, required) → disimpan di `sessionStorage`.
2. `getUserMedia()` → `<video>` live + `video.style.filter` untuk preview.
3. Countdown overlay 3/2/1 sesuai timer (3s/5s/10s) → capture ke `<canvas>` dengan `ctx.filter`.
4. Slot sesuai layout: `2x2`=4, `2x3`=6, `strip_1x4`=4. Thumbnail + preview strip digambar ulang tiap jepret.
5. Tombol **Selanjutnya** aktif saat slot penuh → render strip resolusi tinggi (header HOLD' MOMENT + footer nama/sosmed/tanggal) → `fetch POST /photo/save` (JSON base64) → redirect ke `/photo/{id}/result`.
6. Kamera butuh **HTTPS atau localhost** agar `getUserMedia` diizinkan browser.

## 8. Struktur Kode Penting
- `tailwind.config.js` — warna `brand-bg/orange/dark/card-light/text/muted` + font Playfair Display & Plus Jakarta Sans
- `routes/web.php` — publik, auth, kamera, admin (`auth`+`admin`)
- `app/Http/Controllers/{Page,Auth,Photobooth,Admin}Controller.php`
- `app/Http/Middleware/AdminMiddleware.php` (alias `admin` di `bootstrap/app.php`)
- `app/Models/{User,Template,Photo}.php`
- `database/migrations/*_create_{templates,photos}_table.php` + users (role, google_id)
- `resources/views/{layouts/app,components/navbar,home,about,how-it-works,camera,login,register,save,admin/*}.blade.php`
