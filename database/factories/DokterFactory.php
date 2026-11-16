<?php

namespace Database\Factories;

use App\Models\Dokter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dokter>
 */
class DokterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'poli_id' => PoliFactory::new()->create()->id,
            'nama' => 'dr. '.$this->faker->name(),
            'spesialisasi' => $this->faker->jobTitle(),
            'no_telp' => $this->faker->numerify('08##########'),
        ];
    }
}
