<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\LogLoginController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\RekamMedisController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function (): void {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    /*
     * Catatan urutan: rute "kelola" (create/store/...) didaftarkan lebih dulu,
     * baru rute "lihat" (index/show), supaya /pasien/create tidak tertangkap
     * oleh /pasien/{pasien}.
     */

    // Pasien: registrasi & admin boleh mengelola, dokter hanya melihat.
    Route::resource('pasien', PasienController::class)
        ->except(['index', 'show'])
        ->middleware('can:pasien.kelola');
    Route::resource('pasien', PasienController::class)
        ->only(['index', 'show'])
        ->middleware('can:pasien.lihat');

    // Pendaftaran kunjungan.
    Route::resource('kunjungan', KunjunganController::class)
        ->except(['index', 'show'])
        ->middleware('can:kunjungan.kelola');
    Route::post('kunjungan/{kunjungan}/proses', [KunjunganController::class, 'proses'])
        ->name('kunjungan.proses')
        ->middleware('can:kunjungan.proses');
    Route::post('kunjungan/{kunjungan}/selesai', [KunjunganController::class, 'selesai'])
        ->name('kunjungan.selesai')
        ->middleware('can:kunjungan.kelola');
    Route::resource('kunjungan', KunjunganController::class)
        ->only(['index', 'show'])
        ->middleware('can:kunjungan.lihat');

    // Rekam medis: dokter mengisi, admin & apoteker boleh membaca.
    Route::resource('rekam-medis', RekamMedisController::class)
        ->except(['index', 'show'])
        ->parameters(['rekam-medis' => 'rekamMedis'])
        ->middleware('can:rekam-medis.kelola');
    Route::resource('rekam-medis', RekamMedisController::class)
        ->only(['index', 'show'])
        ->parameters(['rekam-medis' => 'rekamMedis'])
        ->middleware('can:rekam-medis.lihat');

    // Farmasi.
    Route::resource('obat', ObatController::class)
        ->except(['index', 'show'])
        ->middleware('can:obat.kelola');
    Route::post('obat/{obat}/restok', [ObatController::class, 'restok'])
        ->name('obat.restok')
        ->middleware('can:obat.kelola');
    Route::resource('obat', ObatController::class)
        ->only(['index'])
        ->middleware('can:obat.lihat');

    // Master data: hanya administrator.
    Route::resource('poli', PoliController::class)->except(['show'])->middleware('can:poli.kelola');
    Route::resource('dokter', DokterController::class)->except(['show'])->middleware('can:dokter.kelola');

    // Administrasi sistem.
    Route::resource('pengguna', PenggunaController::class)
        ->except(['show'])
        ->middleware('can:pengguna.kelola');
    Route::get('log-login', [LogLoginController::class, 'index'])
        ->name('log-login.index')
        ->middleware('can:log-login.lihat');
    Route::get('audit-log', [AuditLogController::class, 'index'])
        ->name('audit-log.index')
        ->middleware('can:audit.lihat');
});
