<?php

namespace App\Enums;

enum Permission: string
{
    case Dashboard = 'dashboard';
    case PasienLihat = 'pasien.lihat';
    case PasienKelola = 'pasien.kelola';
    case PoliKelola = 'poli.kelola';
    case DokterKelola = 'dokter.kelola';
    case KunjunganLihat = 'kunjungan.lihat';
    case KunjunganKelola = 'kunjungan.kelola';
    case KunjunganProses = 'kunjungan.proses';
    case RekamMedisLihat = 'rekam-medis.lihat';
    case RekamMedisKelola = 'rekam-medis.kelola';
    case ObatLihat = 'obat.lihat';
    case ObatKelola = 'obat.kelola';
    case PenggunaKelola = 'pengguna.kelola';
    case LogLoginLihat = 'log-login.lihat';
    case AuditLihat = 'audit.lihat';
}
