<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Headquarters',
                'website' => 'https://example.com',
                'phone' => '555-555-5555',
                'address_street' => '123 Main St',
                'address_city' => 'Anytown',
                'address_state' => 'NY',
                'address_zip' => '12345',
            ],
            [
                'name' => 'Branch Office',
                'website' => 'https://example.com',
                'phone' => '555-555-5555',
                'address_street' => '123 Main St',
                'address_city' => 'Anytown',
                'address_state' => 'NY',
                'address_zip' => '12345',
            ],
        ];

        foreach ($locations as $location) {
            \App\Models\Location::factory()->create($location);
        }
    }
}
