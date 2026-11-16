<?php

namespace Database\Factories;

use App\Models\Obat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Obat>
 */
class ObatFactory extends Factory
{
    public function definition(): array
    {
        $satuan = $this->faker->randomElement(['tablet', 'kapsul', 'sirup', 'salep', 'injeksi']);

        return [
            'nama' => $this->faker->words(2, true),
            'jenis' => $satuan,
            'satuan' => $satuan,
            'stok' => $this->faker->numberBetween(0, 200),
            'stok_minimum' => $this->faker->numberBetween(5, 15),
            'harga' => $this->faker->numberBetween(1000, 50000),
        ];
    }
}
