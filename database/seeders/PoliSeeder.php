<?php

namespace Database\Seeders;

use App\Models\Poli;
use Illuminate\Database\Seeder;

class PoliSeeder extends Seeder
{
    /**
     * @var list<array<string, string>>
     */
    private array $poli = [
        ['nama' => 'Poli Umum', 'deskripsi' => 'Layanan medis umum, pemeriksaan awal dan tindakan ringan.'],
        ['nama' => 'Poli Anak', 'deskripsi' => 'Layanan kesehatan spesialis anak dan imunisasi.'],
        ['nama' => 'Poli Gigi', 'deskripsi' => 'Layanan kesehatan gigi dan mulut.'],
        ['nama' => 'Poli Penyakit Dalam', 'deskripsi' => 'Layanan spesialis penyakit dalam untuk dewasa.'],
    ];

    public function run(): void
    {
        foreach ($this->poli as $data) {
            Poli::firstOrCreate(['nama' => $data['nama']], $data);
        }
    }
}
