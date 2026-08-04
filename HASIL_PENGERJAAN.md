# Hasil Pengerjaan Integrasi

## Status

Aplikasi sudah disesuaikan dengan naskah skripsi dan data `Data Mhs.xlsx`.

## Integrasi yang selesai

- Dataset angkatan 2023 telah diproses menjadi 612 sampel siap training.
- Data mahasiswa, IPS Semester 1-5, dan IPK aktual terhubung ke tabel aplikasi.
- Metadata sumber data, metode preprocessing, periode sumber, dan status estimasi disimpan untuk audit.
- Halaman Admin **Dataset** ditambahkan untuk memeriksa ringkasan, pemetaan variabel, data dikeluarkan, dan unduhan berkas audit.
- Perintah `php artisan thesis:setup --fresh --force` menjalankan migrasi, seeding, import dataset, dan training model ANN awal.
- ANN memakai 5 input, 8 hidden neuron, 1 output, pembagian 80:20, random seed 42, serta evaluasi MAE dan MSE.
- Script setup tersedia untuk Windows dan macOS/Linux.

## Catatan metodologis

File sumber hanya menyediakan empat periode akademik. Karena skripsi membutuhkan IPS Semester 1 sampai Semester 5, IPS Semester 1 dan Semester 2 dibuat sebagai estimasi kompatibilitas:

```text
Estimasi IPS awal = ((3 x IPK GANJIL2425) - IPS GANJIL2425) / 2
```

Kedua nilai tersebut ditandai `is_estimated = true`. Untuk hasil penelitian final, nilai estimasi sebaiknya diganti dengan IPS Semester 1 dan Semester 2 asli dari pihak akademik.

## Menjalankan aplikasi

### macOS/Linux

```bash
./setup-macos-linux.sh
php artisan serve
```

### Windows

```bat
setup-windows.bat
php artisan serve
```

Akun awal:

```text
Admin    : admin / password123
Operator : operator / password123
```

## Validasi yang telah dilakukan

- Seluruh 66 berkas PHP lolos pemeriksaan sintaks `php -l`.
- Seluruh 39 Blade view berhasil dikompilasi.
- Tiga route halaman Dataset berhasil terdaftar.
- Dataset 612 sampel lolos validasi rentang nilai 0.00-4.00.
- Struktur CSV dan ringkasan dataset berhasil diperiksa.

Pengujian database penuh dan training aktual tidak dapat dijalankan pada lingkungan pembuatan paket karena ekstensi PDO MySQL/SQLite tidak tersedia. Jalankan script setup pada komputer dengan MySQL dan `pdo_mysql` aktif untuk membangun database sekaligus melatih model.
