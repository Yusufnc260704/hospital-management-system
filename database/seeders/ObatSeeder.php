<?php

namespace Database\Seeders;

use App\Models\Obat;
use Illuminate\Database\Seeder;

class ObatSeeder extends Seeder
{
    /**
     * @var list<array<string, string|int>>
     */
    private array $obat = [
        ['nama' => 'Paracetamol 500mg', 'jenis' => 'tablet', 'satuan' => 'tablet', 'stok' => 850, 'stok_minimum' => 100, 'harga' => 500],
        ['nama' => 'Amoxicillin 500mg', 'jenis' => 'kapsul', 'satuan' => 'kapsul', 'stok' => 420, 'stok_minimum' => 100, 'harga' => 1500],
        ['nama' => 'Cetirizine 10mg', 'jenis' => 'tablet', 'satuan' => 'tablet', 'stok' => 60, 'stok_minimum' => 100, 'harga' => 1000],
        ['nama' => 'Antasida DOEN', 'jenis' => 'sirup', 'satuan' => 'botol', 'stok' => 48, 'stok_minimum' => 20, 'harga' => 9000],
        ['nama' => 'Omeprazole 20mg', 'jenis' => 'kapsul', 'satuan' => 'kapsul', 'stok' => 8, 'stok_minimum' => 50, 'harga' => 2000],
        ['nama' => 'Vitamin B Complex', 'jenis' => 'tablet', 'satuan' => 'tablet', 'stok' => 300, 'stok_minimum' => 50, 'harga' => 750],
    ];

    public function run(): void
    {
        foreach ($this->obat as $data) {
            Obat::firstOrCreate(['nama' => $data['nama']], $data);
        }
    }
}
