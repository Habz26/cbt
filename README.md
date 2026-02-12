# CBT SPMB - SMK Al-Falah

CBT SPMB adalah aplikasi Computer Based Test (CBT) yang digunakan untuk proses Seleksi Penerimaan Murid Baru (SPMB) di SMK Al-Falah. Sistem ini dirancang untuk pelaksanaan ujian berbasis komputer dengan manajemen data terpusat, sistem penjadwalan ujian, serta monitoring hasil secara real-time.

Aplikasi memiliki 2 role utama:
- Admin
- Student (Siswa)

---

# 📦 Instalasi Project

## 1. Aktifkan Ekstensi ZIP di PHP

Sebelum menjalankan `composer install`, pastikan ekstensi **zip** pada PHP sudah aktif.

Langkah:
1. Buka file `php.ini`
2. Cari baris berikut:

;extension=zip


3. Hapus tanda `;` sehingga menjadi:

extension=zip


4. Simpan file lalu restart web server (Apache / XAMPP / Laragon / dll)

Ekstensi ZIP wajib aktif karena Composer membutuhkan dukungan ZIP untuk mengekstrak dependency.

---

## 2. Install Dependency Backend

```bash
composer install
```
## 3. Install Dependency Frontend
```bash
npm install
npm run build
```
Atau bisa langsung:
```bash
npm install && npm run build
```
## 4. Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```
Atur konfigurasi database pada file .env, lalu jalankan:
```bash
php artisan migrate
php artisan key:generate
```
## 5. Jalankan Aplikasi
```bash
php artisan serve
```
🧩 Alur Sistem
👨‍🎓 ROLE: STUDENT
1. Login
Student login menggunakan akun yang sudah dibuatkan oleh admin berupa:

Username

Password

2. Dashboard Student
Menampilkan card ujian dengan status:

Aktif

Belum Aktif

Sudah Dikerjakan

Jika ujian sudah dikerjakan:

Tombol berubah menjadi "Sudah Dikerjakan"

Tombol dalam keadaan disable

Tidak dapat mengerjakan ulang

Tersedia tombol Logout.

3. Halaman Ujian
Sistem ujian menggunakan mekanisme:

1 soal per halaman

Navigasi:

Tombol Sebelumnya

Tombol Selanjutnya

Jika berada di soal terakhir:

Tombol "Selanjutnya" berubah menjadi "Submit"

Tersedia fitur:

Tombol Preview Soal

Menampilkan daftar nomor soal

Bisa langsung lompat ke soal tertentu

Saat menekan Submit:

Muncul verifikasi konfirmasi

Jika disetujui, jawaban disimpan

Student diarahkan ke halaman Result

4. Halaman Result
Menampilkan:

Pesan bahwa ujian telah selesai dikerjakan

Tombol kembali ke Dashboard

Setelah submit:

Ujian tidak bisa dikerjakan ulang

Status berubah menjadi "Sudah Dikerjakan"

👨‍💼 ROLE: ADMIN
Admin memiliki beberapa menu utama pada navbar:

📊 Dashboard Admin
Menampilkan card statistik:

Total Ujian

Total Soal

Total User

Total Hasil

Menampilkan grafik:

Diagram batang rata-rata skor siswa

Diagram batang jumlah soal benar siswa

Diagram bulat user berdasarkan role

Diagram batang jumlah soal per ujian

Tabel Analytics Siswa:

Nama Siswa

Email

Total Ujian

Total Skor

Rata-rata Skor

Jumlah Soal Benar

📝 Kelola Soal
Field Input:

Pilih Ujian (berdasarkan data ujian yang sudah dibuat)

Tipe Soal (PG / Essay)

Soal

Gambar Soal (Opsional)

Tombol Simpan

Fitur Tambahan:

Import Soal dari Excel

Pilih ujian terlebih dahulu

Upload file Excel

Klik tombol Import

Di bawah form terdapat:

Garis pemisah

Preview card soal sesuai ujian yang dipilih

Fitur Edit

Fitur Hapus

🗂 Kelola Ujian
Field:

Nama Ujian

Durasi (menit)

Waktu Mulai (tanggal & jam)

Waktu Selesai (tanggal & jam)

Fitur:

Tombol Tambah Ujian

Garis pemisah

Preview card ujian yang sudah dibuat

Edit

Hapus

👥 Kelola User
Field:

Nama

Username

Email

Password

Role

Fitur:

Tambah User

Import User dari Excel

Tabel daftar user

Edit

Hapus

🗓 Jadwal Ujian
Menampilkan card jadwal ujian berisi:

Judul Ujian

Durasi

Waktu Mulai

Waktu Selesai

Tombol Edit Jadwal

📈 Monitoring Hasil
Menampilkan tabel:

Nama Siswa

Ujian

Skor

Tanggal Submit

Jam Submit

Tersedia tombol detail untuk melihat jawaban siswa.

Detail Jawaban menampilkan tabel:

Soal

Jawaban Siswa

Jawaban Benar

Tersedia tombol Logout.

🎯 Tujuan Sistem
CBT SPMB dibuat untuk:

Mempermudah proses seleksi siswa baru

Mengurangi penggunaan kertas

Mempercepat proses penilaian

Memberikan monitoring hasil secara terstruktur

Mendukung digitalisasi sistem ujian di SMK Al-Falah

🏫 Digunakan Oleh
SMK Al-Falah
Untuk kebutuhan Seleksi Penerimaan Murid Baru berbasis Computer Based Test.
