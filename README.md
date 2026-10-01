# 🌱 JarakFeed - Platform Digital Pengelolaan dan Pemasaran Pakan Fermentasi BUMDes

JarakFeed merupakan platform digital yang dikembangkan untuk mendukung pengelolaan unit usaha pakan fermentasi berbasis potensi lokal di Desa Jarak, Kabupaten Kediri.

Platform ini dirancang untuk membantu BUMDes dalam melakukan digitalisasi proses pengelolaan usaha, mulai dari pencatatan bahan baku, produksi pakan fermentasi, pengelolaan stok, hingga pemasaran produk kepada peternak.

---

## 📌 Latar Belakang

Desa Jarak memiliki potensi sektor peternakan ruminansia seperti sapi dan kambing yang dikelola oleh masyarakat secara mandiri. Namun, pengelolaan pakan ternak masih menghadapi tantangan berupa ketersediaan pakan yang bergantung pada musim serta belum optimalnya pengelolaan usaha pakan fermentasi.

Melalui kolaborasi antara akademisi, Sagasitas, dan Pemerintah Desa Jarak, dikembangkan sebuah platform digital yang dapat membantu BUMDes dalam mengelola unit usaha pakan fermentasi secara lebih terstruktur dan berkelanjutan.

---

## 🎯 Tujuan Sistem

- Mendukung digitalisasi pengelolaan unit usaha pakan fermentasi BUMDes.
- Membantu pencatatan produksi dan ketersediaan stok pakan.
- Mempermudah pengelolaan informasi produk.
- Mendukung proses pemasaran dan pemesanan produk secara digital.
- Meningkatkan efisiensi pengelolaan usaha berbasis potensi lokal desa.

---

## ✨ Fitur Utama

### 👨‍💼 Admin BUMDes

- Dashboard pengelolaan usaha.
- Manajemen data bahan baku.
- Pencatatan produksi pakan fermentasi.
- Pengelolaan data produk.
- Monitoring stok produk.
- Pengelolaan pesanan.
- Pencatatan transaksi penjualan.
- Laporan sederhana usaha.

### 🧑‍🌾 Peternak

- Melihat katalog produk pakan fermentasi.
- Melihat informasi produk dan penggunaan.
- Melihat ketersediaan stok.
- Melakukan pemesanan produk.
- Mendapatkan informasi edukasi pakan fermentasi.

---

## 🏗️ Arsitektur Sistem
Pengguna
|
|
PWA JarakFeed
|
|
Supabase
|
|
Database
(Produk - Produksi - Stok - Pesanan - Transaksi)

---

## 🛠️ Tech Stack

### Frontend
- React.js
- Vite
- TypeScript
- Tailwind CSS
- Progressive Web App (PWA)

### Backend & Database
- Supabase
- PostgreSQL

### Development Tools
- GitHub
- Visual Studio Code
- Figma

### Deployment
- Vercel

---

---

## 👥 Kolaborator

Proyek ini dikembangkan melalui kolaborasi:

- Universitas Negeri Surabaya
- Sagasitas
- Pemerintah Desa Jarak
- BUMDes Desa Jarak

---

## 🚀 Pengembangan Sistem

Tahapan pengembangan:

1. Identifikasi kebutuhan pengguna.
2. Analisis proses bisnis BUMDes.
3. Perancangan sistem dan database.
4. Pengembangan platform digital.
5. Pengujian sistem bersama mitra.
6. Implementasi dan pelatihan penggunaan.

---

## 📌 Status Pengembangan

🚧 Dalam tahap pengembangan awal.

Fokus pengembangan saat ini:
- Analisis kebutuhan sistem.
- Perancangan antarmuka.
- Perancangan database.
- Pengembangan fitur utama platform.

---

## 📄 Lisensi

Project ini dibuat sebagai bagian dari kegiatan Studi Independen Universitas Negeri Surabaya.

# JarakFeed

Project website untuk membantu BUMDes Desa Jarak mengelola usaha pakan fermentasi.

Kita pakai Laravel, Filament, dan MySQL. Saat ini fitur yang sudah dibuat adalah pengelolaan bahan baku.

Kode Laravel ada di folder `backend`, dan branch yang kita pakai sekarang adalah `migration-laravel`.

## Persiapan dulu

Siapkan di laptop masing-masing:

- Laragon dengan PHP 8.3 dan MySQL
- Composer
- Git
- VS Code

Pastikan ekstensi PHP `zip` sudah aktif supaya pemasangan paket Filament bisa berjalan.

Cek lewat terminal Laragon:

```bat
php -v
composer --version
git --version
```

Kalau mau ikut mengirim perubahan, terima dulu undangan collaborator dari GitHub.

## 1. Ambil project dari GitHub

Buka terminal Laragon, lalu jalankan:

```bat
cd C:\laragon\www
git clone --branch migration-laravel https://github.com/Fandyyapari/BUMDes-Feed-Management-System.git jarakfeed-repo
cd jarakfeed-repo\backend
```

Perintah ini mengambil project ke laptop kamu.

Setelah itu, buka folder `jarakfeed-repo` di VS Code.

## 2. Pasang paket project

Di terminal, pastikan kamu ada di folder `backend`, lalu jalankan:

```bat
composer install
```

Ini memasang semua paket yang dibutuhkan project, termasuk Laravel dan Filament. Jadi tidak perlu bikin project Laravel baru.

## 3. Siapkan file konfigurasi

Jalankan:

```bat
copy .env.example .env
php artisan key:generate
```

File `.env` dipakai untuk pengaturan di laptop kamu, termasuk koneksi database.

## 4. Buat database

Nyalakan MySQL di Laragon, lalu buka HeidiSQL dan buat database dengan nama:

```text
jarakfeed
```

Buka file `backend/.env` di VS Code. Atur bagian database jadi:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jarakfeed
DB_USERNAME=root
DB_PASSWORD=
```

Kalau MySQL kamu punya password atau port yang berbeda, sesuaikan.

Simpan file, lalu jalankan:

```bat
php artisan config:clear
php artisan migrate
```

Perintah `migrate` membuat tabel yang dibutuhkan project.

Database kita masih di laptop masing-masing, jadi data yang kamu masukkan tidak otomatis muncul di laptop anggota lain.

## 5. Buat akun untuk login

Jalankan:

```bat
php artisan make:filament-user
```

Isi nama, email, dan password. Ingat passwordnya karena nanti dipakai untuk login.

Ini akun admin aplikasi di laptop kamu, beda dengan akun GitHub.

## 6. Jalankan project

Jalankan:

```bat
php artisan serve
```

Lalu buka di browser:

http://127.0.0.1:8000/admin

Login pakai akun yang baru dibuat.

Terminal ini biarkan tetap berjalan. Kalau mau mengetik perintah lain, buka terminal baru. Untuk menghentikan server, tekan `Ctrl+C`.

Untuk panel Filament standar, langkah npm belum diperlukan. Nanti kalau kita mulai mengerjakan tampilan Blade dengan Vite, siapkan Node.js dan jalankan:

```bat
npm install
npm run build
```

## 7. Kalau mau nambah fitur

Sepakati dulu siapa mengerjakan fitur apa.

Pastikan pekerjaan sebelumnya sudah disimpan dalam commit, lalu jalankan dari folder utama project:

```bat
cd C:\laragon\www\jarakfeed-repo
git switch migration-laravel
git pull --ff-only origin migration-laravel
git switch -c fitur/bahan-masuk
```

Ganti `fitur/bahan-masuk` sesuai fitur kamu. Misalnya:

- `fitur/produksi`
- `fitur/katalog`
- `fitur/pemesanan`

Branch ini jadi tempat kamu mengerjakan fitur. Setelah mengubah kode di VS Code, coba dulu hasilnya di browser.

## 8. Kirim hasil pekerjaan

Kalau sudah berjalan sesuai harapan:

```bat
git status
git add backend
git diff --cached --stat
git commit -m "Tambah fitur bahan masuk"
git push -u origin fitur/bahan-masuk
```

Sesuaikan pesan commit dan nama branch dengan pekerjaan kamu.

Penjelasan singkat:

- `status`: cek file yang berubah.
- `add`: pilih perubahan yang mau disimpan.
- `diff`: cek ringkasan perubahan yang dipilih.
- `commit`: simpan perubahan ke riwayat Git di laptop.
- `push`: kirim perubahan ke GitHub.

Kalau mengubah file di luar folder `backend`, tambahkan file itu juga lewat `git add`.

## 9. Buat Pull Request

Setelah push, buka repository di GitHub:

1. Klik **Pull requests → New pull request**.
2. Pilih base `migration-laravel`.
3. Pilih compare sesuai branch fitur kamu.
4. Jelaskan apa yang ditambahkan dan bagaimana cara mencobanya.
5. Klik **Create pull request**.

Nanti perubahan diperiksa dulu sebelum digabung lewat merge.

## 10. Ambil update dari teman

Kalau ada fitur yang sudah digabung, simpan dulu pekerjaan lokal kamu dalam commit, lalu jalankan:

```bat
cd C:\laragon\www\jarakfeed-repo
git switch migration-laravel
git pull --ff-only origin migration-laravel
cd backend
composer install
php artisan migrate
```

Kalau ada perubahan paket atau aset frontend, jalankan juga perintah npm yang diperlukan.

Untuk tugas berikutnya, buat branch baru dari `migration-laravel` yang sudah diperbarui.

## Biar kerja timnya enak

- Setiap orang pakai branch fitur sendiri.
- Pull Request sementara diarahkan ke `migration-laravel`.
- Sepakati pembagian fitur supaya tidak mengerjakan bagian yang sama.
- Kalau mengubah struktur database, buat migration supaya teman lain bisa ikut memperbaruinya.
- File `.env`, folder `vendor`, dan `node_modules` tidak perlu dikirim ke GitHub.
- File `composer.lock` ikut disimpan supaya versi paket kita sama.
- GitHub menyimpan kode. Data database lokal tidak ikut terkirim.
