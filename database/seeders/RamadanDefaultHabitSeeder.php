<?php

namespace Database\Seeders;

use App\Models\RamadanDefaultHabit;
use Illuminate\Database\Seeder;

class RamadanDefaultHabitSeeder extends Seeder
{
    public function run(): void
    {
        $habits = [
            // Positive habits
            ['name' => 'Puasa (Sahur & Buka tepat waktu)', 'icon' => 'moon', 'points' => 10, 'sort_order' => 1, 'type' => 'positive'],
            ['name' => 'Shalat 5 Waktu', 'icon' => 'pray', 'points' => 10, 'sort_order' => 2, 'type' => 'positive'],
            ['name' => 'Shalat Rawatib', 'icon' => 'pray', 'points' => 10, 'sort_order' => 3, 'type' => 'positive'],
            ['name' => 'Shalat Tarawih & Witir', 'icon' => 'mosque', 'points' => 10, 'sort_order' => 4, 'type' => 'positive'],
            ['name' => 'Tilawah Al-Quran / Murajaah Hafalan', 'icon' => 'book-open', 'points' => 10, 'sort_order' => 5, 'type' => 'positive'],
            ['name' => 'Sedekah Harian', 'icon' => 'heart', 'points' => 10, 'sort_order' => 6, 'type' => 'positive'],
            ['name' => 'Dzikir Setelah Shalat', 'icon' => 'sun', 'points' => 10, 'sort_order' => 7, 'type' => 'positive'],
            ['name' => 'Istighfar Sebelum Tidur', 'icon' => 'star', 'points' => 10, 'sort_order' => 8, 'type' => 'positive'],
            ['name' => 'Baca Buku', 'icon' => 'book', 'points' => 10, 'sort_order' => 9, 'type' => 'positive'],
            ['name' => 'Olahraga Ringan', 'icon' => 'heart-pulse', 'points' => 10, 'sort_order' => 10, 'type' => 'positive'],

            // Negative habits
            ['name' => 'Berkata Kotor / Menyakiti Lisan', 'icon' => 'warning', 'points' => 5, 'sort_order' => 11, 'type' => 'negative'],
            ['name' => 'Marah Berlebihan', 'icon' => 'warning', 'points' => 5, 'sort_order' => 12, 'type' => 'negative'],
            ['name' => 'Menunda Solat Tanpa Uzur', 'icon' => 'warning', 'points' => 5, 'sort_order' => 13, 'type' => 'negative'],
            ['name' => 'Ghibah', 'icon' => 'warning', 'points' => 5, 'sort_order' => 14, 'type' => 'negative'],
            ['name' => 'Lalai Menjaga Hati', 'icon' => 'warning', 'points' => 5, 'sort_order' => 15, 'type' => 'negative'],
        ];

        foreach ($habits as $habit) {
            RamadanDefaultHabit::updateOrCreate(
                ['name' => $habit['name']],
                $habit
            );
        }
    }
}
