<?php

namespace Database\Seeders;

use App\Enums\StatusKunjungan;
use App\Models\Dokter;
use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\RekamMedis;
use Illuminate\Database\Seeder;

class KunjunganSeeder extends Seeder
{
    public function run(): void
    {
        $pasien = [
            Pasien::where('no_rm', 'RM-00001')->firstOrFail(),
            Pasien::where('no_rm', 'RM-00002')->firstOrFail(),
            Pasien::where('no_rm', 'RM-00003')->firstOrFail(),
        ];

        $dokter = [
            Dokter::where('nama', 'dr. Andi Prasetyo')->firstOrFail(),
            Dokter::where('nama', 'dr. Siti Rahmawati, Sp.A')->firstOrFail(),
            Dokter::where('nama', 'dr. Rahmat Hidayat, Sp.PD')->firstOrFail(),
        ];

        $poliId = static fn (Dokter $dokter): int => Poli::findOrFail($dokter->poli_id)->id;

        $kunjunganHariIni = [
            ['pasien' => $pasien[0], 'dokter' => $dokter[0], 'keluhan' => 'Demam dan batuk dua hari.', 'status' => StatusKunjungan::Menunggu],
            ['pasien' => $pasien[2], 'dokter' => $dokter[1], 'keluhan' => 'Imunisasi HB lanjutan.', 'status' => StatusKunjungan::Menunggu],
            ['pasien' => $pasien[1], 'dokter' => $dokter[1], 'keluhan' => 'Kontrol tumbuh kembang anak.', 'status' => StatusKunjungan::Diproses],
            ['pasien' => $pasien[0], 'dokter' => $dokter[2], 'keluhan' => 'Nyeri perut naik turun.', 'status' => StatusKunjungan::Selesai, 'diagnosa' => 'Dispepsia', 'tindakan' => 'Pemberian obat sesuai resep dan anjuran istirahat.', 'resep' => 'Paracetamol 500mg 3x1 selama tiga hari.'],
        ];

        foreach ($kunjunganHariIni as $row) {
            $kunjungan = Kunjungan::firstOrCreate([
                'pasien_id' => $row['pasien']->id,
                'dokter_id' => $row['dokter']->id,
                'tanggal_kunjungan' => today(),
                'keluhan' => $row['keluhan'],
            ], [
                'poli_id' => $poliId($row['dokter']),
                'status' => $row['status'],
            ]);

            $this->buatRekamMedisUntukSelesai($row, $kunjungan);
        }

        $kunjunganLalu = [
            ['pasien' => $pasien[1], 'dokter' => $dokter[0], 'keluhan' => 'Pemeriksaan kesehatan berkala.', 'tanggal' => today()->subDay(), 'status' => StatusKunjungan::Selesai, 'diagnosa' => 'Sehat, tekanan darah normal', 'tindakan' => 'Edukasi gizi seimbang.', 'resep' => '-'],
            ['pasien' => $pasien[2], 'dokter' => $dokter[1], 'keluhan' => 'Demam disertai ruam.', 'tanggal' => today()->subDays(3), 'status' => StatusKunjungan::Selesai, 'diagnosa' => 'Varicella (cacar air) ringan', 'tindakan' => 'Pengobatan simptomatik dan penjelasan pembaruan ke orang tua.', 'resep' => 'Paracetamol sirup sesuai dosis.'],
            ['pasien' => $pasien[1], 'dokter' => $dokter[0], 'keluhan' => 'Sakit kepala berkepanjangan.', 'tanggal' => today()->subWeeks(2), 'status' => StatusKunjungan::Selesai, 'diagnosa' => 'Tension headache', 'tindakan' => 'Anjuran istirahat dan pemeriksaan lanjutan bila berlanjut.', 'resep' => '-'],
        ];

        foreach ($kunjunganLalu as $row) {
            $kunjungan = Kunjungan::firstOrCreate([
                'pasien_id' => $row['pasien']->id,
                'dokter_id' => $row['dokter']->id,
                'tanggal_kunjungan' => $row['tanggal'],
                'keluhan' => $row['keluhan'],
            ], [
                'poli_id' => $poliId($row['dokter']),
                'status' => $row['status'],
            ]);

            $this->buatRekamMedisUntukSelesai($row, $kunjungan);
        }
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function buatRekamMedisUntukSelesai(array $row, Kunjungan $kunjungan): void
    {
        if (! isset($row['diagnosa']) || $kunjungan->rekamMedis()->exists()) {
            return;
        }

        RekamMedis::create([
            'kunjungan_id' => $kunjungan->id,
            'diagnosa' => $row['diagnosa'],
            'tindakan' => $row['tindakan'] ?? null,
            'resep' => $row['resep'] ?? null,
            'catatan' => $row['catatan'] ?? null,
        ]);
    }
}
