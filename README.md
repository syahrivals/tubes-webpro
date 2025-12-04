# Aplikasi Presensi Laravel

Aplikasi presensi sederhana untuk dosen dan mahasiswa menggunakan Laravel 12 dengan Tailwind CSS.

## Fitur

- **Autentikasi**: Login sederhana untuk dosen dan mahasiswa
- **Dashboard Dosen**: 
  - Statistik mata kuliah dan mahasiswa
  - Tabel presensi dengan batch input
- **Dashboard Mahasiswa**:
  - Ringkasan kehadiran per mata kuliah
  - Detail presensi per tanggal
  - Edit profil (phone, photo)

## Requirements

- PHP 8.2+
- Composer
- Node.js & NPM
- SQLite (default) atau MySQL/PostgreSQL

## Instalasi

1. Clone repository:
```bash
git clone <repository-url>
cd tubes-webpro
```

2. Install dependencies:
```bash
composer install
npm install
```

3. Setup environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Konfigurasi database di `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=presensi
DB_USERNAME=root
DB_PASSWORD=
```

5. Jalankan migrations dan seeders:
```bash
php artisan migrate --seed
```

6. Build assets:

**Untuk Development (dengan hot reload):**
Jalankan di terminal terpisah:
```bash
npm run dev
```

**Untuk Production:**
```bash
npm run build
```

7. Jalankan server:
```bash
php artisan serve
```

**PENTING:** Jika menggunakan `npm run dev`, pastikan terminal tetap berjalan. Jika tidak, gunakan `npm run build` terlebih dahulu.

Aplikasi akan tersedia di `http://localhost:8000`

## Akun Test

### Dosen
- Email: `dosen@example.com`
- Password: `password123`

### Mahasiswa
- Email: `mahasiswa@example.com`
- Password: `password123`

Ada 10 akun mahasiswa total (mahasiswa@example.com sampai mahasiswa10@example.com), semua dengan password `password123`.

## Struktur Database

- **users**: id, name, email, password, role (dosen/mahasiswa)
- **mahasiswas**: id, user_id, nim, jurusan, angkatan, phone, photo
- **matkuls**: id, kode, nama, dosen_id, semester, credits
- **enrollments**: id, mahasiswa_id, matkul_id (many-to-many)
- **presences**: id, matkul_id, mahasiswa_id, tanggal, status, note, recorded_by

## Testing

Jalankan tests:
```bash
php artisan test
```

## Teknologi

- Laravel 12
- Bootstrap
- Chart.js (untuk grafik)
- MySQL

## License

MIT
