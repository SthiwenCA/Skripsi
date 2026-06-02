@echo off
echo ========================================================
echo   AUTO-SETUP LINGKUNGAN SKRIPSI (LARAVEL + YOLOv8 API)
echo ========================================================
echo.

echo [1/7] Menginstal dependensi PHP (Composer)...
call composer install

echo.
echo [2/7] Menyiapkan file .env...
if not exist .env (
    copy .env.example .env
    echo [SUKSES] File .env berhasil dibuat dari .env.example.
) else (
    echo [INFO] File .env sudah ada, dilewati.
)

echo.
echo [3/7] Membuat Application Key Laravel...
call php artisan key:generate

echo.
echo [4/7] Menautkan folder Storage (Untuk akses foto jalan)...
call php artisan storage:link

echo.
echo [5/7] Menginstal dependensi Frontend (Node.js/NPM)...
call npm install
call npm run build

echo.
echo [6/7] Membuat Python Virtual Environment di folder 'ai-server'...
cd ai-server
if not exist .venv (
    python -m venv .venv
    echo [SUKSES] Virtual Environment berhasil dibuat.
) else (
    echo [INFO] Virtual Environment sudah ada.
)

echo.
echo [7/7] Menginstal Library YOLO, PyTorch, dan Web Framework...
call .venv\Scripts\activate.bat
python -m pip install --upgrade pip
pip install -r requirements.txt
call .venv\Scripts\deactivate.bat
cd ..

echo.
echo ========================================================
echo   SETUP SELESAI DENGAN SUKSES!
echo ========================================================
echo TUGAS SELANJUTNYA SEBELUM RUNNING:
echo 1. NYALAKAN XAMPP (Start MySQL dan Apache).
echo 2. Buka file .env dan masukkan username/password database.
echo 3. Buka terminal dan ketik: php artisan migrate
echo ========================================================
pause