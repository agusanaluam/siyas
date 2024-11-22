<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DtKotakabTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('dt_kotakab')->delete();
        
        \DB::table('dt_kotakab')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => '11.01',
                'name' => 'Aceh Selatan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'code' => '11.02',
                'name' => 'Aceh Tenggara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'code' => '11.03',
                'name' => 'Aceh Timur',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'code' => '11.04',
                'name' => 'Aceh Tengah',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'code' => '11.05',
                'name' => 'Aceh Barat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'code' => '11.06',
                'name' => 'Aceh Besar',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'code' => '11.07',
                'name' => 'Pidie',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'code' => '11.08',
                'name' => 'Aceh Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'code' => '11.09',
                'name' => 'Simeulue',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'code' => '11.10',
                'name' => 'Aceh Singkil',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'code' => '11.11',
                'name' => 'Bireuen',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'code' => '11.12',
                'name' => 'Aceh Barat Daya',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'code' => '11.13',
                'name' => 'Gayo Lues',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'code' => '11.14',
                'name' => 'Aceh Jaya',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'code' => '11.15',
                'name' => 'Nagan Raya',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'code' => '11.16',
                'name' => 'Aceh Tamiang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'code' => '11.17',
                'name' => 'Bener Meriah',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'code' => '11.18',
                'name' => 'Pidie Jaya',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'code' => '11.71',
                'name' => 'Banda Aceh',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'code' => '11.72',
                'name' => 'Sabang',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'code' => '11.73',
                'name' => 'Lhokseumawe',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'code' => '11.74',
                'name' => 'Langsa',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'code' => '11.75',
                'name' => 'Subulussalam',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'code' => '12.01',
                'name' => 'Tapanuli Tengah',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'code' => '12.02',
                'name' => 'Tapanuli Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'code' => '12.03',
                'name' => 'Tapanuli Selatan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'code' => '12.04',
                'name' => 'Nias',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'code' => '12.05',
                'name' => 'Langkat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
                'code' => '12.06',
                'name' => 'Karo',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
                'code' => '12.07',
                'name' => 'Deli Serdang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
                'code' => '12.08',
                'name' => 'Simalungun',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            31 => 
            array (
                'id' => 32,
                'code' => '12.09',
                'name' => 'Asahan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            32 => 
            array (
                'id' => 33,
                'code' => '12.10',
                'name' => 'Labuhanbatu',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            33 => 
            array (
                'id' => 34,
                'code' => '12.11',
                'name' => 'Dairi',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
                'code' => '12.12',
                'name' => 'Toba Samosir',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            35 => 
            array (
                'id' => 36,
                'code' => '12.13',
                'name' => 'Mandailing Natal',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            36 => 
            array (
                'id' => 37,
                'code' => '12.14',
                'name' => 'Nias Selatan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            37 => 
            array (
                'id' => 38,
                'code' => '12.15',
                'name' => 'Pakpak Bharat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            38 => 
            array (
                'id' => 39,
                'code' => '12.16',
                'name' => 'Humbang Hasundutan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            39 => 
            array (
                'id' => 40,
                'code' => '12.17',
                'name' => 'Samosir',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            40 => 
            array (
                'id' => 41,
                'code' => '12.18',
                'name' => 'Serdang Bedagai',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            41 => 
            array (
                'id' => 42,
                'code' => '12.19',
                'name' => 'Batu Bara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            42 => 
            array (
                'id' => 43,
                'code' => '12.2',
                'name' => 'Padang Lawas Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            43 => 
            array (
                'id' => 44,
                'code' => '12.21',
                'name' => 'Padang Lawas',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            44 => 
            array (
                'id' => 45,
                'code' => '12.22',
                'name' => 'Labuhanbatu Selatan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            45 => 
            array (
                'id' => 46,
                'code' => '12.23',
                'name' => 'Labuhanbatu Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            46 => 
            array (
                'id' => 47,
                'code' => '12.24',
                'name' => 'Nias Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            47 => 
            array (
                'id' => 48,
                'code' => '12.25',
                'name' => 'Nias Barat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            48 => 
            array (
                'id' => 49,
                'code' => '12.71',
                'name' => 'Medan',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            49 => 
            array (
                'id' => 50,
                'code' => '12.72',
                'name' => 'Pematang Siantar',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            50 => 
            array (
                'id' => 51,
                'code' => '12.73',
                'name' => 'Sibolga',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            51 => 
            array (
                'id' => 52,
                'code' => '12.74',
                'name' => 'Tanjung Balai',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            52 => 
            array (
                'id' => 53,
                'code' => '12.75',
                'name' => 'Binjai',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            53 => 
            array (
                'id' => 54,
                'code' => '12.76',
                'name' => 'Tebing Tinggi',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            54 => 
            array (
                'id' => 55,
                'code' => '12.77',
                'name' => 'Padang Sidempuan',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            55 => 
            array (
                'id' => 56,
                'code' => '12.78',
                'name' => 'Gunungsitoli',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            56 => 
            array (
                'id' => 57,
                'code' => '13.01',
                'name' => 'Pesisir Selatan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            57 => 
            array (
                'id' => 58,
                'code' => '13.02',
                'name' => 'Solok',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            58 => 
            array (
                'id' => 59,
                'code' => '13.03',
                'name' => 'Sijunjung',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            59 => 
            array (
                'id' => 60,
                'code' => '13.04',
                'name' => 'Tanah Datar',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            60 => 
            array (
                'id' => 61,
                'code' => '13.05',
                'name' => 'Padang Pariaman',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            61 => 
            array (
                'id' => 62,
                'code' => '13.06',
                'name' => 'Agam',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            62 => 
            array (
                'id' => 63,
                'code' => '13.07',
                'name' => 'Lima Puluh Kota',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            63 => 
            array (
                'id' => 64,
                'code' => '13.08',
                'name' => 'Pasaman',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            64 => 
            array (
                'id' => 65,
                'code' => '13.09',
                'name' => 'Kepulauan Mentawai',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            65 => 
            array (
                'id' => 66,
                'code' => '13.10',
                'name' => 'Dharmasraya',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            66 => 
            array (
                'id' => 67,
                'code' => '13.11',
                'name' => 'Solok Selatan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            67 => 
            array (
                'id' => 68,
                'code' => '13.12',
                'name' => 'Pasaman Barat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            68 => 
            array (
                'id' => 69,
                'code' => '13.71',
                'name' => 'Padang',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            69 => 
            array (
                'id' => 70,
                'code' => '13.72',
                'name' => 'Solok',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            70 => 
            array (
                'id' => 71,
                'code' => '13.73',
                'name' => 'Sawah Lunto',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            71 => 
            array (
                'id' => 72,
                'code' => '13.74',
                'name' => 'Padang Panjang',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            72 => 
            array (
                'id' => 73,
                'code' => '13.75',
                'name' => 'Bukittinggi',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            73 => 
            array (
                'id' => 74,
                'code' => '13.76',
                'name' => 'Payakumbuh',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            74 => 
            array (
                'id' => 75,
                'code' => '13.77',
                'name' => 'Pariaman',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            75 => 
            array (
                'id' => 76,
                'code' => '14.01',
                'name' => 'Kampar',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            76 => 
            array (
                'id' => 77,
                'code' => '14.02',
                'name' => 'Indragiri Hulu',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            77 => 
            array (
                'id' => 78,
                'code' => '14.03',
                'name' => 'Bengkalis',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            78 => 
            array (
                'id' => 79,
                'code' => '14.04',
                'name' => 'Indragiri Hilir',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            79 => 
            array (
                'id' => 80,
                'code' => '14.05',
                'name' => 'Pelalawan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            80 => 
            array (
                'id' => 81,
                'code' => '14.06',
                'name' => 'Rokan Hulu',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            81 => 
            array (
                'id' => 82,
                'code' => '14.07',
                'name' => 'Rokan Hilir',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            82 => 
            array (
                'id' => 83,
                'code' => '14.08',
                'name' => 'Siak',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            83 => 
            array (
                'id' => 84,
                'code' => '14.09',
                'name' => 'Kuantan Singingi',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            84 => 
            array (
                'id' => 85,
                'code' => '14.10',
                'name' => 'Kepulauan Meranti',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            85 => 
            array (
                'id' => 86,
                'code' => '14.71',
                'name' => 'Pekanbaru',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            86 => 
            array (
                'id' => 87,
                'code' => '14.72',
                'name' => 'Dumai',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            87 => 
            array (
                'id' => 88,
                'code' => '15.01',
                'name' => 'Kerinci',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            88 => 
            array (
                'id' => 89,
                'code' => '15.02',
                'name' => 'Merangin',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            89 => 
            array (
                'id' => 90,
                'code' => '15.03',
                'name' => 'Sarolangun',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            90 => 
            array (
                'id' => 91,
                'code' => '15.04',
                'name' => 'Batang Hari',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            91 => 
            array (
                'id' => 92,
                'code' => '15.05',
                'name' => 'Muaro Jambi',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            92 => 
            array (
                'id' => 93,
                'code' => '15.06',
                'name' => 'Tanjung Jabung Barat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            93 => 
            array (
                'id' => 94,
                'code' => '15.07',
                'name' => 'Tanjung Jabung Timur',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            94 => 
            array (
                'id' => 95,
                'code' => '15.08',
                'name' => 'Bungo',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            95 => 
            array (
                'id' => 96,
                'code' => '15.09',
                'name' => 'Tebo',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            96 => 
            array (
                'id' => 97,
                'code' => '15.71',
                'name' => 'Jambi',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            97 => 
            array (
                'id' => 98,
                'code' => '15.72',
                'name' => 'Sungaipenuh',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            98 => 
            array (
                'id' => 99,
                'code' => '16.01',
                'name' => 'Ogan Komering Ulu',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            99 => 
            array (
                'id' => 100,
                'code' => '16.02',
                'name' => 'Ogan Komering Ilir',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            100 => 
            array (
                'id' => 101,
                'code' => '16.03',
                'name' => 'Muara Enim',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            101 => 
            array (
                'id' => 102,
                'code' => '16.04',
                'name' => 'Lahat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            102 => 
            array (
                'id' => 103,
                'code' => '16.05',
                'name' => 'Musi Rawas',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            103 => 
            array (
                'id' => 104,
                'code' => '16.06',
                'name' => 'Musi Banyuasin',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            104 => 
            array (
                'id' => 105,
                'code' => '16.07',
                'name' => 'Banyuasin',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            105 => 
            array (
                'id' => 106,
                'code' => '16.08',
                'name' => 'Ogan Komering Ulu Timur',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            106 => 
            array (
                'id' => 107,
                'code' => '16.09',
                'name' => 'Ogan Komering Ulu Selatan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            107 => 
            array (
                'id' => 108,
                'code' => '16.10',
                'name' => 'Ogan Ilir',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            108 => 
            array (
                'id' => 109,
                'code' => '16.11',
                'name' => 'Empat Lawang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            109 => 
            array (
                'id' => 110,
                'code' => '16.12',
                'name' => 'Penukal Abab Lematang Ilir',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            110 => 
            array (
                'id' => 111,
                'code' => '16.13',
                'name' => 'Musi Rawas Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            111 => 
            array (
                'id' => 112,
                'code' => '16.71',
                'name' => 'Palembang',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            112 => 
            array (
                'id' => 113,
                'code' => '16.72',
                'name' => 'Pagar Alam',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            113 => 
            array (
                'id' => 114,
                'code' => '16.73',
                'name' => 'Lubuk Linggau',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            114 => 
            array (
                'id' => 115,
                'code' => '16.74',
                'name' => 'Prabumulih',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            115 => 
            array (
                'id' => 116,
                'code' => '17.01',
                'name' => 'Bengkulu Selatan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            116 => 
            array (
                'id' => 117,
                'code' => '17.02',
                'name' => 'Rejang Lebong',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            117 => 
            array (
                'id' => 118,
                'code' => '17.03',
                'name' => 'Bengkulu Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            118 => 
            array (
                'id' => 119,
                'code' => '17.04',
                'name' => 'Kaur',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            119 => 
            array (
                'id' => 120,
                'code' => '17.05',
                'name' => 'Seluma',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            120 => 
            array (
                'id' => 121,
                'code' => '17.06',
                'name' => 'Muko Muko',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            121 => 
            array (
                'id' => 122,
                'code' => '17.07',
                'name' => 'Lebong',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            122 => 
            array (
                'id' => 123,
                'code' => '17.08',
                'name' => 'Kepahiang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            123 => 
            array (
                'id' => 124,
                'code' => '17.09',
                'name' => 'Bengkulu Tengah',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            124 => 
            array (
                'id' => 125,
                'code' => '17.71',
                'name' => 'Bengkulu',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            125 => 
            array (
                'id' => 126,
                'code' => '18.01',
                'name' => 'Lampung Selatan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            126 => 
            array (
                'id' => 127,
                'code' => '18.02',
                'name' => 'Lampung Tengah',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            127 => 
            array (
                'id' => 128,
                'code' => '18.03',
                'name' => 'Lampung Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            128 => 
            array (
                'id' => 129,
                'code' => '18.04',
                'name' => 'Lampung Barat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            129 => 
            array (
                'id' => 130,
                'code' => '18.05',
                'name' => 'Tulang Bawang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            130 => 
            array (
                'id' => 131,
                'code' => '18.06',
                'name' => 'Tanggamus',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            131 => 
            array (
                'id' => 132,
                'code' => '18.07',
                'name' => 'Lampung Timur',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            132 => 
            array (
                'id' => 133,
                'code' => '18.08',
                'name' => 'Way Kanan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            133 => 
            array (
                'id' => 134,
                'code' => '18.09',
                'name' => 'Pesawaran',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            134 => 
            array (
                'id' => 135,
                'code' => '18.10',
                'name' => 'Pringsewu',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            135 => 
            array (
                'id' => 136,
                'code' => '18.11',
                'name' => 'Mesuji',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            136 => 
            array (
                'id' => 137,
                'code' => '18.12',
                'name' => 'Tulang Bawang Barat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            137 => 
            array (
                'id' => 138,
                'code' => '18.13',
                'name' => 'Pesisir Barat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            138 => 
            array (
                'id' => 139,
                'code' => '18.71',
                'name' => 'Bandar Lampung',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            139 => 
            array (
                'id' => 140,
                'code' => '18.72',
                'name' => 'Metro',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            140 => 
            array (
                'id' => 141,
                'code' => '19.01',
                'name' => 'Bangka',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            141 => 
            array (
                'id' => 142,
                'code' => '19.02',
                'name' => 'Belitung',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            142 => 
            array (
                'id' => 143,
                'code' => '19.03',
                'name' => 'Bangka Selatan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            143 => 
            array (
                'id' => 144,
                'code' => '19.04',
                'name' => 'Bangka Tengah',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            144 => 
            array (
                'id' => 145,
                'code' => '19.05',
                'name' => 'Bangka Barat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            145 => 
            array (
                'id' => 146,
                'code' => '19.06',
                'name' => 'Belitung Timur',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            146 => 
            array (
                'id' => 147,
                'code' => '19.71',
                'name' => 'Pangkal Pinang',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            147 => 
            array (
                'id' => 148,
                'code' => '21.01',
                'name' => 'Bintan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            148 => 
            array (
                'id' => 149,
                'code' => '21.02',
                'name' => 'Karimun',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            149 => 
            array (
                'id' => 150,
                'code' => '21.03',
                'name' => 'Natuna',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            150 => 
            array (
                'id' => 151,
                'code' => '21.04',
                'name' => 'Lingga',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            151 => 
            array (
                'id' => 152,
                'code' => '21.05',
                'name' => 'Kepulauan Anambas',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            152 => 
            array (
                'id' => 153,
                'code' => '21.71',
                'name' => 'Batam',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            153 => 
            array (
                'id' => 154,
                'code' => '21.72',
                'name' => 'Tanjung Pinang',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            154 => 
            array (
                'id' => 155,
                'code' => '31.01',
                'name' => 'Kepulauan Seribu',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            155 => 
            array (
                'id' => 156,
                'code' => '31.71',
                'name' => 'Jakarta Pusat',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            156 => 
            array (
                'id' => 157,
                'code' => '31.72',
                'name' => 'Jakarta Utara',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            157 => 
            array (
                'id' => 158,
                'code' => '31.73',
                'name' => 'Jakarta Barat',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            158 => 
            array (
                'id' => 159,
                'code' => '31.74',
                'name' => 'Jakarta Selatan',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            159 => 
            array (
                'id' => 160,
                'code' => '31.75',
                'name' => 'Jakarta Timur',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            160 => 
            array (
                'id' => 161,
                'code' => '32.01',
                'name' => 'Bogor',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            161 => 
            array (
                'id' => 162,
                'code' => '32.02',
                'name' => 'Sukabumi',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            162 => 
            array (
                'id' => 163,
                'code' => '32.03',
                'name' => 'Cianjur',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            163 => 
            array (
                'id' => 164,
                'code' => '32.04',
                'name' => 'Bandung',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            164 => 
            array (
                'id' => 165,
                'code' => '32.05',
                'name' => 'Garut',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            165 => 
            array (
                'id' => 166,
                'code' => '32.06',
                'name' => 'Tasikmalaya',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            166 => 
            array (
                'id' => 167,
                'code' => '32.07',
                'name' => 'Ciamis',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            167 => 
            array (
                'id' => 168,
                'code' => '32.08',
                'name' => 'Kuningan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            168 => 
            array (
                'id' => 169,
                'code' => '32.09',
                'name' => 'Cirebon',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            169 => 
            array (
                'id' => 170,
                'code' => '32.10',
                'name' => 'Majalengka',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            170 => 
            array (
                'id' => 171,
                'code' => '32.11',
                'name' => 'Sumedang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            171 => 
            array (
                'id' => 172,
                'code' => '32.12',
                'name' => 'Indramayu',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            172 => 
            array (
                'id' => 173,
                'code' => '32.13',
                'name' => 'Subang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            173 => 
            array (
                'id' => 174,
                'code' => '32.14',
                'name' => 'Purwakarta',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            174 => 
            array (
                'id' => 175,
                'code' => '32.15',
                'name' => 'Karawang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            175 => 
            array (
                'id' => 176,
                'code' => '32.16',
                'name' => 'Bekasi',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            176 => 
            array (
                'id' => 177,
                'code' => '32.17',
                'name' => 'Bandung Barat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            177 => 
            array (
                'id' => 178,
                'code' => '32.18',
                'name' => 'Pangandaran',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            178 => 
            array (
                'id' => 179,
                'code' => '32.71',
                'name' => 'Bogor',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            179 => 
            array (
                'id' => 180,
                'code' => '32.72',
                'name' => 'Sukabumi',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            180 => 
            array (
                'id' => 181,
                'code' => '32.73',
                'name' => 'Bandung',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            181 => 
            array (
                'id' => 182,
                'code' => '32.74',
                'name' => 'Cirebon',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            182 => 
            array (
                'id' => 183,
                'code' => '32.75',
                'name' => 'Bekasi',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            183 => 
            array (
                'id' => 184,
                'code' => '32.76',
                'name' => 'Depok',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            184 => 
            array (
                'id' => 185,
                'code' => '32.77',
                'name' => 'Cimahi',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            185 => 
            array (
                'id' => 186,
                'code' => '32.78',
                'name' => 'Tasikmalaya',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            186 => 
            array (
                'id' => 187,
                'code' => '32.79',
                'name' => 'Banjar',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            187 => 
            array (
                'id' => 188,
                'code' => '33.01',
                'name' => 'Cilacap',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            188 => 
            array (
                'id' => 189,
                'code' => '33.02',
                'name' => 'Banyumas',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            189 => 
            array (
                'id' => 190,
                'code' => '33.03',
                'name' => 'Purbalingga',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            190 => 
            array (
                'id' => 191,
                'code' => '33.04',
                'name' => 'Banjarnegara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            191 => 
            array (
                'id' => 192,
                'code' => '33.05',
                'name' => 'Kebumen',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            192 => 
            array (
                'id' => 193,
                'code' => '33.06',
                'name' => 'Purworejo',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            193 => 
            array (
                'id' => 194,
                'code' => '33.07',
                'name' => 'Wonosobo',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            194 => 
            array (
                'id' => 195,
                'code' => '33.08',
                'name' => 'Magelang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            195 => 
            array (
                'id' => 196,
                'code' => '33.09',
                'name' => 'Boyolali',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            196 => 
            array (
                'id' => 197,
                'code' => '33.10',
                'name' => 'Klaten',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            197 => 
            array (
                'id' => 198,
                'code' => '33.11',
                'name' => 'Sukoharjo',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            198 => 
            array (
                'id' => 199,
                'code' => '33.12',
                'name' => 'Wonogiri',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            199 => 
            array (
                'id' => 200,
                'code' => '33.13',
                'name' => 'Karanganyar',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            200 => 
            array (
                'id' => 201,
                'code' => '33.14',
                'name' => 'Sragen',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            201 => 
            array (
                'id' => 202,
                'code' => '33.15',
                'name' => 'Grobogan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            202 => 
            array (
                'id' => 203,
                'code' => '33.16',
                'name' => 'Blora',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            203 => 
            array (
                'id' => 204,
                'code' => '33.17',
                'name' => 'Rembang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            204 => 
            array (
                'id' => 205,
                'code' => '33.18',
                'name' => 'Pati',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            205 => 
            array (
                'id' => 206,
                'code' => '33.19',
                'name' => 'Kudus',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            206 => 
            array (
                'id' => 207,
                'code' => '33.2',
                'name' => 'Jepara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            207 => 
            array (
                'id' => 208,
                'code' => '33.21',
                'name' => 'Demak',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            208 => 
            array (
                'id' => 209,
                'code' => '33.22',
                'name' => 'Semarang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            209 => 
            array (
                'id' => 210,
                'code' => '33.23',
                'name' => 'Temanggung',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            210 => 
            array (
                'id' => 211,
                'code' => '33.24',
                'name' => 'Kendal',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            211 => 
            array (
                'id' => 212,
                'code' => '33.25',
                'name' => 'Batang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            212 => 
            array (
                'id' => 213,
                'code' => '33.26',
                'name' => 'Pekalongan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            213 => 
            array (
                'id' => 214,
                'code' => '33.27',
                'name' => 'Pemalang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            214 => 
            array (
                'id' => 215,
                'code' => '33.28',
                'name' => 'Tegal',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            215 => 
            array (
                'id' => 216,
                'code' => '33.29',
                'name' => 'Brebes',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            216 => 
            array (
                'id' => 217,
                'code' => '33.71',
                'name' => 'Magelang',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            217 => 
            array (
                'id' => 218,
                'code' => '33.72',
                'name' => 'Surakarta',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            218 => 
            array (
                'id' => 219,
                'code' => '33.73',
                'name' => 'Salatiga',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            219 => 
            array (
                'id' => 220,
                'code' => '33.74',
                'name' => 'Semarang',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            220 => 
            array (
                'id' => 221,
                'code' => '33.75',
                'name' => 'Pekalongan',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            221 => 
            array (
                'id' => 222,
                'code' => '33.76',
                'name' => 'Tegal',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            222 => 
            array (
                'id' => 223,
                'code' => '34.01',
                'name' => 'Kulon Progo',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            223 => 
            array (
                'id' => 224,
                'code' => '34.02',
                'name' => 'Bantul',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            224 => 
            array (
                'id' => 225,
                'code' => '34.03',
                'name' => 'Gunung Kidul',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            225 => 
            array (
                'id' => 226,
                'code' => '34.04',
                'name' => 'Sleman',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            226 => 
            array (
                'id' => 227,
                'code' => '34.71',
                'name' => 'Yogyakarta',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            227 => 
            array (
                'id' => 228,
                'code' => '35.01',
                'name' => 'Pacitan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            228 => 
            array (
                'id' => 229,
                'code' => '35.02',
                'name' => 'Ponorogo',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            229 => 
            array (
                'id' => 230,
                'code' => '35.03',
                'name' => 'Trenggalek',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            230 => 
            array (
                'id' => 231,
                'code' => '35.04',
                'name' => 'Tulungagung',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            231 => 
            array (
                'id' => 232,
                'code' => '35.05',
                'name' => 'Blitar',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            232 => 
            array (
                'id' => 233,
                'code' => '35.06',
                'name' => 'Kediri',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            233 => 
            array (
                'id' => 234,
                'code' => '35.07',
                'name' => 'Malang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            234 => 
            array (
                'id' => 235,
                'code' => '35.08',
                'name' => 'Lumajang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            235 => 
            array (
                'id' => 236,
                'code' => '35.09',
                'name' => 'Jember',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            236 => 
            array (
                'id' => 237,
                'code' => '35.10',
                'name' => 'Banyuwangi',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            237 => 
            array (
                'id' => 238,
                'code' => '35.11',
                'name' => 'Bondowoso',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            238 => 
            array (
                'id' => 239,
                'code' => '35.12',
                'name' => 'Situbondo',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            239 => 
            array (
                'id' => 240,
                'code' => '35.13',
                'name' => 'Probolinggo',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            240 => 
            array (
                'id' => 241,
                'code' => '35.14',
                'name' => 'Pasuruan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            241 => 
            array (
                'id' => 242,
                'code' => '35.15',
                'name' => 'Sidoarjo',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            242 => 
            array (
                'id' => 243,
                'code' => '35.16',
                'name' => 'Mojokerto',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            243 => 
            array (
                'id' => 244,
                'code' => '35.17',
                'name' => 'Jombang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            244 => 
            array (
                'id' => 245,
                'code' => '35.18',
                'name' => 'Nganjuk',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            245 => 
            array (
                'id' => 246,
                'code' => '35.19',
                'name' => 'Madiun',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            246 => 
            array (
                'id' => 247,
                'code' => '35.2',
                'name' => 'Magetan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            247 => 
            array (
                'id' => 248,
                'code' => '35.21',
                'name' => 'Ngawi',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            248 => 
            array (
                'id' => 249,
                'code' => '35.22',
                'name' => 'Bojonegoro',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            249 => 
            array (
                'id' => 250,
                'code' => '35.23',
                'name' => 'Tuban',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            250 => 
            array (
                'id' => 251,
                'code' => '35.24',
                'name' => 'Lamongan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            251 => 
            array (
                'id' => 252,
                'code' => '35.25',
                'name' => 'Gresik',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            252 => 
            array (
                'id' => 253,
                'code' => '35.26',
                'name' => 'Bangkalan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            253 => 
            array (
                'id' => 254,
                'code' => '35.27',
                'name' => 'Sampang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            254 => 
            array (
                'id' => 255,
                'code' => '35.28',
                'name' => 'Pamekasan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            255 => 
            array (
                'id' => 256,
                'code' => '35.29',
                'name' => 'Sumenep',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            256 => 
            array (
                'id' => 257,
                'code' => '35.71',
                'name' => 'Kediri',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            257 => 
            array (
                'id' => 258,
                'code' => '35.72',
                'name' => 'Blitar',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            258 => 
            array (
                'id' => 259,
                'code' => '35.73',
                'name' => 'Malang',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            259 => 
            array (
                'id' => 260,
                'code' => '35.74',
                'name' => 'Probolinggo',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            260 => 
            array (
                'id' => 261,
                'code' => '35.75',
                'name' => 'Pasuruan',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            261 => 
            array (
                'id' => 262,
                'code' => '35.76',
                'name' => 'Mojokerto',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            262 => 
            array (
                'id' => 263,
                'code' => '35.77',
                'name' => 'Madiun',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            263 => 
            array (
                'id' => 264,
                'code' => '35.78',
                'name' => 'Surabaya',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            264 => 
            array (
                'id' => 265,
                'code' => '35.79',
                'name' => 'Batu',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            265 => 
            array (
                'id' => 266,
                'code' => '36.01',
                'name' => 'Pandeglang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            266 => 
            array (
                'id' => 267,
                'code' => '36.02',
                'name' => 'Lebak',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            267 => 
            array (
                'id' => 268,
                'code' => '36.03',
                'name' => 'Tangerang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            268 => 
            array (
                'id' => 269,
                'code' => '36.04',
                'name' => 'Serang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            269 => 
            array (
                'id' => 270,
                'code' => '36.71',
                'name' => 'Tangerang',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            270 => 
            array (
                'id' => 271,
                'code' => '36.72',
                'name' => 'Cilegon',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            271 => 
            array (
                'id' => 272,
                'code' => '36.73',
                'name' => 'Serang',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            272 => 
            array (
                'id' => 273,
                'code' => '36.74',
                'name' => 'Tangerang Selatan',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            273 => 
            array (
                'id' => 274,
                'code' => '51.01',
                'name' => 'Jembrana',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            274 => 
            array (
                'id' => 275,
                'code' => '51.02',
                'name' => 'Tabanan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            275 => 
            array (
                'id' => 276,
                'code' => '51.03',
                'name' => 'Badung',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            276 => 
            array (
                'id' => 277,
                'code' => '51.04',
                'name' => 'Gianyar',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            277 => 
            array (
                'id' => 278,
                'code' => '51.05',
                'name' => 'Klungkung',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            278 => 
            array (
                'id' => 279,
                'code' => '51.06',
                'name' => 'Bangli',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            279 => 
            array (
                'id' => 280,
                'code' => '51.07',
                'name' => 'Karangasem',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            280 => 
            array (
                'id' => 281,
                'code' => '51.08',
                'name' => 'Buleleng',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            281 => 
            array (
                'id' => 282,
                'code' => '51.71',
                'name' => 'Denpasar',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            282 => 
            array (
                'id' => 283,
                'code' => '52.01',
                'name' => 'Lombok Barat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            283 => 
            array (
                'id' => 284,
                'code' => '52.02',
                'name' => 'Lombok Tengah',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            284 => 
            array (
                'id' => 285,
                'code' => '52.03',
                'name' => 'Lombok Timur',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            285 => 
            array (
                'id' => 286,
                'code' => '52.04',
                'name' => 'Sumbawa',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            286 => 
            array (
                'id' => 287,
                'code' => '52.05',
                'name' => 'Dompu',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            287 => 
            array (
                'id' => 288,
                'code' => '52.06',
                'name' => 'Bima',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            288 => 
            array (
                'id' => 289,
                'code' => '52.07',
                'name' => 'Sumbawa Barat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            289 => 
            array (
                'id' => 290,
                'code' => '52.08',
                'name' => 'Lombok Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            290 => 
            array (
                'id' => 291,
                'code' => '52.71',
                'name' => 'Mataram',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            291 => 
            array (
                'id' => 292,
                'code' => '52.72',
                'name' => 'Bima',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            292 => 
            array (
                'id' => 293,
                'code' => '53.01',
                'name' => 'Kupang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            293 => 
            array (
                'id' => 294,
                'code' => '53.02',
                'name' => 'Timor Tengah Selatan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            294 => 
            array (
                'id' => 295,
                'code' => '53.03',
                'name' => 'Timor Tengah Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            295 => 
            array (
                'id' => 296,
                'code' => '53.04',
                'name' => 'Belu',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            296 => 
            array (
                'id' => 297,
                'code' => '53.05',
                'name' => 'Alor',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            297 => 
            array (
                'id' => 298,
                'code' => '53.06',
                'name' => 'Flores Timur',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            298 => 
            array (
                'id' => 299,
                'code' => '53.07',
                'name' => 'Sikka',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            299 => 
            array (
                'id' => 300,
                'code' => '53.08',
                'name' => 'Ende',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            300 => 
            array (
                'id' => 301,
                'code' => '53.09',
                'name' => 'Ngada',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            301 => 
            array (
                'id' => 302,
                'code' => '53.10',
                'name' => 'Manggarai',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            302 => 
            array (
                'id' => 303,
                'code' => '53.11',
                'name' => 'Sumba Timur',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            303 => 
            array (
                'id' => 304,
                'code' => '53.12',
                'name' => 'Sumba Barat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            304 => 
            array (
                'id' => 305,
                'code' => '53.13',
                'name' => 'Lembata',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            305 => 
            array (
                'id' => 306,
                'code' => '53.14',
                'name' => 'Rote Ndao',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            306 => 
            array (
                'id' => 307,
                'code' => '53.15',
                'name' => 'Manggarai Barat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            307 => 
            array (
                'id' => 308,
                'code' => '53.16',
                'name' => 'Nagekeo',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            308 => 
            array (
                'id' => 309,
                'code' => '53.17',
                'name' => 'Sumba Tengah',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            309 => 
            array (
                'id' => 310,
                'code' => '53.18',
                'name' => 'Sumba Barat Daya',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            310 => 
            array (
                'id' => 311,
                'code' => '53.19',
                'name' => 'Manggarai Timur',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            311 => 
            array (
                'id' => 312,
                'code' => '53.2',
                'name' => 'Sabu Raijua',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            312 => 
            array (
                'id' => 313,
                'code' => '53.21',
                'name' => 'Malaka',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            313 => 
            array (
                'id' => 314,
                'code' => '53.71',
                'name' => 'Kupang',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            314 => 
            array (
                'id' => 315,
                'code' => '61.01',
                'name' => 'Sambas',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            315 => 
            array (
                'id' => 316,
                'code' => '61.02',
                'name' => 'Mempawah',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            316 => 
            array (
                'id' => 317,
                'code' => '61.03',
                'name' => 'Sanggau',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            317 => 
            array (
                'id' => 318,
                'code' => '61.04',
                'name' => 'Ketapang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            318 => 
            array (
                'id' => 319,
                'code' => '61.05',
                'name' => 'Sintang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            319 => 
            array (
                'id' => 320,
                'code' => '61.06',
                'name' => 'Kapuas Hulu',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            320 => 
            array (
                'id' => 321,
                'code' => '61.07',
                'name' => 'Bengkayang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            321 => 
            array (
                'id' => 322,
                'code' => '61.08',
                'name' => 'Landak',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            322 => 
            array (
                'id' => 323,
                'code' => '61.09',
                'name' => 'Sekadau',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            323 => 
            array (
                'id' => 324,
                'code' => '61.10',
                'name' => 'Melawi',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            324 => 
            array (
                'id' => 325,
                'code' => '61.11',
                'name' => 'Kayong Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            325 => 
            array (
                'id' => 326,
                'code' => '61.12',
                'name' => 'Kubu Raya',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            326 => 
            array (
                'id' => 327,
                'code' => '61.71',
                'name' => 'Pontianak',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            327 => 
            array (
                'id' => 328,
                'code' => '61.72',
                'name' => 'Singkawang',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            328 => 
            array (
                'id' => 329,
                'code' => '62.01',
                'name' => 'Kotawaringin Barat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            329 => 
            array (
                'id' => 330,
                'code' => '62.02',
                'name' => 'Kotawaringin Timur',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            330 => 
            array (
                'id' => 331,
                'code' => '62.03',
                'name' => 'Kapuas',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            331 => 
            array (
                'id' => 332,
                'code' => '62.04',
                'name' => 'Barito Selatan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            332 => 
            array (
                'id' => 333,
                'code' => '62.05',
                'name' => 'Barito Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            333 => 
            array (
                'id' => 334,
                'code' => '62.06',
                'name' => 'Katingan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            334 => 
            array (
                'id' => 335,
                'code' => '62.07',
                'name' => 'Seruyan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            335 => 
            array (
                'id' => 336,
                'code' => '62.08',
                'name' => 'Sukamara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            336 => 
            array (
                'id' => 337,
                'code' => '62.09',
                'name' => 'Lamandau',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            337 => 
            array (
                'id' => 338,
                'code' => '62.10',
                'name' => 'Gunung Mas',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            338 => 
            array (
                'id' => 339,
                'code' => '62.11',
                'name' => 'Pulang Pisau',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            339 => 
            array (
                'id' => 340,
                'code' => '62.12',
                'name' => 'Murung Raya',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            340 => 
            array (
                'id' => 341,
                'code' => '62.13',
                'name' => 'Barito Timur',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            341 => 
            array (
                'id' => 342,
                'code' => '62.71',
                'name' => 'Palangka Raya',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            342 => 
            array (
                'id' => 343,
                'code' => '63.01',
                'name' => 'Tanah Laut',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            343 => 
            array (
                'id' => 344,
                'code' => '63.02',
                'name' => 'Kotabaru',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            344 => 
            array (
                'id' => 345,
                'code' => '63.03',
                'name' => 'Banjar',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            345 => 
            array (
                'id' => 346,
                'code' => '63.04',
                'name' => 'Barito Kuala',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            346 => 
            array (
                'id' => 347,
                'code' => '63.05',
                'name' => 'Tapin',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            347 => 
            array (
                'id' => 348,
                'code' => '63.06',
                'name' => 'Hulu Sungai Selatan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            348 => 
            array (
                'id' => 349,
                'code' => '63.07',
                'name' => 'Hulu Sungai Tengah',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            349 => 
            array (
                'id' => 350,
                'code' => '63.08',
                'name' => 'Hulu Sungai Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            350 => 
            array (
                'id' => 351,
                'code' => '63.09',
                'name' => 'Tabalong',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            351 => 
            array (
                'id' => 352,
                'code' => '63.10',
                'name' => 'Tanah Bumbu',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            352 => 
            array (
                'id' => 353,
                'code' => '63.11',
                'name' => 'Balangan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            353 => 
            array (
                'id' => 354,
                'code' => '63.71',
                'name' => 'Banjarmasin',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            354 => 
            array (
                'id' => 355,
                'code' => '63.72',
                'name' => 'Banjarbaru',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            355 => 
            array (
                'id' => 356,
                'code' => '64.01',
                'name' => 'Paser',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            356 => 
            array (
                'id' => 357,
                'code' => '64.02',
                'name' => 'Kutai Kartanegara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            357 => 
            array (
                'id' => 358,
                'code' => '64.03',
                'name' => 'Berau',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            358 => 
            array (
                'id' => 359,
                'code' => '64.07',
                'name' => 'Kutai Barat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            359 => 
            array (
                'id' => 360,
                'code' => '64.08',
                'name' => 'Kutai Timur',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            360 => 
            array (
                'id' => 361,
                'code' => '64.09',
                'name' => 'Penajam Paser Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            361 => 
            array (
                'id' => 362,
                'code' => '64.11',
                'name' => 'Mahakam Ulu',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            362 => 
            array (
                'id' => 363,
                'code' => '64.71',
                'name' => 'Balikpapan',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            363 => 
            array (
                'id' => 364,
                'code' => '64.72',
                'name' => 'Samarinda',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            364 => 
            array (
                'id' => 365,
                'code' => '64.74',
                'name' => 'Bontang',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            365 => 
            array (
                'id' => 366,
                'code' => '65.01',
                'name' => 'Bulungan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            366 => 
            array (
                'id' => 367,
                'code' => '65.02',
                'name' => 'Malinau',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            367 => 
            array (
                'id' => 368,
                'code' => '65.03',
                'name' => 'Nunukan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            368 => 
            array (
                'id' => 369,
                'code' => '65.04',
                'name' => 'Tana Tidung',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            369 => 
            array (
                'id' => 370,
                'code' => '65.71',
                'name' => 'Tarakan',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            370 => 
            array (
                'id' => 371,
                'code' => '71.01',
                'name' => 'Bolaang Mongondow',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            371 => 
            array (
                'id' => 372,
                'code' => '71.02',
                'name' => 'Minahasa',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            372 => 
            array (
                'id' => 373,
                'code' => '71.03',
                'name' => 'Kepulauan Sangihe',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            373 => 
            array (
                'id' => 374,
                'code' => '71.04',
                'name' => 'Kepulauan Talaud',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            374 => 
            array (
                'id' => 375,
                'code' => '71.05',
                'name' => 'Minahasa Selatan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            375 => 
            array (
                'id' => 376,
                'code' => '71.06',
                'name' => 'Minahasa Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            376 => 
            array (
                'id' => 377,
                'code' => '71.07',
                'name' => 'Minahasa Tenggara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            377 => 
            array (
                'id' => 378,
                'code' => '71.08',
                'name' => 'Bolaang Mongondow Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            378 => 
            array (
                'id' => 379,
                'code' => '71.09',
            'name' => 'Kepulauan Siau Tagulandang Biaro (Sitaro)',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            379 => 
            array (
                'id' => 380,
                'code' => '71.10',
                'name' => 'Bolaang Mongondow Timur',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            380 => 
            array (
                'id' => 381,
                'code' => '71.11',
                'name' => 'Bolaang Mongondow Selatan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            381 => 
            array (
                'id' => 382,
                'code' => '71.71',
                'name' => 'Manado',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            382 => 
            array (
                'id' => 383,
                'code' => '71.72',
                'name' => 'Bitung',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            383 => 
            array (
                'id' => 384,
                'code' => '71.73',
                'name' => 'Tomohon',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            384 => 
            array (
                'id' => 385,
                'code' => '71.74',
                'name' => 'Kotamobagu',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            385 => 
            array (
                'id' => 386,
                'code' => '72.01',
                'name' => 'Banggai',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            386 => 
            array (
                'id' => 387,
                'code' => '72.02',
                'name' => 'Poso',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            387 => 
            array (
                'id' => 388,
                'code' => '72.03',
                'name' => 'Donggala',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            388 => 
            array (
                'id' => 389,
                'code' => '72.04',
                'name' => 'Toli-Toli',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            389 => 
            array (
                'id' => 390,
                'code' => '72.05',
                'name' => 'Buol',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            390 => 
            array (
                'id' => 391,
                'code' => '72.06',
                'name' => 'Morowali',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            391 => 
            array (
                'id' => 392,
                'code' => '72.07',
                'name' => 'Banggai Kepulauan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            392 => 
            array (
                'id' => 393,
                'code' => '72.08',
                'name' => 'Parigi Moutong',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            393 => 
            array (
                'id' => 394,
                'code' => '72.09',
                'name' => 'Tojo Una-Una',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            394 => 
            array (
                'id' => 395,
                'code' => '72.10',
                'name' => 'Sigi',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            395 => 
            array (
                'id' => 396,
                'code' => '72.11',
                'name' => 'Banggai Laut',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            396 => 
            array (
                'id' => 397,
                'code' => '72.12',
                'name' => 'Morowali Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            397 => 
            array (
                'id' => 398,
                'code' => '72.71',
                'name' => 'Palu',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            398 => 
            array (
                'id' => 399,
                'code' => '73.01',
                'name' => 'Kepulauan Selayar',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            399 => 
            array (
                'id' => 400,
                'code' => '73.02',
                'name' => 'Bulukumba',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            400 => 
            array (
                'id' => 401,
                'code' => '73.03',
                'name' => 'Bantaeng',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            401 => 
            array (
                'id' => 402,
                'code' => '73.04',
                'name' => 'Jeneponto',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            402 => 
            array (
                'id' => 403,
                'code' => '73.05',
                'name' => 'Takalar',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            403 => 
            array (
                'id' => 404,
                'code' => '73.06',
                'name' => 'Gowa',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            404 => 
            array (
                'id' => 405,
                'code' => '73.07',
                'name' => 'Sinjai',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            405 => 
            array (
                'id' => 406,
                'code' => '73.08',
                'name' => 'Bone',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            406 => 
            array (
                'id' => 407,
                'code' => '73.09',
                'name' => 'Maros',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            407 => 
            array (
                'id' => 408,
                'code' => '73.10',
                'name' => 'Pangkajene Kepulauan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            408 => 
            array (
                'id' => 409,
                'code' => '73.11',
                'name' => 'Barru',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            409 => 
            array (
                'id' => 410,
                'code' => '73.12',
                'name' => 'Soppeng',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            410 => 
            array (
                'id' => 411,
                'code' => '73.13',
                'name' => 'Wajo',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            411 => 
            array (
                'id' => 412,
                'code' => '73.14',
                'name' => 'Sidenreng Rappang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            412 => 
            array (
                'id' => 413,
                'code' => '73.15',
                'name' => 'Pinrang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            413 => 
            array (
                'id' => 414,
                'code' => '73.16',
                'name' => 'Enrekang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            414 => 
            array (
                'id' => 415,
                'code' => '73.17',
                'name' => 'Luwu',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            415 => 
            array (
                'id' => 416,
                'code' => '73.18',
                'name' => 'Tana Toraja',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            416 => 
            array (
                'id' => 417,
                'code' => '73.22',
                'name' => 'Luwu Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            417 => 
            array (
                'id' => 418,
                'code' => '73.24',
                'name' => 'Luwu Timur',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            418 => 
            array (
                'id' => 419,
                'code' => '73.26',
                'name' => 'Toraja Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            419 => 
            array (
                'id' => 420,
                'code' => '73.71',
                'name' => 'Makassar',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            420 => 
            array (
                'id' => 421,
                'code' => '73.72',
                'name' => 'Parepare',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            421 => 
            array (
                'id' => 422,
                'code' => '73.73',
                'name' => 'Palopo',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            422 => 
            array (
                'id' => 423,
                'code' => '74.01',
                'name' => 'Kolaka',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            423 => 
            array (
                'id' => 424,
                'code' => '74.02',
                'name' => 'Konawe',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            424 => 
            array (
                'id' => 425,
                'code' => '74.03',
                'name' => 'Muna',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            425 => 
            array (
                'id' => 426,
                'code' => '74.04',
                'name' => 'Buton',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            426 => 
            array (
                'id' => 427,
                'code' => '74.05',
                'name' => 'Konawe Selatan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            427 => 
            array (
                'id' => 428,
                'code' => '74.06',
                'name' => 'Bombana',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            428 => 
            array (
                'id' => 429,
                'code' => '74.07',
                'name' => 'Wakatobi',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            429 => 
            array (
                'id' => 430,
                'code' => '74.08',
                'name' => 'Kolaka Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            430 => 
            array (
                'id' => 431,
                'code' => '74.09',
                'name' => 'Konawe Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            431 => 
            array (
                'id' => 432,
                'code' => '74.10',
                'name' => 'Buton Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            432 => 
            array (
                'id' => 433,
                'code' => '74.11',
                'name' => 'Kolaka Timur',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            433 => 
            array (
                'id' => 434,
                'code' => '74.12',
                'name' => 'Konawe Kepulauan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            434 => 
            array (
                'id' => 435,
                'code' => '74.13',
                'name' => 'Muna Barat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            435 => 
            array (
                'id' => 436,
                'code' => '74.14',
                'name' => 'Buton Tengah',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            436 => 
            array (
                'id' => 437,
                'code' => '74.15',
                'name' => 'Buton Selatan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            437 => 
            array (
                'id' => 438,
                'code' => '74.71',
                'name' => 'Kendari',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            438 => 
            array (
                'id' => 439,
                'code' => '74.72',
                'name' => 'Bau-Bau',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            439 => 
            array (
                'id' => 440,
                'code' => '75.01',
                'name' => 'Gorontalo',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            440 => 
            array (
                'id' => 441,
                'code' => '75.02',
                'name' => 'Boalemo',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            441 => 
            array (
                'id' => 442,
                'code' => '75.03',
                'name' => 'Bone Bolango',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            442 => 
            array (
                'id' => 443,
                'code' => '75.04',
                'name' => 'Pohuwato',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            443 => 
            array (
                'id' => 444,
                'code' => '75.05',
                'name' => 'Gorontalo Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            444 => 
            array (
                'id' => 445,
                'code' => '75.71',
                'name' => 'Gorontalo',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            445 => 
            array (
                'id' => 446,
                'code' => '76.01',
                'name' => 'Mamuju Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            446 => 
            array (
                'id' => 447,
                'code' => '76.02',
                'name' => 'Mamuju',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            447 => 
            array (
                'id' => 448,
                'code' => '76.03',
                'name' => 'Mamasa',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            448 => 
            array (
                'id' => 449,
                'code' => '76.04',
                'name' => 'Polewali Mandar',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            449 => 
            array (
                'id' => 450,
                'code' => '76.05',
                'name' => 'Majene',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            450 => 
            array (
                'id' => 451,
                'code' => '76.06',
                'name' => 'Mamuju Tengah',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            451 => 
            array (
                'id' => 452,
                'code' => '81.01',
                'name' => 'Maluku Tengah',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            452 => 
            array (
                'id' => 453,
                'code' => '81.02',
                'name' => 'Maluku Tenggara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            453 => 
            array (
                'id' => 454,
                'code' => '81.03',
                'name' => 'Maluku Tenggara Barat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            454 => 
            array (
                'id' => 455,
                'code' => '81.04',
                'name' => 'Buru',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            455 => 
            array (
                'id' => 456,
                'code' => '81.05',
                'name' => 'Seram Bagian Timur',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            456 => 
            array (
                'id' => 457,
                'code' => '81.06',
                'name' => 'Seram Bagian Barat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            457 => 
            array (
                'id' => 458,
                'code' => '81.07',
                'name' => 'Kepulauan Aru',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            458 => 
            array (
                'id' => 459,
                'code' => '81.08',
                'name' => 'Maluku Barat Daya',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            459 => 
            array (
                'id' => 460,
                'code' => '81.09',
                'name' => 'Buru Selatan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            460 => 
            array (
                'id' => 461,
                'code' => '81.71',
                'name' => 'Ambon',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            461 => 
            array (
                'id' => 462,
                'code' => '81.72',
                'name' => 'Tual',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            462 => 
            array (
                'id' => 463,
                'code' => '82.01',
                'name' => 'Halmahera Barat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            463 => 
            array (
                'id' => 464,
                'code' => '82.02',
                'name' => 'Halmahera Tengah',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            464 => 
            array (
                'id' => 465,
                'code' => '82.03',
                'name' => 'Halmahera Utara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            465 => 
            array (
                'id' => 466,
                'code' => '82.04',
                'name' => 'Halmahera Selatan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            466 => 
            array (
                'id' => 467,
                'code' => '82.05',
                'name' => 'Kepulauan Sula',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            467 => 
            array (
                'id' => 468,
                'code' => '82.06',
                'name' => 'Halmahera Timur',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            468 => 
            array (
                'id' => 469,
                'code' => '82.07',
                'name' => 'Pulau Morotai',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            469 => 
            array (
                'id' => 470,
                'code' => '82.08',
                'name' => 'Pulau Taliabu',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            470 => 
            array (
                'id' => 471,
                'code' => '82.71',
                'name' => 'Ternate',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            471 => 
            array (
                'id' => 472,
                'code' => '82.72',
                'name' => 'Tidore Kepulauan',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            472 => 
            array (
                'id' => 473,
                'code' => '91.01',
                'name' => 'Merauke',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            473 => 
            array (
                'id' => 474,
                'code' => '91.02',
                'name' => 'Jayawijaya',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            474 => 
            array (
                'id' => 475,
                'code' => '91.03',
                'name' => 'Jayapura',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            475 => 
            array (
                'id' => 476,
                'code' => '91.04',
                'name' => 'Nabire',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            476 => 
            array (
                'id' => 477,
                'code' => '91.05',
                'name' => 'Kepulauan Yapen',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            477 => 
            array (
                'id' => 478,
                'code' => '91.06',
                'name' => 'Biak Numfor',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            478 => 
            array (
                'id' => 479,
                'code' => '91.07',
                'name' => 'Puncak Jaya',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            479 => 
            array (
                'id' => 480,
                'code' => '91.08',
                'name' => 'Paniai',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            480 => 
            array (
                'id' => 481,
                'code' => '91.09',
                'name' => 'Mimika',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            481 => 
            array (
                'id' => 482,
                'code' => '91.10',
                'name' => 'Sarmi',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            482 => 
            array (
                'id' => 483,
                'code' => '91.11',
                'name' => 'Keerom',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            483 => 
            array (
                'id' => 484,
                'code' => '91.12',
                'name' => 'Pegunungan Bintang',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            484 => 
            array (
                'id' => 485,
                'code' => '91.13',
                'name' => 'Yahukimo',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            485 => 
            array (
                'id' => 486,
                'code' => '91.14',
                'name' => 'Tolikara',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            486 => 
            array (
                'id' => 487,
                'code' => '91.15',
                'name' => 'Waropen',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            487 => 
            array (
                'id' => 488,
                'code' => '91.16',
                'name' => 'Boven Digoel',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            488 => 
            array (
                'id' => 489,
                'code' => '91.17',
                'name' => 'Mappi',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            489 => 
            array (
                'id' => 490,
                'code' => '91.18',
                'name' => 'Asmat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            490 => 
            array (
                'id' => 491,
                'code' => '91.19',
                'name' => 'Supiori',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            491 => 
            array (
                'id' => 492,
                'code' => '91.2',
                'name' => 'Mamberamo Raya',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            492 => 
            array (
                'id' => 493,
                'code' => '91.21',
                'name' => 'Mamberamo Tengah',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            493 => 
            array (
                'id' => 494,
                'code' => '91.22',
                'name' => 'Yalimo',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            494 => 
            array (
                'id' => 495,
                'code' => '91.23',
                'name' => 'Lanny Jaya',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            495 => 
            array (
                'id' => 496,
                'code' => '91.24',
                'name' => 'Nduga',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            496 => 
            array (
                'id' => 497,
                'code' => '91.25',
                'name' => 'Puncak',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            497 => 
            array (
                'id' => 498,
                'code' => '91.26',
                'name' => 'Dogiyai',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            498 => 
            array (
                'id' => 499,
                'code' => '91.27',
                'name' => 'Intan Jaya',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            499 => 
            array (
                'id' => 500,
                'code' => '91.28',
                'name' => 'Deiyai',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        \DB::table('dt_kotakab')->insert(array (
            0 => 
            array (
                'id' => 501,
                'code' => '91.71',
                'name' => 'Jayapura',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 502,
                'code' => '92.01',
                'name' => 'Sorong',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 503,
                'code' => '92.02',
                'name' => 'Manokwari',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 504,
                'code' => '92.03',
                'name' => 'Fakfak',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 505,
                'code' => '92.04',
                'name' => 'Sorong Selatan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 506,
                'code' => '92.05',
                'name' => 'Raja Ampat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 507,
                'code' => '92.06',
                'name' => 'Teluk Bintuni',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 508,
                'code' => '92.07',
                'name' => 'Teluk Wondama',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 509,
                'code' => '92.08',
                'name' => 'Kaimana',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 510,
                'code' => '92.09',
                'name' => 'Tambrauw',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 511,
                'code' => '92.10',
                'name' => 'Maybrat',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 512,
                'code' => '92.11',
                'name' => 'Manokwari Selatan',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 513,
                'code' => '92.12',
                'name' => 'Pegunungan Arfak',
                'type' => 'Kabupaten',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 514,
                'code' => '92.71',
                'name' => 'Sorong',
                'type' => 'Kota',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}