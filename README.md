# E-Presensi

Aplikasi **presensi (absensi) siswa berbasis pengenalan wajah** (*face recognition*) untuk lingkungan kampus.

Presensi dicatat dengan memindai wajah melalui kamera browser. Wajah dikenali oleh
service Python terpisah, lalu dicocokkan dengan **ID pengguna** untuk memvalidasi
kehadiran. Sistem juga menerapkan **geofence** (radius lokasi) agar absensi hanya
valid jika dilakukan di dalam area kampus.

Dibangun dengan **Laravel 11** (backend & antarmuka) dan **FastAPI + dlib**
(microservice pengenalan wajah).

---

## Daftar Isi

- [Fitur](#fitur)
- [Teknologi](#teknologi)
- [Yang Harus Diinstall](#yang-harus-diinstall)
- [Tata Cara Pemasangan](#tata-cara-pemasangan)
- [Cara Menjalankan](#cara-menjalankan)
- [Akun Default](#akun-default)
- [API Service Python](#api-service-python)
- [Struktur Proyek](#struktur-proyek)
- [Troubleshooting](#troubleshooting)
- [Catatan Penting](#catatan-penting)
- [License](#license)

---

## Fitur

###\Admin

| Fitur | Keterangan |
|---|---|
| Dashboard | Ringkasan jumlah siswa, hadir, dan absensi tidak lengkap |
| Data Absensi | Lihat, filter per rentang tanggal, dan hapus data absensi |
| Reset Data | Hapus massal absensi (atau hanya foto absensi) berdasarkan rentang tanggal |
| Kelola Siswa | CRUD siswa, import massal dari Excel (`.xlsx` / `.xls`) |
| Registrasi Wajah | Daftarkan wajah siswa dari halaman edit siswa |
| Geofence | Atur nama instansi, alamat, titik koordinat, dan radius Radius (meter) |
| Laporan | Rekap absensi per periode + export ke Excel |
| Jadwal | CRUD jadwal acara/kegiatan, bagikan ke siswa |
| Branding Aplikasi | Upload logo, favicon, dan wallpaper |
| Profil & Password | Ubah profil, foto, dan password |

### Siswa / User

| Fitur | Keterangan |
|---|---|
| Dashboard | Status absensi hari ini |
| Presensi Masuk | Scan wajah via kamera (WebSocket) + validasi geofence + foto |
| Presensi Pulang | Isi catatan kegiatan lalu checkout |
| Riwayat & Laporan | Lihat riwayat absensi sendiri + export Excel |
| Jadwal | Melihat jadwal yang dibagikan admin |
| Profil & Password | Ubah profil, foto, dan password |

---

## Teknologi

| Layer | Teknologi |
|---|---|
| Framework | Laravel 11 |
| Bahasa Backend | PHP `^8.2` |
| Microservice AI | FastAPI 0.115 + Uvicorn |
| Face Recognition | dlib 19.x + face-recognition 1.3.0 |
| Pengolahan Gambar | OpenCV (`opencv-python`), Pillow, NumPy |
| Database | MySQL / MariaDB |
| Frontend | Blade + TailwindCSS + Alpine.js |
| Asset Build | Vite 5 |
| Live Streaming | WebSocket (Python `→` browser) |
| Peta / Geofence | Leaflet.js + OpenStreetMap |
| Import/Export Excel | maatwebsite/excel 3.1 |
| DataTables Server-side | yajra/laravel-datatables 11 |
| Manipulasi Gambar | intervention/image 2 |

---

## Yang Harus Diinstall

### 1. Wajib

| Software | Versi Minimum | Keterangan |
|---|---|---|
| **PHP** | `8.2` (sudah diuji sampai `8.4`) | Wajib ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd` |
| **Composer** | `2.x` | Install dependency PHP |
| **Node.js** | `18`+ (sudah diuji `v25`) | Untuk Vite. Wajib ekstensi `bcmath` |
| **MySQL / MariaDB** | MySQL `8.0` atau MariaDB `10.4`+ | Database utama |
| **Python** | `3.9` – `3.11` (rekomendasi **3.10**) | Untuk service face recognition |
| **pip** | ikut versi Python | Package Python |
| **Git** | `2.x` | (Opsional) Untuk clone repository |

> **XAMPP / Laragon** sudah mencakup **Apache, PHP, dan MySQL** dalam satu paket,
> jadi tidak perlu install PHP dan MySQL secara terpisah. Pastikan modul PHP
> `pdo_mysql` aktif di `php.ini`.

### 2. Untuk Kompilasi `dlib` di Windows

`dlib` adalah library C++, sehingga **tidak ada wheel resmi** untuk Windows
pada versi terbaru. Untuk `pip install dlib` berhasil, dibutuhkan:

| Software | Keterangan |
|---|---|
| **Visual Studio Build Tools 2019/2022** | Centang workload **"Desktop development with C++"** |
| **CMake** | `3.x`, harus ada di `PATH` |

> **Alternatif tanpa compiler:** repo ini sudah menyertakan wheel dlib siap pakai
> di root: `dlib-19.22.99-cp39-cp39-win_amd64.whl` (khusus **Python 3.9**, Windows x64).
> Dengan Python 3.9, `dlib` terpasang tanpa perlu kompilasi.
>
> ```powershell
> pip install .\dlib-19.22.99-cp39-cp39-win_amd64.whl
> ```

### 3. Checklist Ekstensi PHP

Aktifkan di `php.ini` (hapus tanda `;` di depan):

```ini
extension=pdo_mysql
extension=mbstring
extension=openssl
extension=fileinfo
extension=gd
```

Cek hasilnya:

```powershell
php -m
```

### 4. Model dlib (Sudah Termasuk)

Service AI membutuhkan dua file model, **sudah tersedia** di `backend-ai/model/`:

| File | Ukuran | Fungsi |
|---|---|---|
| `shape_predictor_68_face_landmarks.dat` | ~95 MB | Mendeteksi 68 titik landmark wajah |
| `dlib_face_recognition_resnet_model_v1.dat` | ~21 MB | Menghasilkan vektor fitur wajah 128 dimensi |

---

## Tata Cara Pemasangan

### Langkah 0 — Persiapan

1. Nyalakan **Apache** dan **MySQL** melalui **XAMPP Control Panel**.
2. Pastikan `http://localhost/phpmyadmin` bisa dibuka di browser.
3. Buka terminal / Command Prompt / PowerShell di folder proyek.

---

### Langkah 1 — Clone Repository

```bash
git clone https://github.com/mywardaddy/presensi-app.git
cd presensi-app
```

Jika sudah mengunduh manual (ZIP), cukup `cd` ke folder `presensi-app`.

---

### Langkah 2 — Install Dependency PHP (Composer)

```powershell
composer install
```

Menghasilkan folder `vendor/`.

> **Catatan:** folder `vendor/` tidak ikut di-commit ke repository (sesuai standar
> Laravel), sehingga `composer install` **wajib** dijalankan setiap kali clone.

---

### Langkah 3 — Install Dependency JavaScript (npm)

```powershell
npm install
npm run build
```

`npm run build` mengompilasi aset Vite ke folder `public/build/`.

> Untuk mode pengembangan (auto-reload), jalankan `npm run dev` dan **jangan**
> `npm run build` — keduanya tidak boleh berjalan bersamaan.

---

### Langkah 4 — Konfigurasi Environment

```powershell
copy .env.example .env
```

Di Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Generate application key:

```powershell
php artisan key:generate
```

Lalu buka `.env` dan sesuaikan bagian database:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=presensi-app
DB_USERNAME=root
DB_PASSWORD=
```

Pastikan `APP_URL` sesuai (default repository: `http://127.0.0.1:8000`):

```ini
APP_URL=http://127.0.0.1:8000
APP_TIMEZONE=Asia/Jakarta
```

---

### Langkah 5 — Siapkan Database

#### Cara A — Import Dump SQL (Disarankan)

Dump `database/presensi-app.sql` berisi **struktur dan data** yang sudah jadi
dan konsisten dengan aplikasi. Cara ini paling aman.

**_lewat phpMyAdmin:_

1. Buka `http://localhost/phpmyadmin`
2. Klik tab **Import** di menu atas
3. Klik **Choose File** → pilih `presensi-app/database/presensi-app.sql`
4. Klik **Go / Kirim**

Database `presensi-app` akan dibuat otomatis beserta seluruh tabelnya.

**Lewat terminal (XAMPP):**

```powershell
& "C:\xampp\mysql\bin\mysql.exe" -u root -e "CREATE DATABASE IF NOT EXISTS \`presensi-app\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
& "C:\xampp\mysql\bin\mysql.exe" -u root presensi-app < "database\presensi-app.sql"
```

#### Cara B — Migration + Seeder

```powershell
php artisan migrate
php artisan db:seed
```

> ⚠️ **PENTING — baca dulu [Catatan Penting](#catatan-penting).**
> Saat repository ini dibuat, `php artisan migrate` **belum tentu berhasil** dari
> database kosong karena ada ketidaksesuaian antara file migration dan skema
> database yang sebenarnya. **Gunakan Cara A (import SQL) kecuali Anda sudah
> memperbaiki hal-hal yang disebutkan pada bagian Catatan Penting.**

---

### Langkah 6 — Link Storage

```powershell
php artisan storage:link
```

Membuat symlink `public/storage` → `storage/app/public`, agar foto profil,
foto absensi, dan file branding dapat diakses browser.

> Di Windows, bila `storage:link` gagal karena symlink tidak diizinkan,
> jalankan terminal sebagai **Administrator**, atau lewati langkah ini karena
> folder `public/storage` sudah tersedia di repository.

---

### Langkah 7 — Setup Python Service (`backend-ai`)

#### 7.1 — Membuat Virtual Environment

```powershell
cd backend-ai

python -m venv .venv
```

Aktifkan virtual environment:

```powershell
# PowerShell
.\.venv\Scripts\Activate.ps1

# ATAU Command Prompt (CMD)
.venv\Scripts\activate.bat
```

Jika PowerShell menolak menjalankan `Activate.ps1` karena kebijakan eksekusi:

```powershell
Set-ExecutionPolicy -Scope CurrentUser -ExecutionPolicy RemoteSigned
```

#### 7.2 — Install Package Python

**Opsi 1 — Python 3.9 di Windows (paling mudah, tanpa compiler):**

```powershell
pip install .\..\dlib-19.22.99-cp39-cp39-win_amd64.whl
pip install -r requirements.txt
```

> `requirements.txt` tetap menyebut `dlib==19.24.6`. Bila `pip` mencoba
> meng-*build* dlib dari source, jalankan perintah `pip install` untuk wheel
> **terlebih dahulu** (seperti di atas) agar dependensi `face-recognition`
> sudah terpenuhi.

**Opsi 2 — Python 3.10 / 3.11 (butuh Visual Studio Build Tools + CMake):**

```powershell
pip install --upgrade pip setuptools wheel
pip install -r requirements.txt
```

Butuh **±10–20 menit** untuk kompilasi dlib.

#### 7.3 — Verifikasi Install

```powershell
python -c "import dlib, cv2, fastapi, face_recognition; print('OK')"
```

Bila muncul `OK`, installasi Python berhasil.

---

### Langkah 8 — Verifikasi Model dlib

Pastikan kedua file model ada:

```text
backend-ai/model/shape_predictor_68_face_landmarks.dat
backend-ai/model/dlib_face_recognition_resnet_model_v1.dat
```

Jika belum ada, unduh dari situs resmi dlib dan ekstrak (`.bz2`) ke `backend-ai/model/`.

---

## Cara Menjalankan

Aplikasi ini membutuhkan **2 proses** yang berjalan bersamaan, plus MySQL dari XAMPP.

### Terminal 1 — Laravel

```powershell
# dari folder root proyek (presensi-app)
php artisan serve --host=127.0.0.1 --port=8000
```

Buka di browser:

```
http://127.0.0.1:8000
```

> **Alternatif tanpa `artisan serve`:** Letakkan folder proyek di
> `C:\xampp\htdocs\presensi-app`, lalu jalankan **Apache** dari XAMPP dan buka
> `http://localhost/presensi-app/public`. Pastikan `AllowOverride All` aktif
> pada `httpd.conf` agar file `.htaccess` Laravel dibaca.

### Terminal 2 — Service Face Recognition (Python)

```powershell
cd backend-ai
.\.venv\Scripts\Activate.ps1
uvicorn api:app --host 127.0.0.1 --port 5050
```

Pastikan muncul:

```
Uvicorn running on http://127.0.0.1:5050
```

Test service:

```
http://127.0.0.1:5050
```

Respon yang benar:

```json
{ "message": "Face Recognition API is running!" }
```

> ⚠️ **Port wajib `5050`.** Alamat `ws://127.0.0.1:5050/ws` dan
> `http://127.0.0.1:5050/register_face` ditulis langsung di dalam file Blade
> (`resources/views/includes/scan-face-user.blade.php` dan
> `resources/views/pages/admin/user/edit.blade.php`). Mengubah port **wajib**
> disertai perubahan di kedua file tersebut.
>
> ⚠️ Service Python **tidak bisa diakses dari komputer lain** (hanya `127.0.0.1`),
> dan memakai **WebSocket**, jadi camera hanya berjalan di browser
> `localhost` / `127.0.0.1` — bukan dari IP publik.

### Ringkasan Urutan Menjalankan

```
1. XAMPP     → nyalakan Apache + MySQL
2. Terminal 1 → cd backend-ai → uvicorn api:app --host 127.0.0.1 --port 5050
3. Terminal 2 → php artisan serve --host 127.0.0.1 --port 8000
4. Browser   → http://127.0.0.1:8000
```

> Urutan 1 → 2 → 3 → 4 **tidak wajib**, selama MySQL menyala sebelum
> login, dan service Python menyala sebelum memindai wajah.

---

## Akun Default

Dibuat oleh seeder / sudah ada pada dump SQL:

| Role | Email | Password |
|---|---|---|
| Administrator | `admin@gmail.com` | `admin123` |
| User / Mahasiswa | `user@gmail.com` | `user123` |

> **Ganti password ini sebelum dipakai di lingkungan nyata.**
> Password disimpan dengan hash `bcrypt`.

**Role ID:**

| ID | Nama | Akses |
|---|---|---|
| `1` | Admin | Seluruh menu admin |
| `2` | User | Menu siswa |

---

## API Service Python

Base URL: `http://127.0.0.1:5050`
CORS diizinkan untuk: `http://127.0.0.1:8000` dan `http://localhost:8000`

| Method | Endpoint | Keterangan |
|---|---|---|
| `GET` | `/` | Health check |
| `WS` | `/ws` | Streaming pengenalan wajah real-time |
| `POST` | `/register_face` | Mendaftarkan wajah baru |

### `WS /ws` — Streaming Face Recognition

Kirim frame gambar dari browser:

```json
{ "image": "<base64>" }
```

Balasan:

```json
{ "face": "2" }
```

`face` berisi **ID pengguna** (string) bila dikenali, `"Unknown"` bila tidak
cocok, dan `"No face detected"` bila tidak ada wajah di frame.

> Setiap frame yang diterima juga ditulis ke `backend-ai/debug_frame.jpg` — berguna
> untuk keperluan debugging.

### `POST /register_face` — Registrasi Wajah

Content-Type: `multipart/form-data`

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `id` | string | Ya | ID pengguna |
| `name` | string | Ya | Nama pengguna |
| `file` | file | Ya | Gambar wajah (`.jpg`, `.jpeg`, `.png`, `.webp`) |

Contoh dengan `curl`:

```bash
curl -X POST http://127.0.0.1:5050/register_face \
  -F "id=2" \
  -F "name=Budi Santoso" \
  -F "file=@C:/path/foto.jpg"
```

Respon berhasil:

```json
{ "success": true, "message": "Registrasi Wajah Berhasil: Budi Santoso" }
```

Data tersimpan di:

- `backend-ai/images/` — foto wajah asli
- `backend-ai/known_faces.json` — vektor fitur wajah (128 dimensi)

---

## Struktur Proyek

```text
presensi-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/            # Dashboard, absensi, user, laporan, settings
│   │   │   ├── User/             # Dashboard, absensi, laporan, settings
│   │   │   └── LoginController.php
│   │   ├── Middleware/           # IsAdmin, IsUser
│   │   └── Requests/             # Validasi form
│   ├── Imports/                  # Import user dari Excel
│   ├── Exports/                  # Export absensi ke Excel
│   ├── Models/                   # User, Absensi, Instansi, Role, Jadwal, Application
│   └── Rules/
│
├── backend-ai/                   # Microservice face recognition (Python)
│   ├── api.py                    # FastAPI: /ws dan /register_face
│   ├── known_faces.json          # Database vektor wajah
│   ├── images/                   # Foto wajah terdaftar
│   ├── model/                    # Model dlib (.dat)
│   ├── requirements.txt
│   └── debug_frame.jpg           # Output debug otomatis
│
├── bootstrap/app.php             # Alias middleware 'admin' & 'user'
├── config/                       # Konfigurasi Laravel
├── database/
│   ├── migrations/               # Struktur tabel
│   ├── seeders/DatabaseSeeder.php
│   └── presensi-app.sql          # Dump SQL (struktur + data)
│
├── public/                       # Document root, aset build
├── resources/views/
│   ├── auth/                     # Halaman login
│   ├── includes/                 # Komponen: scan wajah, sidebar, navbar
│   ├── layouts/                  # Layout admin, user, auth
│   └── pages/
│       ├── admin/                # Halaman admin
│       └── user/                 # Halaman siswa
│
├── routes/web.php                # Seluruh route aplikasi
├── storage/app/public/           # Upload: foto profil, foto absensi, branding
├── composer.json                 # Dependency PHP
├── requirements.txt              # Dependency Python
├── package.json                  # Dependency JavaScript
└── vite.config.js
```

---

## Troubleshooting

### ❌ Kamera tidak bisa diakses

Browser hanya mengizinkan kamera pada **`localhost` / `127.0.0.1`** atau di
melalui **HTTPS**. Jangan diakses lewat `http://192.168.x.x` — kamera akan
ditolak.

Pastikan juga izin kamera tidak di-*block* oleh browser.

### ❌ Scan wajah selalu "Unknown" / "No face detected"

- Pastikan service Python benar-benar berjalan di port `5050`.
- Pastikan pencahayaan wajah terang dan wajah menghadap lurus ke kamera.
- Pastikan wajah siswa **sudah terdaftar** lewat tombol registrasi wajah pada
  halaman edit siswa di menu Admin.
- Wajah yang terlalu miring, tertutup masker, atau pencahayaan redup membuat
  jarak fitur wajah melewati ambang batas **0,85** (di `backend-ai/api.py`).

### ❌ WebSocket gagal connect

- Service Python harus dijalankan dengan `--host 127.0.0.1 --port 5050`.
- WebSocket tidak bisa melewati proxy.

### ❌ `pip install dlib` gagal

```text
error: Microsoft Visual C++ 14.0 or greater is required
```

Instal **Visual Studio Build Tools 2022** dengan workload *Desktop development
with C++*, lalu jalankan ulang. Atau gunakan **Python 3.9** dan pasang wheel
`dlib-19.22.99-cp39-cp39-win_amd64.whl` yang sudah ada di repository.

### ❌ `Class "dlib.shape_predictor" not found` / model not found

Path model bersifat relatif terhadap folder kerja. **Selalu jalankan `uvicorn`
dari dalam folder `backend-ai`:**

```powershell
cd backend-ai
uvicorn api:app --host 127.0.0.1 --port 5050
```

### ❌ `SQLSTATE[HY000] [1045] Access denied`

Username/password MySQL pada `.env` tidak sesuai. Default XAMPP adalah
`root` dengan password kosong.

### ❌ `Base table or view not found`

Database belum di-import. Jalankan **Langkah 5 — Cara A**.

### ❌ `SQLSTATE[42S22]: Column not found: prodi`

Menandakan database dibuat dari migration yang tidak sinkron dengan aplikasi.
Hapus database, lalu import ulang `database/presensi-app.sql`.

### ❌ Halaman styling berantakan (tanpa CSS)

Aset Vite belum di-build:

```powershell
npm install
npm run build
```

### ❌ Foto tidak tampil

```powershell
php artisan storage:link
```

### ❌ `php artisan serve` menampilkan "Invalid Host header"

Gunakan `127.0.0.1`, bukan `localhost` atau nama hostname komputer:

```powershell
php artisan serve --host=127.0.0.1 --port 8000
```

---

## Catatan Penting

### Ketidaksesuaian Skema Database

Struktur tabel pada **file migration** dan **skema database sebenarnya** (dump
SQL) **tidak identik**. Perbedaan yang terdeteksi:

| Tabel | File migration | Database sebenarnya (dump SQL) |
|---|---|---|
| `users` | kolom `nim` | kolom **`nip_nim`** + ada kolom **`prodi`** |
| `instansi` | kolom `address` | kolom **`adress`** |
| `applications` | tidak ada `color` | ada kolom **`color`** |
| `applications` | ada `wallpaper` | perlu disesuaikan |

Selain itu, file migration
`database/migrations/2024_09_05_220620_create_applications_table.php`
memiliki method **`up()` dan `down()` yang terduplikasi** (baris 12 & 35, serta
30 & 42). PHP akan gagal memuat file tersebut sehingga
`php artisan migrate` **tidak dapat dijalankan dari database kosong**.

**Rekomendasi: gunakan `database/presensi-app.sql` untuk menyiapkan database.**
Jika ingin bermigrasi penuh, perbaiki dulu hal-hal di atas.

### Catatan Keamanan Repository

Repository ini sengaja memuat beberapa file sensitif agar proyek dapat langsung
dijalankan. Perhatikan hal berikut sebelum dipakai di lingkungan nyata:

| Item | Risiko |
|---|---|
| `backend-ai/images/` | Berisi **foto wajah orang nyata** (data biometrik) |
| `backend-ai/known_faces.json` | Vektor fitur wajah yang dapat dipakai untuk identifikasi biometrik |
| `database/presensi-app.sql` | Berisi baris `sessions` (token login aktif) dan **password hash** |
| `storage/app/public/faces/` | Berisi foto wajah hasil registrasi |
| `public/build/` | Aset hasil build |

**Tindakan yang disarankan:**

1. **Ganti password** `admin123` dan `user123` serta hapus baris `sessions`
   dari dump SQL sebelum dipakai.
2. **Jangan gunakan kembali** foto wajah dan `known_faces.json` di repository
   ini untuk keperluan produksi.
3. Bila data biometrik tidak boleh publik, keluarkan `backend-ai/images/` dan
   `backend-ai/known_faces.json` dari riwayat git
   (`git filter-repo`) dan aktifkan **Git LFS** untuk file model `.dat`.
4. File `.env` **tidak** ikut di-commit (sudah masuk `.gitignore`), tetapi
   `APP_KEY` pada repository ini sudah pernah dipublikasikan. Jalankan
   `php artisan key:generate` untuk membuat key baru.

### Batasan Teknis

- Seluruh aplikasi memakai **WebSocket** dan dikunci ke `127.0.0.1`, sehingga
  **hanya dapat diakses dari komputer lokal** — belum mendukung banyak
  pengguna dari jaringan atau perangkat berbeda.
- Alamat service AI ditulis **hardcoded** di dalam file Blade, bukan di
  `config/` atau `.env`.
- Validasi geofence menggunakan koordinat yang dikirim browser, sehingga
  mudah dipalsukan.
- Service AI melakukan penulisan `debug_frame.jpg` pada setiap frame — ini
  memblokir event loop FastAPI.

---

## License

[MIT](https://choosealicense.com/licenses/mit/)
