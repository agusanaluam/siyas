<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MGroupTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('m_group')->delete();
        
        \DB::table('m_group')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'SN Group',
                'description' => 'SukaNegara Group',
                'created_at' => '2024-02-22 08:49:08',
                'updated_at' => '2024-11-14 07:47:24',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Atlas Group',
                'description' => 'Atlas Group',
                'created_at' => '2024-02-22 09:02:17',
                'updated_at' => '2024-11-14 07:47:43',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'RCM Group',
                'description' => 'Rancamanyar Group',
                'created_at' => '2024-02-22 09:03:42',
                'updated_at' => '2024-11-14 07:48:08',
            ),
        ));
        
        
    }
}