<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Registrasi = 'registrasi';
    case Dokter = 'dokter';
    case Apoteker = 'apoteker';

    public function label(): string
    {
        return match ($this) {
            Role::Admin => 'Administrator',
            Role::Registrasi => 'Petugas Registrasi',
            Role::Dokter => 'Dokter',
            Role::Apoteker => 'Apoteker',
        };
    }

    public function deskripsi(): string
    {
        return match ($this) {
            Role::Admin => 'Akses penuh: pengguna, master data, dan seluruh modul.',
            Role::Registrasi => 'Pendaftaran pasien dan kunjungan di loket.',
            Role::Dokter => 'Pemeriksaan pasien dan pengisian rekam medis.',
            Role::Apoteker => 'Farmasi, stok obat, dan pembacaan resep.',
        };
    }

    public function warna(): string
    {
        return match ($this) {
            Role::Admin => 'danger',
            Role::Registrasi => 'primary',
            Role::Dokter => 'success',
            Role::Apoteker => 'warning',
        };
    }

    /**
     * Daftar izin yang dimiliki peran ini.
     *
     * @return array<int, Permission>
     */
    public function izin(): array
    {
        return match ($this) {
            Role::Admin => Permission::cases(),
            Role::Registrasi => [
                Permission::Dashboard,
                Permission::PasienLihat,
                Permission::PasienKelola,
                Permission::KunjunganLihat,
                Permission::KunjunganKelola,
                Permission::KunjunganProses,
            ],
            Role::Dokter => [
                Permission::Dashboard,
                Permission::PasienLihat,
                Permission::KunjunganLihat,
                Permission::KunjunganProses,
                Permission::RekamMedisLihat,
                Permission::RekamMedisKelola,
                Permission::ObatLihat,
            ],
            Role::Apoteker => [
                Permission::Dashboard,
                Permission::ObatLihat,
                Permission::ObatKelola,
                Permission::RekamMedisLihat,
            ],
        };
    }

    public function punya(Permission $permission): bool
    {
        return in_array($permission, $this->izin(), true);
    }

    /**
     * Pilihan peran untuk dropdown.
     *
     * @return array<string, string>
     */
    public static function opsi(): array
    {
        $opsi = [];

        foreach (self::cases() as $role) {
            $opsi[$role->value] = $role->label();
        }

        return $opsi;
    }
}
