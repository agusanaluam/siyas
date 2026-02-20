<?php

namespace Database\Seeders;

use App\Models\RamadanDefaultHabit;
use Illuminate\Database\Seeder;

class RamadanDefaultHabitSeeder extends Seeder
{
    public function run(): void
    {
        $habits = [
            ['name' => 'Puasa (Sahur & Buka tepat waktu)', 'icon' => 'moon', 'points' => 10, 'sort_order' => 1],
            ['name' => 'Shalat 5 Waktu', 'icon' => 'pray', 'points' => 10, 'sort_order' => 2],
            ['name' => 'Shalat Rawatib', 'icon' => 'pray', 'points' => 10, 'sort_order' => 3],
            ['name' => 'Shalat Tarawih & Witir', 'icon' => 'mosque', 'points' => 10, 'sort_order' => 4],
            ['name' => 'Tilawah Al-Quran / Murajaah Hafalan', 'icon' => 'book-open', 'points' => 10, 'sort_order' => 5],
            ['name' => 'Sedekah Harian', 'icon' => 'heart', 'points' => 10, 'sort_order' => 6],
            ['name' => 'Dzikir Setelah Shalat', 'icon' => 'sun', 'points' => 10, 'sort_order' => 7],
            ['name' => 'Istighfar Sebelum Tidur', 'icon' => 'star', 'points' => 10, 'sort_order' => 8],
            ['name' => 'Baca Buku', 'icon' => 'book', 'points' => 10, 'sort_order' => 9],
            ['name' => 'Olahraga Ringan', 'icon' => 'heart-pulse', 'points' => 10, 'sort_order' => 10],
        ];

        foreach ($habits as $habit) {
            RamadanDefaultHabit::updateOrCreate(
                ['name' => $habit['name']],
                $habit
            );
        }
    }
}
