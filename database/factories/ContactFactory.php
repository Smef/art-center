<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_first' => $this->faker->firstName(),
            'name_last' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            // assign this contact to an existing company 75% of the time. Make a new company if no companies exist.
            'company_id' => ($this->faker->boolean(75) ? Company::inRandomOrder()->first()->id : null) ?? Company::factory(),
            'role' => $this->faker->jobTitle(),
        ];
    }
}
