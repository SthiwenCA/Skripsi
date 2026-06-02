# 🛣️ E-Apps Deteksi Kerusakan Jalan (Laravel + YOLOv8)

Repositori ini berisi sistem E-Apps untuk mendeteksi kerusakan jalan secara real-time. Sistem ini menggunakan arsitektur modern yang memisahkan antara Web Server berbasis framework **Laravel (PHP)** dan AI API Server berbasis **YOLOv8 (Python)**.

---

## 💻 Persyaratan Sistem (Prerequisites)
Sebelum menjalankan proyek ini di komputer (lokal), pastikan perangkat Anda sudah terinstal:
- **XAMPP** (Untuk menjalankan MySQL Server & PHP v8.1+)
- **Composer** (PHP Package Manager)
- **Node.js & NPM**
- **Python** (v3.8 - v3.11)

---

## 🚀 Cara Cepat Instalasi (Khusus Windows)

Bagi penguji atau kolaborator yang baru saja melakukan `git clone` terhadap repositori ini, ikuti langkah berikut untuk menjalankan sistem di komputer Anda:

1. **Nyalakan XAMPP:** Buka aplikasi XAMPP Control Panel, lalu klik tombol **Start** pada modul **MySQL** (untuk database) dan **Apache** (opsional, jika ingin membuka phpMyAdmin).
2. Buka folder proyek ini, lalu klik ganda (*Double-Click*) pada file `setup.bat`. Tunggu hingga terminal hitam selesai menginstal seluruh dependensi Laravel dan Python (sekitar 3-5 menit).
3. Setelah selesai, buka file `.env` di teks editor Anda. Cari dan pastikan kredensial database lokal Anda sudah sesuai:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=root
   DB_PASSWORD=
5. Buka terminal (CMD/VS Code) di root folder, lalu jalankan perintah pembuatan tabel database:
    ```Bash
    php artisan migrate

## Konfigurasi Model AI (YOLOv8)
Sistem membutuhkan bobot (weights) hasil pelatihan AI untuk mendeteksi jalan rusak.

1. Siapkan file model YOLO Anda (bernama best.pt).
2. Masukkan file tersebut ke dalam direktori server AI, tepatnya di dalam folder ai-server/ (atau sesuaikan dengan jalur yang dipanggil di dalam file app.py).

## 🌐 Cara Menjalankan Sistem (Membutuhkan 2 atau 3 Terminal)
Karena sistem ini memisahkan antara beban Web dan beban AI, Anda wajib menyalakan keduanya secara bersamaan di terminal yang berbeda.

### Terminal 1: Menyalakan AI Server (Backend Python)
Buka terminal baru di root folder, lalu jalankan perintah berikut secara berurutan:

```bash
# Masuk ke folder AI
cd ai-server

# Aktifkan virtual environment (Windows)
..\.venv\Scripts\activate
# (Catatan: Untuk Mac/Linux gunakan -> source ../.venv/bin/activate)

# Jalankan server AI
python app.py
```

### Terminal 2: Menyalakan Web Server (Frontend Laravel)
Buka terminal baru lagi (biarkan Terminal 1 tetap bekerja di latar belakang). Pastikan Anda berada di direktori utama (root) proyek, lalu jalankan:

```bash
php artisan serve

Terminal 3: Kompilasi Aset Frontend (Opsional namun Disarankan)
Jika Anda menggunakan Vite/TailwindCSS dan ingin melakukan modifikasi pada tampilan antarmuka (UI), atau jika tampilan web terlihat berantakan, buka terminal ketiga di direktori utama proyek dan jalankan:
    ```Bash
    npm run dev

🎉 Selesai!