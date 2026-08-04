# Dokumentasi Penyesuaian Aplikasi, Skripsi, dan Dataset

## 1. Ruang lingkup skripsi yang diterapkan

Aplikasi menerapkan spesifikasi penelitian berikut:

- Objek: mahasiswa Program Studi Teknik Informatika Universitas Dipa Makassar angkatan 2023.
- Input model: IPS Semester 1, IPS Semester 2, IPS Semester 3, IPS Semester 4, dan IPS Semester 5.
- Output model: prediksi IPK dan IPK aktual sebagai target/evaluasi.
- Metode: Artificial Neural Network dengan algoritma backpropagation.
- Arsitektur default: 5 neuron input, 8 neuron hidden, dan 1 neuron output.
- Pembagian data default: 80% training dan 20% testing.
- Evaluasi: Mean Absolute Error (MAE) dan Mean Squared Error (MSE).
- Platform: Laravel, PHP, MySQL, HTML, CSS, dan JavaScript.

## 2. Kondisi dataset sumber

`Data Mhs.xlsx` berisi 9.913 baris, 3.388 mahasiswa unik, dan empat periode akademik:

- GANJIL2425
- GENAP2425
- GANJIL2526
- GENAP2526

Untuk angkatan 2023 terdapat 682 mahasiswa. Sebanyak 612 mahasiswa mempunyai empat periode lengkap dan dapat dibentuk menjadi dataset penelitian. Sebanyak 70 mahasiswa dikeluarkan: 69 karena periode tidak lengkap dan 1 karena hasil estimasi di luar rentang 0-4.

## 3. Pemetaan data ke variabel penelitian

Karena file sumber tidak menyediakan IPS Semester 1 dan Semester 2 secara terpisah, aplikasi menggunakan mode kompatibilitas berikut:

| Variabel | Sumber |
|---|---|
| IPS 1 | Estimasi rata-rata IPS semester awal |
| IPS 2 | Sama dengan estimasi IPS 1 |
| IPS 3 | IPS GANJIL2425 |
| IPS 4 | IPS GENAP2425 |
| IPS 5 | IPS GANJIL2526 |
| IPK aktual | IPK GENAP2526 |

Rumus estimasi:

```text
Estimasi IPS awal = ((3 x IPK GANJIL2425) - IPS GANJIL2425) / 2
```

Rumus tersebut mengikuti asumsi IPK sebagai rata-rata IPS sampai semester berjalan yang digunakan dalam naskah skripsi. Nilai IPS 1 dan IPS 2 diberi status `is_estimated = true` dan ditampilkan dengan label **Estimasi** pada aplikasi.

> Untuk hasil penelitian final yang paling kuat, ganti nilai estimasi dengan data IPS Semester 1 dan Semester 2 asli jika pihak akademik dapat memberikannya.

## 4. Berkas dataset terintegrasi

- `database/data/data_mhs_raw.csv`: seluruh data sumber hasil konversi dari Excel.
- `database/data/data_mhs_penelitian_2023.csv`: 612 sampel siap training ANN.
- `database/data/data_mhs_excluded_2023.csv`: data yang tidak masuk sampel beserta alasannya.
- `database/data/dataset_summary.json`: statistik dan aturan pemetaan.

## 5. Alur aplikasi

1. Seeder memasukkan akun admin dan operator.
2. Seeder memasukkan mahasiswa angkatan 2023 dan Data IPS hasil preprocessing.
3. Perintah `thesis:setup` melatih model ANN menggunakan data lengkap.
4. Model terlatih disimpan sebagai model aktif.
5. Administrator atau operator dapat menjalankan prediksi.
6. Hasil prediksi, IPK aktual, absolute error, squared error, MAE, dan MSE tersimpan di database.
7. Grafik dan laporan membaca data hasil prediksi tersebut.

## 6. Perintah setup

```bash
php artisan thesis:setup --fresh --force
```

Perintah tersebut menjalankan migrasi, seeding dataset, dan training model ANN awal.
