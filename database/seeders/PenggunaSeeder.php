<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Dokter;
use App\Models\User;
use Illuminate\Database\Seeder;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        // Akun demo tidak boleh pernah dibuat di server produksi.
        if (app()->isProduction()) {
            $this->command?->warn('PenggunaSeeder dilewati karena APP_ENV=production.');

            return;
        }

        $dokter = Dokter::orderBy('id')->first();
        $password = (string) env('SEED_PASSWORD', 'password');

        $akun = [
            [
                'name' => 'Administrator',
                'email' => 'admin@rsdrjth.test',
                'role' => Role::Admin,
                'dokter_id' => null,
            ],
            [
                'name' => 'Petugas Registrasi',
                'email' => 'registrasi@rsdrjth.test',
                'role' => Role::Registrasi,
                'dokter_id' => null,
            ],
            [
                'name' => 'dr. Andi Prasetyo',
                'email' => 'dokter@rsdrjth.test',
                'role' => Role::Dokter,
                'dokter_id' => $dokter?->id,
            ],
            [
                'name' => 'Apoteker',
                'email' => 'apoteker@rsdrjth.test',
                'role' => Role::Apoteker,
                'dokter_id' => null,
            ],
        ];

        foreach ($akun as $data) {
            $pengguna = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => $password,
                    'role' => $data['role']->value,
                    'dokter_id' => $data['dokter_id'],
                    'is_active' => true,
                ],
            );

            $pengguna->forceFill([
                'name' => $data['name'],
                'role' => $data['role']->value,
                'dokter_id' => $data['dokter_id'],
                'is_active' => true,
            ])->save();
        }
    }
}
