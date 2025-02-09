<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestingSeeder extends Seeder
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
        ]);

        $david = new User;
        $david->name = 'David Nahodyl';
        $david->email = 'david@gearboxgo.com';
        $david->password = Hash::make('OSMIUM-roe-madcap');
        $david->assignRole('Admin');
        $david->save();
    }
}
