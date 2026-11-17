# Panduan Lengkap — Observatorium Langit Gelap (SK02)

Untuk Windows 10/11, Visual Studio Code, XAMPP, Composer, Laravel 12, MySQL.

## Daftar isi
1. Persiapan aplikasi
2. Memasang proyek
3. Mengatur database MySQL
4. Migration dan seeder
5. Menjalankan aplikasi
6. Panduan pengujian manual
7. Solusi error umum
8. Dokumentasi penggunaan dan penjelasan untuk dosen
9. Pengumpulan ke GitHub

---

## 1. Persiapan aplikasi

### 1.1 XAMPP (PHP + MySQL)
1. Unduh XAMPP dari apachefriends.org. **Pilih versi dengan PHP 8.2 atau lebih baru** (Laravel 12 membutuhkan PHP >= 8.2).
2. Instal di `C:\xampp` (hindari `C:\Program Files`).
3. Buka **XAMPP Control Panel**.

### 1.2 PATH PHP
1. Tekan `Win`, ketik **Edit the system environment variables**, lalu **Environment Variables**.
2. Pada *System variables* pilih `Path` > **Edit** > **New** > isi `C:\xampp\php` > OK semua.
3. **Tutup dan buka ulang** CMD/VS Code, lalu cek:

```bat
php -v
php -m
```

`php -v` harus menampilkan versi 8.2 atau lebih. Pada hasil `php -m` pastikan ada: `mbstring`, `openssl`, `pdo_mysql`, `fileinfo`, `ctype`, `json`, `tokenizer`, `xml`, `curl`.

### 1.3 Mengaktifkan ekstensi PHP
Buka `C:\xampp\php\php.ini` dengan VS Code, cari baris berikut dan hapus tanda `;` di depannya bila ada:

```ini
extension=curl
extension=fileinfo
extension=mbstring
extension=openssl
extension=pdo_mysql
extension=zip
extension=pdo_sqlite   ; hanya jika ingin menjalankan php artisan test
```

Simpan, lalu restart Apache di XAMPP bila sedang berjalan. Periksa ulang dengan `php -m`.

### 1.4 Composer
1. Unduh **Composer-Setup.exe** dari getcomposer.org dan jalankan.
2. Saat diminta memilih PHP, arahkan ke `C:\xampp\php\php.exe`.
3. Buka CMD baru:

```bat
composer -V
```

### 1.5 Visual Studio Code
Unduh dari code.visualstudio.com. Ekstensi yang membantu: *PHP Intelephense*, *Laravel Blade Snippets*.

---

## 2. Memasang proyek

1. Ekstrak `Observatorium_Langit_Gelap_SK02.zip` (klik kanan > Extract All).
2. Pindahkan foldernya ke lokasi yang pendek tanpa spasi, misalnya `C:\Proyek\`.
3. Buka VS Code > **File > Open Folder** > pilih folder proyek.
4. Buka terminal VS Code: **Terminal > New Terminal** (pastikan lokasinya di folder proyek yang berisi file `artisan`).

```bat
composer install
copy .env.example .env
php artisan key:generate
```

Penjelasan:
- `composer install` mengunduh seluruh library Laravel ke folder `vendor/` (butuh internet, 2-5 menit).
- `.env` adalah file konfigurasi lingkungan (database, URL, dll). File ini tidak ikut ke GitHub.
- `key:generate` mengisi `APP_KEY` di `.env` untuk enkripsi session.

Isi `.env` yang penting sudah benar secara bawaan:

```env
APP_NAME="Observatorium Langit Gelap"
APP_URL=http://127.0.0.1:8000
APP_LOCALE=id
APP_TIMEZONE=Asia/Jakarta

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=observatorium_langit_gelap
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

---

## 3. Mengatur database MySQL

1. Di XAMPP Control Panel klik **Start** pada **Apache** dan **MySQL** (keduanya hijau). Port MySQL bawaan 3306.
2. Buka http://localhost/phpmyadmin.
3. Klik **New** (Baru) > Nama database: `observatorium_langit_gelap` > Collation `utf8mb4_unicode_ci` > **Create**.
4. Pada `.env`, XAMPP bawaan memakai user `root` dengan password **kosong**. Jika Anda mengubah password root, isikan di `DB_PASSWORD=`.
5. Cek koneksi:

```bat
php artisan migrate:status
```

Jika tampil daftar migration (status *Pending*) berarti Laravel sudah terhubung ke MySQL. Jika muncul error `[1045]` atau `[2002]`, lihat bagian 7.

---

## 4. Migration dan seeder

```bat
php artisan migrate
php artisan db:seed
```

Atau sekaligus (cocok untuk mengulang dari nol):

```bat
php artisan migrate:fresh --seed
```

Periksa di phpMyAdmin, database `observatorium_langit_gelap` harus berisi tabel:
`kategori`, `sesi_pengamatan`, `detail_lokasi`, `perlengkapan`, `perlengkapan_sesi` (dan tabel bawaan Laravel: `users`, `cache`, `jobs`, `migrations`, dll).

Memeriksa foreign key: buka tabel `detail_lokasi` > tab **Structure** > **Relation view**. Di sana harus ada relasi
`sesi_pengamatan_id -> sesi_pengamatan.id`. Tabel `perlengkapan_sesi` memiliki dua relasi: ke `sesi_pengamatan` dan `perlengkapan`.
Anda juga dapat membuka **Designer** (menu database > More > Designer) untuk melihat garis relasi antartabel.

Data contoh setelah seeding: 6 kategori, 12 perlengkapan, 8 sesi pengamatan (masing-masing dengan 1 lokasi dan beberapa perlengkapan).

---

## 5. Menjalankan aplikasi

Frontend memakai CSS/JS biasa di folder `public/`, jadi **tidak perlu `npm install` maupun `npm run build`**, dan error "Vite manifest not found" tidak akan muncul.

```bat
php artisan serve
```

Buka http://127.0.0.1:8000 (Dashboard). Hentikan server dengan `Ctrl + C`.

Menu: **Dashboard**, **Sesi pengamatan**, **Lokasi pengamatan**, **Perlengkapan**, **Kategori**.

---

## 6. Panduan pengujian manual

Gunakan data berikut.

### 6.1 Menambah sesi (data utama + lokasi + perlengkapan sekaligus)
Menu **Sesi pengamatan > Tambah sesi**:

| Isian | Nilai |
|---|---|
| Kode sesi | SES-2026-009 |
| Nama sesi | Pengamatan Mars Oposisi |
| Objek sasaran | Mars |
| Kategori | Planet |
| Tanggal | (pilih tanggal hari ini atau setelahnya) |
| Waktu mulai / selesai | 20:00 / 23:30 |
| Status | Direncanakan |
| Nama lokasi | Observatorium Bosscha, Lembang |
| Lintang / Bujur | -6.8246 / 107.6164 |
| Ketinggian | 1310 |
| Catatan cuaca | Prakiraan cerah |
| Perlengkapan | centang Teleskop Refraktor Apo 102 mm (jumlah 1), Kamera CMOS Planetary ASI224MC, Mount EQ6-R Pro |

Klik **Simpan sesi**. Anda diarahkan ke halaman rincian yang memuat data sesi, lokasi, dan tabel perlengkapan.

### 6.2 Mengubah sesi
Pada daftar sesi klik **Edit** > ubah status menjadi *Berlangsung* dan nama lokasi > **Simpan sesi**.

### 6.3 Menghubungkan lokasi dengan sesi (menu Lokasi)
1. Hapus lokasi sebuah sesi lewat menu **Lokasi pengamatan > Hapus** (sesi tetap ada).
2. Klik **Tambah lokasi**; daftar sesi hanya berisi sesi yang belum punya lokasi. Pilih sesi tadi, isi data, simpan. Hubungan One-to-One kembali terbentuk.
3. Coba mengedit lokasi lewat tombol **Edit**.

### 6.4 Menambah dan mengedit perlengkapan yang digunakan
Buka **Edit** sebuah sesi > centang perlengkapan tambahan atau ubah kolom jumlah > simpan. Rincian sesi akan menampilkan daftar terbaru.

### 6.5 Menghapus hubungan perlengkapan tanpa menghapus data master
**Edit** sesi > hilangkan centang salah satu perlengkapan > simpan. Buka menu **Perlengkapan**: perlengkapan tersebut masih ada (kolom *Dipakai* berkurang satu).

### 6.6 Menghapus sesi
Klik **Hapus** pada sesi > konfirmasi. Detail lokasi ikut terhapus, tetapi data **Perlengkapan** dan **Kategori** tetap utuh.

### 6.7 Pencarian dan filter
- Ketik `Saturnus` pada kolom Cari > hanya sesi terkait yang tampil.
- Ketik `Bromo` > sesi yang lokasinya Bromo muncul (pencarian juga meliputi nama lokasi).
- Pilih kategori *Planet*, status *Selesai*, atau rentang tanggal > **Terapkan**. **Reset** mengosongkan filter.
- Menu Perlengkapan: filter jenis (teleskop, kamera, ...) dan kondisi.

### 6.8 Menguji validasi
| Skenario | Hasil yang diharapkan |
|---|---|
| Kirim form sesi kosong | Daftar pesan "wajib diisi" dan kolom bertanda merah |
| Kode sesi `SES-2026-001` (sudah ada) | "kode sesi sudah digunakan" |
| Lintang `95` | "lintang harus bernilai antara -90 sampai 90" |
| Bujur `200` | "bujur harus bernilai antara -180 sampai 180" |
| Tambah lokasi untuk sesi yang sudah punya lokasi | Ditolak (One-to-One) |
| Hapus perlengkapan yang masih dipakai sesi | Pesan error merah, data tidak terhapus |
| Hapus kategori yang masih dipakai sesi | Pesan error merah, data tidak terhapus |

### 6.9 Tes otomatis (opsional)
```bat
php artisan test
```
Membutuhkan ekstensi `pdo_sqlite`. Tes memeriksa penyimpanan sesi + lokasi + pivot, validasi, keunikan, rollback transaksi, pencarian/filter, dan proteksi hapus.

---

## 7. Solusi error umum

| Error | Penyebab | Solusi |
|---|---|---|
| `'php' is not recognized` | PATH PHP belum diatur | Tambahkan `C:\xampp\php` ke PATH (bagian 1.2), tutup dan buka ulang terminal |
| `'composer' is not recognized` | Composer belum terpasang atau terminal belum di-restart | Instal ulang Composer, restart VS Code/terminal |
| `SQLSTATE[HY000] [1045] Access denied` | Username/password MySQL salah | Samakan `DB_USERNAME` dan `DB_PASSWORD` di `.env` dengan MySQL Anda (XAMPP: `root` dan kosong), lalu `php artisan config:clear` |
| `SQLSTATE[HY000] [2002] Connection refused` | MySQL belum jalan atau port beda | Start **MySQL** di XAMPP; cek `DB_PORT` (3306). Jika MySQL gagal start karena port bentrok, ubah port di XAMPP dan `.env` |
| `No application encryption key has been specified` | `APP_KEY` kosong | `php artisan key:generate` lalu `php artisan config:clear` |
| `Vite manifest not found` | View memakai `@vite` tanpa build asset | Proyek ini tidak memakai Vite. Jika muncul, pastikan Anda memakai `layouts/app.blade.php` bawaan proyek, atau jalankan `npm install` lalu `npm run build` |
| `Class "..." not found` | Autoload belum diperbarui / salah nama class | `composer dump-autoload`, periksa namespace dan nama file |
| `Migration table not found` / `Base table or view not found: migrations` | Database baru kosong, atau koneksi ke database yang salah | Pastikan `DB_DATABASE` benar lalu `php artisan migrate` |
| `Table '...' doesn't exist` | Migration belum dijalankan | `php artisan migrate` (atau `migrate:fresh --seed`) |
| `404 Not Found` | URL salah, atau server tidak diarahkan ke folder `public` | Gunakan `php artisan serve` dan buka `http://127.0.0.1:8000`; jika memakai Apache, arahkan DocumentRoot ke `public/`. Cek `php artisan route:list` |
| `Port already in use` / `Failed to listen on 127.0.0.1:8000` | Port 8000 dipakai aplikasi lain | `php artisan serve --port=8080` lalu buka `http://127.0.0.1:8080` |
| `php artisan serve` langsung berhenti / error | Biasanya karena `vendor/` belum ada atau PHP < 8.2 | Jalankan `composer install`; cek `php -v` minimal 8.2; cek `.env` tersedia |
| `Your PHP version does not satisfy requirements` saat composer install | PHP < 8.2 | Instal XAMPP dengan PHP 8.2/8.3 |
| `could not find driver` | Ekstensi `pdo_mysql` belum aktif | Aktifkan di `php.ini` (bagian 1.3) |
| Tampilan berantakan / CSS tidak termuat | Server diakses lewat alamat berbeda dari `APP_URL`, atau cache lama | Buka lewat `http://127.0.0.1:8000`; `php artisan view:clear` |
| Perubahan `.env` tidak terbaca | Config ter-cache | `php artisan config:clear` |

Perintah pembersih umum: `php artisan optimize:clear`.

---

## 8. Dokumentasi penggunaan dan bahan penjelasan untuk dosen

### 8.1 Fungsi menu
- **Dashboard**: ringkasan angka, sesi terdekat, grafik batang status sesi, perlengkapan terpopuler dan yang perlu perhatian.
- **Sesi pengamatan**: data utama aplikasi. Satu form mengisi tiga hal: sesi, detail lokasi, dan perlengkapan.
- **Lokasi pengamatan**: mengelola detail lokasi secara terpisah (tambah ke sesi yang belum punya lokasi, ubah, hapus).
- **Perlengkapan**: data master alat (kode, nama, jenis, kondisi).
- **Kategori**: data master pengelompokan sesi.

### 8.2 Alur menambah sesi (end-to-end)
1. Browser `GET /sesi-pengamatan/create` > `SesiPengamatanController@create` menyiapkan daftar kategori dan perlengkapan > view `sesi/create` (memakai `_form`).
2. Submit `POST /sesi-pengamatan` > **`SesiPengamatanRequest`** memvalidasi (gagal = kembali ke form + pesan + old input).
3. `SesiPengamatanController@store` memanggil **`SesiPengamatanService::store()`**.
4. Service membuka `DB::transaction`: (a) `SesiPengamatan::create`, (b) `detailLokasi()->create`, (c) `perlengkapan()->sync([...jumlah])`.
5. Semua berhasil = commit; ada yang gagal = rollback (tidak ada data setengah jadi).
6. Redirect ke `sesi-pengamatan.show` dengan pesan sukses.

### 8.3 Alur menambah lokasi
`GET /lokasi/create` memuat hanya sesi yang `doesntHave('detailLokasi')` > `POST /lokasi` divalidasi `LokasiRequest` (aturan `unique` pada `sesi_pengamatan_id` menjaga One-to-One) > `LokasiService::store` (transaksi) > tersimpan di `detail_lokasi`.

### 8.4 Alur menghubungkan perlengkapan
Checkbox `perlengkapan_ids[]` dan input `jumlah[id]` > di service diubah menjadi `[id => ['jumlah' => n]]` > `sync()`. `sync` menambah relasi yang baru dipilih dan melepas yang tidak dipilih lagi **hanya pada tabel pivot**, sehingga data master aman.

### 8.5 Relasi database
| Relasi | Eloquent | Implementasi database |
|---|---|---|
| Kategori 1-N Sesi | `Kategori::hasMany`, `SesiPengamatan::belongsTo` | `sesi_pengamatan.kategori_id` FK (restrict on delete) |
| Sesi 1-1 Lokasi | `SesiPengamatan::hasOne`, `DetailLokasi::belongsTo` | `detail_lokasi.sesi_pengamatan_id` FK + **UNIQUE** (cascade on delete) |
| Sesi N-N Perlengkapan | `belongsToMany` di kedua model, `withPivot('jumlah')` | tabel pivot `perlengkapan_sesi`, UNIQUE gabungan kedua FK |

### 8.6 Struktur folder Laravel (yang relevan)
```
app/Http/Controllers   -> menerima request, memanggil service, mengembalikan view/redirect
app/Http/Requests      -> aturan validasi (Form Request)
app/Services           -> logika bisnis + transaksi
app/Models             -> Eloquent, relasi, scope pencarian/filter
bootstrap/app.php      -> konfigurasi aplikasi (routing, middleware) di Laravel 11/12
config/                -> konfigurasi (app.php: timezone Asia/Jakarta)
database/migrations    -> definisi tabel
database/seeders       -> data contoh
lang/id/validation.php -> pesan validasi bahasa Indonesia
public/                -> index.php, css/, js/ (document root)
resources/views        -> Blade: layouts, partials, components, view tiap modul
routes/web.php         -> routing (Route::resource)
tests/Feature          -> tes otomatis
docs/                  -> ERD dan Class Diagram
```

### 8.7 Konsep yang sering ditanyakan
- **Mengapa Service Layer?** Agar controller tipis (hanya HTTP) dan logika/transaksi bisa dipakai ulang serta diuji.
- **Mengapa `DB::transaction`?** Satu aksi menyimpan 3 jenis data; harus semua berhasil atau semua dibatalkan.
- **Mengapa Form Request?** Memisahkan validasi dari controller dan mudah dibaca.
- **Resource Controller**: satu baris `Route::resource` menghasilkan 7 route CRUD; lihat `php artisan route:list`.
- **Pencarian/filter**: *query scope* `scopeFilter` pada model, memakai `when()` agar kondisi hanya diterapkan bila input terisi.
- **Mengapa `waktu_selesai` boleh lebih kecil dari `waktu_mulai`?** Pengamatan malam sering melewati tengah malam.
- **Aplikasi ini tidak memakai login** karena tidak diminta di soal; menambahkannya bisa dilakukan dengan Laravel Breeze.

---

## 9. Pengumpulan ke GitHub

Nama repository: `UTS_NIM_Nama_SK02`, contoh `UTS_20260001_Andi_Pratama_SK02`.

1. Edit `README.md` (baris nama/NIM/kelas) dan pastikan folder `docs/` berisi ERD dan Class Diagram.
2. Buat akun di github.com, lalu **New repository** dengan nama sesuai format di atas. Pilih **Public** (agar dosen dapat mengakses) atau Private dan tambahkan dosen sebagai collaborator. Jangan centang "Add README".
3. Instal Git (git-scm.com), lalu di terminal folder proyek:

```bat
git init
git add .
git commit -m "UTS SK02 Observatorium Langit Gelap"
git branch -M main
git remote add origin https://github.com/USERNAME/UTS_NIM_Nama_SK02.git
git push -u origin main
```

4. Jika diminta login, gunakan *Personal Access Token* (GitHub > Settings > Developer settings > Tokens) sebagai password.
5. Pastikan di GitHub: ada `docs/` (PNG/PDF), `app/`, `database/`, `routes/`, `resources/`; dan **tidak** ada `.env` serta `vendor/` (sudah diabaikan oleh `.gitignore`).
6. Salin tautan repository dan kumpulkan lewat Edlink. Tes dari browser jendela *incognito* untuk memastikan dapat dibuka.

Catatan: soal juga meminta jawaban tulis tangan yang dipindai ke PDF dan unggahan ke SEVIMA. Ikuti petunjuk umum pada lembar soal untuk bagian tersebut.
