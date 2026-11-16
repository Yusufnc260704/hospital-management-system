<?php

namespace Database\Factories;

use App\Models\RekamMedis;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RekamMedis>
 */
class RekamMedisFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kunjungan_id' => KunjunganFactory::new()->create()->id,
            'diagnosa' => $this->faker->sentence(),
            'tindakan' => $this->faker->optional()->sentence(),
            'resep' => $this->faker->optional()->sentence(),
            'catatan' => $this->faker->optional()->paragraph(),
        ];
    }
}
