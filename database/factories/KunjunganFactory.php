<?php

namespace Database\Factories;

use App\Enums\StatusKunjungan;
use App\Models\Dokter;
use App\Models\Kunjungan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kunjungan>
 */
class KunjunganFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pasien_id' => PasienFactory::new()->create()->id,
            'dokter_id' => DokterFactory::new()->create()->id,
            'poli_id' => fn (array $attributes): int => Dokter::find($attributes['dokter_id'])->poli_id,
            'tanggal_kunjungan' => today(),
            'keluhan' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(StatusKunjungan::cases()),
        ];
    }
}
