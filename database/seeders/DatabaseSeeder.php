<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'test@email.com',
        ], [
            'name' => 'Developer',
            'password' => bcrypt('test2023'),
        ]);
        Service::updateOrCreate([
            'name' => 'openweathermap',
        ], []);
        Service::updateOrCreate([
            'name' => 'tommorowio',
        ], []);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
