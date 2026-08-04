# Sistem Prediksi IPK Akhir Mahasiswa Menggunakan ANN

Aplikasi web berbasis **Laravel** untuk memprediksi **Indeks Prestasi Kumulatif (IPK) akhir mahasiswa** berdasarkan nilai **Indeks Prestasi Semester (IPS) Semester 1 sampai Semester 5** menggunakan metode **Artificial Neural Network (ANN)** dengan algoritma **backpropagation**.

Sistem menyediakan dua jenis pengguna, yaitu **Administrator** dan **Operator**, dengan hak akses yang dipisahkan melalui middleware berbasis role.

## Versi terintegrasi skripsi dan Data Mhs.xlsx

Proyek ini sudah dilengkapi dengan dataset penelitian angkatan 2023 dan alur setup otomatis:

- 9.913 baris data mentah hasil konversi dari `Data Mhs.xlsx`.
- 682 mahasiswa angkatan 2023 pada populasi sumber.
- 612 sampel siap training ANN setelah validasi dan preprocessing.
- 5 input ANN: IPS Semester 1 sampai Semester 5.
- Target: IPK aktual periode terbaru yang tersedia.
- Training/testing default 80:20 dengan random seed 42.
- Evaluasi MAE dan MSE.
- Halaman **Dataset** untuk melihat pemetaan, kualitas data, dan mengunduh berkas audit.

### Setup paling cepat

Pastikan MySQL pada XAMPP/Laragon aktif, lalu buat konfigurasi `.env` dari `.env.example`.

**macOS/Linux**

```bash
./setup-macos-linux.sh
```

**Windows**

```bat
setup-windows.bat
```

Atau jalankan manual:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php scripts/create_database.php
php artisan thesis:setup --fresh --force
npm run build
php artisan serve
```

Akun awal:

```text
Admin    : admin / password123
Operator : operator / password123
```

> Catatan: file sumber hanya memuat empat periode akademik. IPS Semester 1 dan 2 pada dataset kompatibilitas merupakan estimasi yang diberi tanda `is_estimated`. Penjelasan lengkap tersedia di `DOKUMENTASI_PENYESUAIAN.md`.

---

## Daftar Isi

- [Tentang Aplikasi](#tentang-aplikasi)
- [Fitur Utama](#fitur-utama)
- [Hak Akses Pengguna](#hak-akses-pengguna)
- [Metode Artificial Neural Network](#metode-artificial-neural-network)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Konfigurasi Database](#konfigurasi-database)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Akun Awal](#akun-awal)
- [Struktur Data Utama](#struktur-data-utama)
- [Struktur Direktori](#struktur-direktori)
- [Daftar Halaman](#daftar-halaman)
- [Pengujian](#pengujian)
- [Perintah yang Sering Digunakan](#perintah-yang-sering-digunakan)
- [Keamanan](#keamanan)
- [Pemecahan Masalah](#pemecahan-masalah)
- [Status Pengembangan](#status-pengembangan)
- [Lisensi](#lisensi)

---

## Tentang Aplikasi

Sistem ini dikembangkan untuk membantu proses pengolahan data akademik dan prediksi IPK akhir mahasiswa. Variabel masukan yang digunakan adalah:

- IPS Semester 1
- IPS Semester 2
- IPS Semester 3
- IPS Semester 4
- IPS Semester 5

Target pelatihan model adalah **IPK akhir aktual mahasiswa**. Setelah model ANN selesai dilatih, sistem dapat menggunakan model aktif tersebut untuk memprediksi IPK akhir mahasiswa lain yang memiliki data IPS lengkap.

Hasil prediksi disimpan ke database beserta informasi model, pengguna yang menjalankan prediksi, waktu prediksi, nilai IPK aktual apabila tersedia, absolute error, dan squared error.

---

## Fitur Utama

### Manajemen autentikasi

- Login menggunakan email atau identitas akun yang terdaftar.
- Logout dari sistem.
- Pengalihan dashboard berdasarkan role.
- Pembatasan akses menggunakan middleware `auth` dan `role`.
- Status akun aktif dan nonaktif.

### Manajemen mahasiswa

- Menampilkan daftar mahasiswa.
- Menambah data mahasiswa.
- Melihat detail mahasiswa.
- Mengubah data mahasiswa.
- Menghapus data mahasiswa.
- Pencarian dan penyaringan data.

### Manajemen Data IPS

- Input IPS Semester 1 sampai Semester 5.
- Input IPK akhir aktual sebagai target training.
- Validasi rentang nilai akademik antara `0.00` sampai `4.00`.
- Pemeriksaan kelengkapan data secara otomatis.
- Penyimpanan pengguna yang memasukkan data.
- Pemisahan data yang siap untuk prediksi dan data yang layak untuk training.

### Training model ANN

- Menggunakan lima neuron input.
- Jumlah hidden neuron dapat dikonfigurasi.
- Menggunakan satu neuron output.
- Normalisasi data dengan metode min-max.
- Pembagian dataset training dan testing.
- Inisialisasi bobot dan bias.
- Pelatihan menggunakan algoritma backpropagation.
- Penyimpanan bobot, bias, parameter normalisasi, dan konfigurasi model.
- Evaluasi model menggunakan MAE dan MSE.
- Aktivasi otomatis terhadap model yang berhasil dilatih.
- Penyimpanan hasil evaluasi setiap dataset training dan testing.

### Prediksi IPK

- Memilih mahasiswa yang memiliki IPS Semester 1 sampai Semester 5 lengkap.
- Menggunakan model ANN aktif.
- Normalisasi data masukan.
- Proses forward propagation.
- Denormalisasi output prediksi.
- Menyimpan hasil prediksi ke database.
- Menghitung absolute error dan squared error apabila IPK aktual tersedia.
- Menampilkan riwayat hasil prediksi.

### Grafik dan laporan

- Visualisasi hasil prediksi.
- Ringkasan performa model.
- Perbandingan IPK prediksi dan IPK aktual.
- Rekapitulasi berdasarkan data yang tersedia.
- Ekspor laporan ke format CSV.

### Profil pengguna

- Mengubah nama.
- Mengubah alamat email.
- Mengubah password dengan verifikasi password saat ini.
- Menampilkan role dan status akun.

---

## Hak Akses Pengguna

### Administrator

Administrator memiliki akses untuk:

- Melihat dashboard sistem.
- Mengelola data mahasiswa.
- Mengelola seluruh Data IPS.
- Melatih dan mengaktifkan model ANN.
- Menjalankan prediksi IPK.
- Melihat seluruh hasil prediksi.
- Melihat grafik.
- Membuka dan mengekspor laporan.
- Memperbarui profil akun.

### Operator

Operator memiliki akses untuk:

- Melihat dashboard Operator.
- Memasukkan Data IPS mahasiswa.
- Menjalankan prediksi menggunakan model ANN aktif.
- Melihat riwayat dan detail prediksi yang diproses melalui akun Operator.
- Memperbarui profil akun.

Operator tidak memiliki akses untuk mengelola model ANN atau membuka halaman khusus Administrator.

---

## Metode Artificial Neural Network

Arsitektur dasar jaringan yang digunakan adalah:

```text
5 neuron input → hidden layer → 1 neuron output
```

Keterangan:

- Lima neuron input mewakili IPS Semester 1 sampai Semester 5.
- Hidden neuron dapat dikonfigurasi saat training.
- Satu neuron output menghasilkan nilai prediksi IPK akhir.
- Konfigurasi default hidden neuron adalah `8`, sehingga arsitektur default menjadi `5–8–1`.

### Alur training

1. Sistem mengambil Data IPS yang lengkap dan memiliki IPK akhir aktual.
2. Sistem memastikan jumlah dataset memenuhi batas minimum.
3. Dataset diacak menggunakan random seed.
4. Dataset dibagi menjadi data training dan testing.
5. Nilai IPS dan target IPK dinormalisasi dengan metode min-max.
6. Bobot dan bias jaringan diinisialisasi.
7. Sistem melakukan forward propagation.
8. Error dihitung berdasarkan output dan target.
9. Bobot dan bias diperbarui menggunakan backpropagation.
10. Training berhenti ketika mencapai jumlah epoch maksimum atau target error.
11. Model dievaluasi menggunakan MAE dan MSE.
12. Model, bobot, bias, parameter normalisasi, dan hasil evaluasi disimpan.
13. Model yang berhasil dilatih ditetapkan sebagai model aktif.

### Konfigurasi default training

| Parameter | Nilai default |
|---|---:|
| Input neuron | 5 |
| Hidden neuron | 8 |
| Output neuron | 1 |
| Learning rate | 0.1 |
| Maksimum epoch | 1000 |
| Target error | 0.001 |
| Data testing | 20% |
| Random seed | 42 |
| Minimum dataset | 5 |

### Metrik evaluasi

**Mean Absolute Error**

```text
MAE = rata-rata dari |nilai aktual - nilai prediksi|
```

**Mean Squared Error**

```text
MSE = rata-rata dari (nilai aktual - nilai prediksi)²
```

Nilai MAE dan MSE yang lebih kecil menunjukkan selisih prediksi terhadap nilai aktual yang lebih rendah.

---

## Teknologi yang Digunakan

### Backend

- PHP 8.3 atau lebih baru
- Laravel 13
- Laravel Tinker
- MySQL
- Eloquent ORM
- Blade Template Engine

### Frontend

- Tailwind CSS 4
- JavaScript
- Vite 8
- Chart.js
- SweetAlert2

### Pengujian dan pengembangan

- Pest
- PHPUnit
- Laravel Pint
- Laravel Pail

---

## Persyaratan Sistem

Pastikan perangkat sudah memiliki:

- PHP `8.3` atau lebih baru
- Composer
- MySQL atau MariaDB
- Node.js
- npm
- Git

Periksa versi perangkat lunak:

```bash
php -v
composer --version
mysql --version
node -v
npm -v
git --version
```

---

## Instalasi

### 1. Clone repository

```bash
git clone https://github.com/USERNAME-GITHUB/sistem-prediksi-ipk-ann.git
cd sistem-prediksi-ipk-ann
```

Ganti `USERNAME-GITHUB` dengan username GitHub pemilik repository.

### 2. Instal dependency PHP

```bash
composer install
```

### 3. Buat file konfigurasi environment

```bash
cp .env.example .env
```

### 4. Buat application key

```bash
php artisan key:generate
```

### 5. Instal dependency frontend

```bash
npm install
```

### 6. Konfigurasikan database

Buat database MySQL:

```sql
CREATE DATABASE sistem_prediksi_ipk_ann
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Kemudian sesuaikan konfigurasi `.env`.

### 7. Jalankan migration dan seeder

```bash
php artisan migrate --seed
```

Perintah tersebut akan membuat tabel aplikasi dan akun awal Administrator serta Operator.

### 8. Build aset frontend

```bash
npm run build
```

### 9. Jalankan aplikasi

```bash
php artisan serve
```

Buka aplikasi pada:

```text
http://127.0.0.1:8000
```

---

## Konfigurasi Database

Contoh konfigurasi `.env`:

```env
APP_NAME="Sistem Prediksi IPK ANN"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem_prediksi_ipk_ann
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

Setelah mengubah `.env`, bersihkan cache konfigurasi:

```bash
php artisan optimize:clear
```

---

## Menjalankan Aplikasi

### Mode pengembangan

Buka dua Terminal.

Terminal pertama:

```bash
php artisan serve
```

Terminal kedua:

```bash
npm run dev
```

Aplikasi dapat dibuka melalui:

```text
http://127.0.0.1:8000
```

### Menjalankan seluruh layanan pengembangan

Project juga menyediakan script Composer:

```bash
composer run dev
```

Perintah ini dapat menjalankan server Laravel, queue listener, log viewer, dan Vite secara bersamaan.

### Mode production build

```bash
npm run build
php artisan optimize
```

---

## Akun Awal

Akun berikut dibuat oleh seeder saat menjalankan:

```bash
php artisan migrate --seed
```

### Administrator

```text
Email    : admin@undipa.ac.id
Password : password123
Role     : admin
```

### Operator

```text
Email    : operator@undipa.ac.id
Password : password123
Role     : operator
```

> Segera ubah password akun awal setelah aplikasi berhasil dijalankan. Password yang diubah langsung pada database lokal tidak mengubah password default di file seeder.

---

## Struktur Data Utama

### Mahasiswa

Menyimpan identitas dan status mahasiswa yang digunakan dalam proses pengolahan Data IPS dan prediksi.

### Data IPS

Menyimpan:

- Mahasiswa
- IPS Semester 1
- IPS Semester 2
- IPS Semester 3
- IPS Semester 4
- IPS Semester 5
- IPK akhir aktual
- Status kelengkapan
- Waktu validasi
- Pengguna yang memasukkan data

### Model ANN

Menyimpan:

- Kode dan versi model
- Jumlah input neuron
- Hidden layer
- Jumlah output neuron
- Learning rate
- Epoch
- Target error
- Persentase training dan testing
- Random seed
- Bobot
- Bias
- Parameter normalisasi
- Jumlah dataset
- MAE
- MSE
- Status training
- Status model aktif
- Pengguna yang melakukan training
- Waktu training

### Dataset Model

Menyimpan pembagian dan hasil evaluasi dataset yang digunakan sebagai data training atau testing.

### Prediksi IPK

Menyimpan:

- Nomor prediksi
- Mahasiswa
- Data IPS
- Model ANN
- IPS Semester 1 sampai Semester 5
- Input ternormalisasi
- IPK hasil prediksi
- IPK aktual
- Absolute error
- Squared error
- Pengguna yang menjalankan prediksi
- Waktu prediksi

---

## Struktur Direktori

```text
sistem-prediksi-ipk-ann/
├── app/
│   ├── Enums/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   ├── Auth/
│   │   │   └── Operator/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   ├── Providers/
│   └── Services/
│       ├── AnnPredictionService.php
│       └── AnnTrainingService.php
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── admin/
│       ├── auth/
│       ├── layouts/
│       ├── operator/
│       └── partials/
├── routes/
│   ├── admin.php
│   ├── operator.php
│   └── web.php
├── storage/
├── tests/
├── .env.example
├── composer.json
├── package.json
└── README.md
```

---

## Daftar Halaman

### Halaman umum

| Method | URL | Keterangan |
|---|---|---|
| GET | `/` | Mengalihkan ke halaman login |
| GET | `/login` | Form login |
| POST | `/login` | Memproses autentikasi |
| POST | `/logout` | Keluar dari sistem |

### Halaman Administrator

| URL | Keterangan |
|---|---|
| `/admin/dashboard` | Dashboard Administrator |
| `/admin/mahasiswa` | Manajemen mahasiswa |
| `/admin/data-ips` | Manajemen Data IPS |
| `/admin/model-ann` | Training dan pengelolaan model ANN |
| `/admin/prediksi-ipk` | Proses prediksi IPK |
| `/admin/hasil-prediksi` | Riwayat hasil prediksi |
| `/admin/grafik` | Visualisasi data dan hasil |
| `/admin/laporan` | Laporan sistem |
| `/admin/laporan/export-csv` | Ekspor laporan CSV |
| `/admin/profil` | Profil Administrator |

### Halaman Operator

| URL | Keterangan |
|---|---|
| `/operator/dashboard` | Dashboard Operator |
| `/operator/data-ips` | Input Data IPS |
| `/operator/prediksi-ipk` | Proses prediksi IPK |
| `/operator/hasil-prediksi` | Riwayat hasil prediksi Operator |
| `/operator/hasil-prediksi/{id}` | Detail hasil prediksi |
| `/operator/profil` | Profil Operator |

---

## Pengujian

Jalankan seluruh pengujian:

```bash
php artisan test
```

Atau gunakan script Composer:

```bash
composer test
```

Periksa sintaks file PHP tertentu:

```bash
php -l app/Http/Controllers/Operator/ProfilController.php
```

Periksa seluruh route:

```bash
php artisan route:list
```

Periksa route Administrator:

```bash
php artisan route:list --name=admin
```

Periksa route Operator:

```bash
php artisan route:list --name=operator
```

Periksa kompilasi Blade:

```bash
php artisan view:clear
php artisan view:cache
```

Periksa build frontend:

```bash
npm run build
```

---

## Perintah yang Sering Digunakan

### Membersihkan seluruh cache Laravel

```bash
php artisan optimize:clear
```

### Menjalankan ulang migration dan seeder

Perintah berikut akan menghapus seluruh data:

```bash
php artisan migrate:fresh --seed
```

### Menjalankan seeder tanpa menghapus data

```bash
php artisan db:seed
```

### Membuat cache untuk production

```bash
php artisan optimize
```

### Memformat kode PHP

```bash
./vendor/bin/pint
```

### Membuka Laravel Tinker

```bash
php artisan tinker
```

---

## Keamanan

- Jangan memasukkan file `.env` ke Git.
- Jangan mengunggah password database, token, atau kredensial lain.
- Ubah password akun hasil seeder sebelum aplikasi digunakan.
- Gunakan `APP_DEBUG=false` pada lingkungan production.
- Gunakan password database yang kuat.
- Batasi akses route berdasarkan role.
- Validasi seluruh input dari pengguna.
- Gunakan HTTPS pada server production.
- Buat backup database secara berkala.

File dan direktori berikut sudah dikecualikan melalui `.gitignore`:

```text
.env
/vendor
/node_modules
/public/build
/storage/*.key
```

Sebelum melakukan push, periksa:

```bash
git status
git ls-files .env
```

Perintah `git ls-files .env` seharusnya tidak menampilkan output.

---

## Pemecahan Masalah

### Error `No application encryption key has been specified`

Jalankan:

```bash
php artisan key:generate
```

### Error koneksi database

Periksa bagian berikut pada `.env`:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem_prediksi_ipk_ann
DB_USERNAME=root
DB_PASSWORD=
```

Kemudian jalankan:

```bash
php artisan optimize:clear
```

### Tabel database belum tersedia

Jalankan:

```bash
php artisan migrate --seed
```

### Tampilan tidak berubah

Jalankan:

```bash
php artisan optimize:clear
php artisan view:clear
npm run build
```

Untuk pengembangan aktif, gunakan:

```bash
npm run dev
```

### Error `Vite manifest not found`

Jalankan:

```bash
npm install
npm run build
```

### Error `Call to undefined method`

Pastikan method yang digunakan route tersedia di controller:

```bash
php artisan route:list
```

Periksa method menggunakan Tinker:

```bash
php artisan tinker --execute='
dump(method_exists(
    \App\Http\Controllers\Operator\DashboardController::class,
    "index"
));
'
```

### Model ANN belum dapat digunakan

Pastikan:

- Minimal terdapat lima dataset lengkap.
- Setiap dataset memiliki IPS Semester 1 sampai Semester 5.
- Dataset training memiliki IPK akhir aktual.
- Training model berhasil.
- Model memiliki status `trained`.
- Model ditetapkan sebagai model aktif.
- Bobot, bias, dan parameter normalisasi tersimpan.

### Login gagal

Pastikan:

- Email dan password benar.
- Akun memiliki status aktif.
- Seeder sudah dijalankan.
- Role akun adalah `admin` atau `operator`.

---

## Status Pengembangan

Project ini dikembangkan untuk kebutuhan penelitian dan implementasi sistem prediksi IPK akhir mahasiswa menggunakan Artificial Neural Network.

Fitur utama yang sudah tersedia:

- Autentikasi
- Role Administrator dan Operator
- Manajemen mahasiswa
- Manajemen Data IPS
- Training ANN
- Evaluasi model
- Prediksi IPK
- Riwayat hasil prediksi
- Grafik
- Laporan CSV
- Profil pengguna

Pengembangan lanjutan dapat mencakup:

- Import data mahasiswa dari Excel
- Ekspor laporan PDF
- Pengujian model dengan dataset yang lebih besar
- Perbandingan ANN dengan metode prediksi lain
- Audit log aktivitas pengguna
- Deployment ke server production
- Dokumentasi API

---

## Kontribusi

Kontribusi dapat dilakukan melalui tahapan berikut:

1. Fork repository.
2. Buat branch fitur baru.

```bash
git checkout -b feature/nama-fitur
```

3. Commit perubahan.

```bash
git commit -m "Menambahkan nama fitur"
```

4. Push branch.

```bash
git push origin feature/nama-fitur
```

5. Buat Pull Request.

---

## Lisensi

Lisensi project belum ditentukan. Tambahkan file `LICENSE` sebelum mendistribusikan atau menggunakan project secara publik.

---

## Catatan

Repository sebaiknya tidak menyertakan:

- `.env`
- `vendor`
- `node_modules`
- File database lokal
- File ZIP audit atau backup
- Kredensial production
- File cache
- File sementara sistem operasi seperti `.DS_Store`

Pastikan repository hanya memuat source code dan file konfigurasi yang memang dibutuhkan untuk instalasi ulang.# sistem-prediksi-ipk-ann-terintegrasi-
