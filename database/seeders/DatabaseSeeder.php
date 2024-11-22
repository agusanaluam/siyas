<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $this->call(UsersTableSeeder::class);
        $this->call(MVolunteerTableSeeder::class);
        $this->call(DtDesakelTableSeeder::class);
        $this->call(DtKecamatanTableSeeder::class);
        $this->call(DtKotakabTableSeeder::class);
        $this->call(DtProvinsiTableSeeder::class);
        $this->call(EduLevelTableSeeder::class);
        $this->call(MGroupTableSeeder::class);
    }
}
