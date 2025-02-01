<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'description' => $this->faker->sentence(),
            'start_date' => $this->faker->date,
            'address_street' => $this->faker->streetAddress(),
            'address_city' => $this->faker->city(),
            'address_state' => $this->faker->stateAbbr(),
            'address_zip' => substr($this->faker->postcode(), 0, 5),
            // assign this contact to an existing company 75% of the time. Make a new company if no companies exist.
            'company_id' => ($this->faker->boolean(75) ? Company::inRandomOrder()->first()->id : null) ?? Company::factory(),
            'status' => $this->faker->randomElement([ProjectStatus::Active->value, ProjectStatus::Completed->value, ProjectStatus::Cancelled->value]),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($project) {
            $documentCount = $this->faker->numberBetween(1, 5);
            // create fake documents
            $documents = collect(range(1, $documentCount))->map(function () {
                $fileName = $this->faker->word().'.'.$this->faker->fileExtension();
                $wordCount = $this->faker->numberBetween(1, 3);

                return [
                    'description' => $this->faker->words($wordCount, true),
                    'file_name' => $fileName,
                ];
            });

            $project->projectDocuments()->createMany($documents);
        });
    }
}
