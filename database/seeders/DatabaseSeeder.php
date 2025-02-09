<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            LocationSeeder::class,
            CourseSeeder::class,
        ]);

        $david = new User;
        $david->name_first = 'David';
        $david->name_last = 'Nahodyl';
        $david->email = 'david.nahodyl@gmail.com';
        $david->password = Hash::make('OSMIUM-roe-madcap');
        $david->assignRole('Admin');
        $david->save();
    }
}
