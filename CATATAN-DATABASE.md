# Catatan Database — HOLD' MOMENT

Panduan menyiapkan database untuk teman satu tim. Ikuti urut dari atas.

## 1. Nyalakan & siapkan MariaDB/MySQL

```bash
sudo systemctl start mariadb
```

Masuk sebagai root lalu siapkan database (user `root` tanpa password — bawaan instalasi MySQL/MariaDB umumnya, tidak perlu bikin user baru):

```sql
CREATE DATABASE IF NOT EXISTS holdmoment CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

## 2. Atur `.env` (file ini TIDAK ikut git — wajib buat manual)

```bash
cp .env.example .env
php artisan key:generate
```

Isi bagian database seperti ini:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=holdmoment
DB_USERNAME=root
DB_PASSWORD=
```

> Catatan: `.env` itu per laptop (tidak ikut git). Isi di atas untuk laptop yang
> MySQL-nya pakai `root` tanpa password. Kalau di laptopmu user-nya beda,
> sesuaikan `DB_USERNAME`/`DB_PASSWORD` dengan akun MySQL masing-masing.

## 3. Migrasi + seed (perintah utama)

```bash
php artisan migrate --seed
php artisan storage:link
```

Isi seeder (`database/seeders/`):

| Seeder | Isi |
|---|---|
| `DatabaseSeeder` | Akun admin + 4 template bawaan, lalu memanggil dua seeder di bawah |
| `FrameTemplateSeeder` | 6 frame transparan (master PNG ikut git di `database/seeders/assets/frames/`, otomatis disalin ke storage) |
| `BackgroundSlotsSeeder` | **Hanya data** 6 background upload-an (koordinat lubang + radius + betulan layout, dicocokkan by nama). File gambar TIDAK ikut — harus upload manual dulu (lihat poin 4) |

Perubahan skema terbaru: kolom `slots` (JSON) di tabel `templates` — berisi `{fw, fh, radius, holes}` hasil ukur lubang foto. Jangan dihapus; halaman `/camera` memakainya agar foto pas di grid dan tidak gepeng.

## 4. Kalau 6 background tidak muncul datanya

Upload dulu ke-6 gambar via `/admin/templates` (login admin dulu) dengan **nama persis** seperti ini, lalu seed ulang:

- `Dream Bigger 2 x 2`, `Good Moments 2 x 2`, `PxT 2 x 2` (layout 2x2)
- `Dream Bigger 2 x 3`, `Good Moments 2 x 3`, `PxT 2 x 3` (layout 2x3)

```bash
php artisan db:seed --force
```

## 5. Akun admin bawaan

- Email: `admin@holdmoment.test`
- Password: `password`
- Login di `/login` → otomatis ke `/admin/dashboard`

## 6. Kalau error

| Pesan | Artinya | Obatnya |
|---|---|---|
| `Connection refused` | MariaDB mati | `sudo systemctl start mariadb` |
| `Access denied for user` | User/password salah atau belum dibuat | Ulangi poin 1, samakan dengan `.env` |
| Gambar template 404 | Symlink storage belum ada | `php artisan storage:link` |
