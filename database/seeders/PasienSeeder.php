<?php

namespace Database\Seeders;

use App\Models\Pasien;
use Illuminate\Database\Seeder;

class PasienSeeder extends Seeder
{
    /**
     * @var list<array<string, string|int>>
     */
    private array $pasien = [
        ['no_rm' => 'RM-00001', 'nama' => 'Budi Wicaksono', 'nik' => '3201010101800001', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '1980-01-01', 'alamat' => 'Jl. Mawar No. 1, Yogyakarta', 'no_telp' => '081290001001'],
        ['no_rm' => 'RM-00002', 'nama' => 'Siti Maisaroh', 'nik' => '3201010102850002', 'jenis_kelamin' => 'P', 'tanggal_lahir' => '1985-01-02', 'alamat' => 'Jl. Melati No. 2, Yogyakarta', 'no_telp' => '081290001002'],
        ['no_rm' => 'RM-00003', 'nama' => 'Ahmad Fauzan', 'nik' => '3201010103200003', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '2020-03-01', 'alamat' => 'Jl. Anggrek No. 3, Yogyakarta', 'no_telp' => '081290001003'],
        ['no_rm' => 'RM-00004', 'nama' => 'Dewi Lestari', 'nik' => '3201010104750004', 'jenis_kelamin' => 'P', 'tanggal_lahir' => '1975-04-01', 'alamat' => 'Jl. Kenanga No. 4, Yogyakarta', 'no_telp' => '081290001004'],
    ];

    public function run(): void
    {
        foreach ($this->pasien as $data) {
            Pasien::firstOrCreate(['no_rm' => $data['no_rm']], $data);
        }
    }
}
