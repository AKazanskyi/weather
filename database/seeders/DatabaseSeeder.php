<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
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
        User::updateOrCreate([
            'email' => 'test1@email.com',
        ], [
            'name' => 'Developer',
            'password' => bcrypt('test2023'),
        ]);
        Setting::updateOrCreate([
            'user_id' => 1,
        ], [
            'pause_until' => Carbon::now()->addDay(),
            'max_uv' => 7,
            'max_pr' => 7
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
