<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin email from environment or use default
        $adminEmail = env('ADMIN_EMAIL', 'admin@example.com');
        $adminName = env('ADMIN_NAME', 'Admin User');
        $adminPassword = env('ADMIN_PASSWORD', 'password');

        // Check if admin user already exists
        $existingAdmin = User::where('email', $adminEmail)->first();

        if ($existingAdmin) {
            $this->command->info("Admin user with email {$adminEmail} already exists. Skipping...");
            return;
        }

        // Create admin user
        User::create([
            'name' => $adminName,
            'email' => $adminEmail,
            'password' => Hash::make($adminPassword),
            'email_verified_at' => now(),
        ]);

        $this->command->info("Admin user created successfully!");
        $this->command->info("Email: {$adminEmail}");
        $this->command->warn("Password: {$adminPassword}");
        $this->command->warn("Please change the password after first login!");
    }
}
