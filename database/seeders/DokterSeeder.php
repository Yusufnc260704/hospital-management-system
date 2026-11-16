<?php

namespace Database\Seeders;

use App\Models\Dokter;
use App\Models\Poli;
use Illuminate\Database\Seeder;

class DokterSeeder extends Seeder
{
    public function run(): void
    {
        $poliUmum = Poli::where('nama', 'Poli Umum')->firstOrFail();
        $poliAnak = Poli::where('nama', 'Poli Anak')->firstOrFail();
        $poliGigi = Poli::where('nama', 'Poli Gigi')->firstOrFail();
        $poliPenyakitDalam = Poli::where('nama', 'Poli Penyakit Dalam')->firstOrFail();

        $dokter = [
            ['poli_id' => $poliUmum->id, 'nama' => 'dr. Andi Prasetyo', 'spesialisasi' => 'Dokter Umum', 'no_telp' => '081234567801'],
            ['poli_id' => $poliUmum->id, 'nama' => 'dr. Dian Kusuma', 'spesialisasi' => 'Dokter Umum', 'no_telp' => '081234567802'],
            ['poli_id' => $poliAnak->id, 'nama' => 'dr. Siti Rahmawati, Sp.A', 'spesialisasi' => 'Spesialis Anak', 'no_telp' => '081234567803'],
            ['poli_id' => $poliGigi->id, 'nama' => 'drg. Budi Santoso', 'spesialisasi' => 'Dokter Gigi', 'no_telp' => '081234567804'],
            ['poli_id' => $poliPenyakitDalam->id, 'nama' => 'dr. Rahmat Hidayat, Sp.PD', 'spesialisasi' => 'Spesialis Penyakit Dalam', 'no_telp' => '081234567805'],
        ];

        foreach ($dokter as $data) {
            Dokter::firstOrCreate(['nama' => $data['nama']], $data);
        }
    }
}
