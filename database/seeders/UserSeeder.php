<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👤 Creating Core Framework Users...');

        $users = [
            [
                'name' => 'Core Admin',
                'email' => 'admin@coreframework.com',
                'password' => Hash::make('password'),
                'status' => 'active',
                'order' => 1,
            ],
            [
                'name' => 'Demo User',
                'email' => 'demo@coreframework.com',
                'password' => Hash::make('password'),
                'status' => 'active',
                'order' => 2,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info("✅ Created " . count($users) . " users for Core Framework");
    }
}
