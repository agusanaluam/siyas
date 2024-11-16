<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MVolunteerTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('m_volunteer')->delete();

        \DB::table('m_volunteer')->insert(array (
            0 =>
            array (
                'id' => 1,
                'group_id' => 2,
                'name' => 'Agus Maulana',
                'sex' => 'L',
                'email' => 'sugaanaluam@gmail.com',
                'phone_number' => 82115979098000,
                'address' => 'test alamat',
                'notes' => NULL,
                'status' => 1,
                'spv' => 0,
                'address_code' => '13.01.02.2003',
                'profile_picture' => 'profile_pictures/images_1730171017.jpeg',
                'nik' => 3204121808960003,
                'birth_date' => '2020-10-02',
                'points' => 0.0,
                'created_at' => '2024-10-29 12:38:03',
                'updated_at' => '2024-10-29 12:38:03',
                'deleted_at' => NULL,
            ),
        ));


    }
}
