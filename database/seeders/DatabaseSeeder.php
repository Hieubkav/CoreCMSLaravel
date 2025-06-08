<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Core Framework sample data creation...');

        $this->call([
            // Users and authentication
            UserSeeder::class,

            // Basic data
            SettingSeeder::class,
            CatPostSeeder::class,
            SliderSeeder::class,

            // Main content
            PostSeeder::class,
        ]);

        $this->command->info('✅ Core Framework sample data created successfully!');
        $this->command->info('📊 Summary:');
        $this->command->info('   - Users: ' . \App\Models\User::count());
        $this->command->info('   - Posts: ' . \App\Models\Post::count());
        $this->command->info('   - Settings: ' . \App\Models\Setting::count());
        $this->command->info('🎉 Core Framework is ready to use!');
    }
}
