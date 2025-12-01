<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Agus Maulana',
                'email' => 'sugaanaluam@gmail.com',
                'phone_number' => 82115979098000,
                'email_verified_at' => '2024-10-26 14:33:44',
                'password' => Hash::make('admin123'),
                'remember_token' => NULL,
                'volunteer_id' => 1,
                'level' => 'root',
                'created_at' => '2024-10-26 14:31:02',
                'updated_at' => '2024-10-29 03:35:52',
            ),
        ));
        
        
    }
}