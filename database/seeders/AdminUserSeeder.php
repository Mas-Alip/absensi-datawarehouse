<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default admin user if it doesn't exist
        User::updateOrCreate(
            ['email' => 'admin@absensi.test'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'),
                'role' => UserRole::ADMIN,
                'email_verified_at' => now(),
            ]
        );

        // Create a default manager user if it doesn't exist
        User::updateOrCreate(
            ['email' => 'manager@absensi.test'],
            [
                'name' => 'Manager',
                'password' => Hash::make('password123'),
                'role' => UserRole::MANAGER,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin and Manager users created/updated successfully!');
    }
}
