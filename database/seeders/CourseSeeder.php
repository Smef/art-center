<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Location;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all locations
        $locations = Location::all();

        // Check if locations exist
        if ($locations->isEmpty()) {
            $this->command->warn('No locations found. Please seed the locations table first.');

            return;
        }

        // Create 200 courses and assign them to a random location
        Course::factory(200)->create(function () use ($locations) {
            return [
                'location_id' => $locations->random()->id,
            ];
        });

        $this->command->info('Courses seeded successfully.');
    }
}
