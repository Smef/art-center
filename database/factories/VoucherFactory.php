<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voucher>
 */
class VoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'po_number' => $this->faker->word,
            'number' => $this->faker->numberBetween(100, 9999),
            'date' => $this->faker->dateTimeBetween('-1 year'),
            'total' => $this->faker->randomFloat(2, 0, 1000),
            'company_id' => null,
        ];
    }
}
