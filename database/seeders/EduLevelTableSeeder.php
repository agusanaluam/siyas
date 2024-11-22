<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EduLevelTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('edu_level')->delete();
        
        \DB::table('edu_level')->insert(array (
            0 => 
            array (
                'id' => 1,
                'level_name' => 'SD/ MI',
                'created_at' => '2024-03-18 12:10:08',
                'updated_at' => '2024-03-18 12:10:08',
            ),
            1 => 
            array (
                'id' => 2,
                'level_name' => 'SMP/ MTs',
                'created_at' => '2024-03-18 12:10:08',
                'updated_at' => '2024-03-18 12:10:08',
            ),
            2 => 
            array (
                'id' => 3,
                'level_name' => 'SMA/ SMK / MA',
                'created_at' => '2024-03-18 12:10:49',
                'updated_at' => '2024-03-18 12:10:49',
            ),
            3 => 
            array (
                'id' => 4,
                'level_name' => 'S1/ Sarjana',
                'created_at' => '2024-03-18 12:10:49',
                'updated_at' => '2024-03-18 12:10:49',
            ),
            4 => 
            array (
                'id' => 5,
                'level_name' => 'S2/ Magister',
                'created_at' => '2024-03-18 12:11:06',
                'updated_at' => '2024-03-18 12:11:06',
            ),
            5 => 
            array (
                'id' => 6,
                'level_name' => 'S3/ Doktor',
                'created_at' => '2024-03-18 12:11:06',
                'updated_at' => '2024-03-18 12:11:06',
            ),
        ));
        
        
    }
}