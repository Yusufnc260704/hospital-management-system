<?php

namespace Database\Factories;

use App\Models\Pasien;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pasien>
 */
class PasienFactory extends Factory
{
    public function definition(): array
    {
        return [
            'no_rm' => 'RM-'.$this->faker->unique()->numerify('#####'),
            'nama' => $this->faker->name(),
            'nik' => $this->faker->unique()->numerify('################'),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'tanggal_lahir' => $this->faker->date(max: '-10 years'),
            'alamat' => $this->faker->address(),
            'no_telp' => $this->faker->numerify('08##########'),
        ];
    }
}
