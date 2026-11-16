<div align="center">

# SIMRS dr. Tjahyo

**Sistem Informasi Manajemen Rumah Sakit berbasis web**

Pendaftaran · Kunjungan · Rekam Medis · Farmasi · Audit Trail

![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-4169E1?logo=postgresql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?logo=bootstrap&logoColor=white)
![Tests](https://img.shields.io/badge/tests-31%20passed-2E7D32)
![Status](https://img.shields.io/badge/status-prototype-F9A825)

<img src="docs/screenshots/01-landing.png" alt="Halaman publik SIMRS dr. Tjahyo" width="100%">

</div>

---

Rumah sakit kecil dan klinik sering masih mencatat pendaftaran pasien secara
manual atau memakai spreadsheet. Cara ini rawan: data mudah tertukar, stok obat
tidak diketahui, dan **tidak ada catatan siapa yang mengubah data pasien**.

Proyek ini membangun alur inti SIMRS sebagai aplikasi web — dengan dua hal yang
sering dilewatkan proyek latihan: **hak akses yang benar-benar ditegakkan di
server** dan **jejak audit untuk setiap perubahan data**.

> [!TIP]
> Ingin langsung mencoba? Jalankan 5 perintah di bagian [Mulai cepat](#mulai-cepat),
> lalu masuk dengan akun demo di bagian [Akun demo](#akun-demo).

📄 **Dokumentasi lengkap:** [Deskripsi sistem (Indonesia)](docs/deskripsi-sistem.md) ·
[System description (English)](docs/system-description.md)

<details>
<summary><b>Daftar isi</b></summary>

- [Sekilas](#sekilas)
- [Tangkapan layar](#tangkapan-layar)
- [Fitur](#fitur)
- [Peran dan hak akses](#peran-dan-hak-akses)
- [Alur penggunaan](#alur-penggunaan)
- [Stack](#stack)
- [Arsitektur](#arsitektur)
- [Mulai cepat](#mulai-cepat)
- [Akun demo](#akun-demo)
- [Pengujian](#pengujian)
- [Keamanan](#keamanan)
- [Keputusan teknis](#keputusan-teknis)
- [Batasan](#batasan)
- [Rencana pengembangan](#rencana-pengembangan)

</details>

## Sekilas

| Modul | Peran | Tabel | Automated test | Screenshot |
|:--:|:--:|:--:|:--:|:--:|
| **7** | **4** | **16** | **31** (123 assertion) | **10** |

**Yang membedakan proyek ini:**

- 🔐 **RBAC 4 peran** — izin diperiksa lewat middleware `can:` di server, bukan
  sekadar menyembunyikan menu. Ada tes yang memastikan akses terlarang benar-benar
  ditolak (403).
- 🧾 **Audit trail** — setiap create/update/delete mencatat siapa, kapan, dari IP
  mana, dan perubahan nilai **lama → baru**. Password tidak pernah ikut dicatat.
- 🧪 **31 tes otomatis** — alur login, otorisasi, CRUD, dan audit diuji lewat HTTP,
  bukan hanya unit test.
- 🎨 **Tampilan tanpa template** — landing page dan dashboard ditulis sendiri
  dengan Bootstrap 5, tanpa build step Node/Vite.

## Tangkapan layar

### Dashboard Administrator

![Dashboard admin](docs/screenshots/03-dashboard-admin.png)

### Audit Trail — inti nilai proyek ini

Setiap perubahan data bisa ditelusuri: siapa pelakunya, kapan, dan apa yang berubah.

![Audit trail](docs/screenshots/04-audit-trail.png)

### Modul operasional

| Farmasi & stok obat | Manajemen pengguna |
|---|---|
| ![Daftar obat](docs/screenshots/05-obat.png) | ![Pengguna](docs/screenshots/07-pengguna.png) |

| Data pasien | Login pegawai |
|---|---|
| ![Pasien](docs/screenshots/06-pasien.png) | ![Login](docs/screenshots/02-login.png) |

### Satu akun, satu tampilan

Menu dan isi dashboard menyesuaikan peran pengguna.

| Registrasi | Dokter | Apoteker |
|---|---|---|
| ![Dashboard registrasi](docs/screenshots/08-dashboard-registrasi.png) | ![Dashboard dokter](docs/screenshots/09-dashboard-dokter.png) | ![Dashboard apoteker](docs/screenshots/10-dashboard-apoteker.png) |

## Fitur

**Publik**

- Profil rumah sakit: layanan, jadwal dokter, dan informasi IGD 24 jam.
- Halaman responsif, tanpa dependensi build frontend.

**Autentikasi & keamanan**

- Login dengan pembatasan **5 percobaan per 60 detik** (per email + IP).
- Akun nonaktif langsung ditolak.
- Setiap percobaan login dicatat: berhasil/gagal, IP, dan perangkat.
- Sesi disimpan di database, ID sesi diperbarui setelah login.

**Operasional**

- **Pasien** — nomor rekam medis otomatis, NIK unik, pencarian cepat.
- **Kunjungan** — pendaftaran ke poli/dokter, alur status antrean.
- **Rekam medis** — diagnosa, tindakan, resep, catatan dokter.
- **Farmasi** — stok obat, penanda stok kritis, harga.
- **Pengguna** — aktif/nonaktif akun, penautan akun ke data dokter.
- **Audit trail** — filter per aksi, jenis data, pengguna, dan IP.
- **Log login** — riwayat akses beserta status.

## Peran dan hak akses

| Modul | Admin | Registrasi | Dokter | Apoteker |
|---|:--:|:--:|:--:|:--:|
| Dashboard | ✅ | ✅ | ✅ | ✅ |
| Pasien (lihat) | ✅ | ✅ | ✅ | — |
| Pasien (kelola) | ✅ | ✅ | — | — |
| Kunjungan (lihat) | ✅ | ✅ | ✅ | — |
| Kunjungan (kelola) | ✅ | ✅ | — | — |
| Kunjungan (proses) | ✅ | ✅ | ✅ | — |
| Rekam medis (lihat) | ✅ | — | ✅ | ✅ |
| Rekam medis (kelola) | ✅ | — | ✅ | — |
| Obat (lihat) | ✅ | — | ✅ | ✅ |
| Obat (kelola) | ✅ | — | — | ✅ |
| Poli & dokter | ✅ | — | — | — |
| Pengguna | ✅ | — | — | — |
| Log login | ✅ | — | — | — |
| Audit trail | ✅ | — | — | — |

## Alur penggunaan

```mermaid
sequenceDiagram
    actor P as Pasien
    participant R as Petugas Registrasi
    participant D as Dokter
    participant A as Apoteker

    P->>R: Datang & mendaftar
    R->>R: Buat data pasien + kunjungan
    R-->>P: Nomor antrean
    D->>D: Periksa & isi rekam medis
    D->>A: Kirim resep
    A->>A: Siapkan obat & cek stok
    Note over R,A: Setiap perubahan data tercatat di audit trail
```

## Stack

| Bagian | Teknologi |
|---|---|
| Framework | Laravel 13 (PHP 8.3) |
| Database | PostgreSQL 16 (Docker) |
| Tampilan | Blade + Bootstrap 5 + Bootstrap Icons (CDN) |
| Autentikasi | Session Laravel, rate limiter, log login |
| Pengujian | PHPUnit — 31 tes, 123 assertion |
| Gaya kode | Laravel Pint |
| Zona waktu | `Asia/Jakarta` |

Tanpa build step frontend: tidak ada Node/Vite yang perlu dijalankan.

## Arsitektur

```mermaid
flowchart LR
    B[Browser] -->|HTTP| W[Laravel 13]
    W --> MW[Middleware<br/>auth · can · throttle]
    MW --> C[Controller]
    C --> M[Eloquent Model]
    M --> DB[(PostgreSQL 16<br/>Docker)]
    M --> AU[LogsAudit trait]
    AU --> DB
```

Alur satu permintaan:

1. **Middleware** memastikan pengguna sudah login, akunnya aktif, dan punya izin
   (`can:pasien.kelola`).
2. **Controller** memvalidasi input lalu memanggil model.
3. **Model** menyimpan perubahan; trait `LogsAudit` otomatis menulis satu baris
   ke `audit_logs`.
4. **Blade** merender halaman dengan menu yang sudah disaring sesuai izin.

## Mulai cepat

**Prasyarat:** PHP 8.3+, Composer, Docker.

```bash
# 1. Dependensi
composer install

# 2. Konfigurasi
cp .env.example .env
php artisan key:generate

# 3. Nyalakan PostgreSQL
docker compose up -d

# 4. Buat tabel + data demo
php artisan migrate:fresh --seed

# 5. Jalankan
php artisan serve
```

Buka <http://127.0.0.1:8000> untuk halaman publik, dan
<http://127.0.0.1:8000/login> untuk masuk sebagai pegawai.

> [!NOTE]
> `npm run dev` **tidak diperlukan**. CSS/JS memakai CDN, bukan Vite.

## Akun demo

Semua akun memakai password `password` (khusus data demo, bisa diubah lewat
`SEED_PASSWORD` di `.env`).

| Email | Peran | Bisa mengakses |
|---|---|---|
| `admin@rsdrjth.test` | Administrator | Semua modul, pengguna, audit trail |
| `registrasi@rsdrjth.test` | Petugas Registrasi | Pasien & kunjungan |
| `dokter@rsdrjth.test` | Dokter | Pemeriksaan & rekam medis |
| `apoteker@rsdrjth.test` | Apoteker | Farmasi & stok obat |

> [!WARNING]
> Ganti atau hapus seluruh akun demo sebelum aplikasi dipakai dengan data nyata.

## Pengujian

```bash
php artisan test
```

Hasil: **31 tes, 123 assertion**.

| Cakupan | Contoh yang diuji |
|---|---|
| Autentikasi | Login berhasil/gagal, pembatasan percobaan, akun nonaktif |
| Otorisasi | Setiap peran hanya bisa membuka modulnya; akses lain ditolak 403 |
| Pasien | Validasi NIK, nomor RM otomatis, pencarian |
| Pengguna | Pembuatan akun, perubahan peran, penonaktifan |
| Audit trail | Dibuat/diubah/dihapus, nilai lama vs baru, password tidak tersimpan |

Tes berjalan di SQLite in-memory sehingga tidak menyentuh database development.

## Keamanan

| Area | Penerapan |
|---|---|
| **Rahasia** | `.env` tidak pernah masuk Git. `.gitignore` menutup `.env.*`, `*.pem`, `*.key`, `*.sql`, dan dump database. |
| **Kata sandi** | Di-hash dengan bcrypt. **Tidak pernah** ditulis ke audit trail maupun log. |
| **Login** | Maksimal 5 percobaan/60 detik per email + IP; akun nonaktif ditolak. |
| **Otorisasi** | RBAC diperiksa di server lewat middleware `can:` — menyembunyikan tombol tidak cukup. |
| **Sesi** | Disimpan di database, cookie `HttpOnly`, ID sesi diperbarui setelah login. |
| **CSRF** | Token wajib di setiap form POST/PUT/DELETE. |
| **Audit** | Mencatat pengguna, waktu, IP, perangkat, dan perubahan nilai. |
| **Seeder** | Menolak membuat akun demo saat `APP_ENV=production`. |
| **Pemeriksaan** | `bash scripts/cek-rahasia.sh` memeriksa repo sebelum push. |

Pemeriksaan rahasia sebelum push:

```bash
bash scripts/cek-rahasia.sh
```

**Untuk produksi**, tambahkan: HTTPS, 2FA untuk administrator, idle timeout,
rotasi kredensial, dan backup terenkripsi.

## Keputusan teknis

**Audit trail memakai trait, bukan package.** Package audit menambah dependensi
dan konfigurasi yang sulit dikendalikan. Dengan trait `LogsAudit`, setiap model
cukup menambahkan `use LogsAudit` dan otomatis tercatat pada event `created`,
`updated`, dan `deleted`. Kolom sensitif dikecualikan di satu tempat.

**Nilai password tidak pernah disimpan.** Kolom `password` dikecualikan dari
audit. Yang tercatat hanya fakta bahwa akun diubah — bukan isi password.

**Perubahan dari console tidak dicatat.** Seeder dan `artisan tinker` sengaja
dilewati agar data demo tidak mengotori audit trail. Audit hanya merekam
perubahan yang dilakukan pengguna lewat aplikasi.

**RBAC memakai Enum + Gate.** Peran dan izin didefinisikan sebagai Enum PHP,
sehingga salah ketik nama izin langsung menjadi error saat development, bukan
bug diam-diam di produksi.

**PostgreSQL, bukan SQLite.** Lebih dekat ke kondisi produksi: tipe `json` untuk
audit trail, tipe data ketat, dan siap untuk banyak pengguna bersamaan. Docker
dipakai agar versi database seragam di semua komputer.

**Pembatasan login.** `RateLimiter` membatasi percobaan per email + IP, dan
setiap percobaan dicatat di `login_logs` untuk mendeteksi akses tidak sah.

## Batasan

Proyek ini adalah **prototipe yang berjalan**, belum sistem siap produksi:

- Belum ada modul kasir/billing, laboratorium, radiologi, dan rawat inap.
- Belum ada cetak PDF (resep, kuitansi, surat keterangan).
- Belum terintegrasi SATUSEHAT/HL7 FHIR.
- Belum ada 2FA dan idle timeout.
- Belum ada backup otomatis terjadwal.
- Data demo masih sedikit.

## Rencana pengembangan

- [ ] Modul kasir dan cetak kuitansi PDF
- [ ] Grafik kunjungan dan pendapatan di dashboard
- [ ] 2FA untuk administrator
- [ ] Backup otomatis harian + uji restore
- [ ] Dockerfile aplikasi agar `docker compose up` menjalankan semuanya
- [ ] CI GitHub Actions menjalankan tes di setiap push
- [ ] Integrasi SATUSEHAT

---

<div align="center">

Dibuat sebagai proyek portofolio **fullstack**: Laravel · PostgreSQL · RBAC · Audit Trail · Automated Test

</div>
