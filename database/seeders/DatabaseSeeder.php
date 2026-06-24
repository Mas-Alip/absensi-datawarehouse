<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\DivisiSeeder;
use Database\Seeders\JabatanSeeder;
use Database\Seeders\AbsensiSeeder;
use Database\Seeders\PegawaiSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DivisiSeeder::class,
            JabatanSeeder::class,
            PegawaiSeeder::class,
            AbsensiSeeder::class,
        ]);

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password')]
        );
    }
}
