<?php

use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('languages')->delete();
        
        \DB::table('languages')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'English',
                'code' => 'en',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-17 15:01:31',
                'updated_at' => '2019-09-17 15:01:31',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'German',
                'code' => 'de',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-17 15:01:31',
                'updated_at' => '2019-09-17 15:01:31',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'French',
                'code' => 'fr',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-17 15:01:31',
                'updated_at' => '2019-09-17 15:01:31',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Spanish',
                'code' => 'es',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-17 15:01:31',
                'updated_at' => '2019-09-17 15:01:31',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Portuguese - Brazil',
                'code' => 'br',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-17 15:01:31',
                'updated_at' => '2019-09-17 15:01:31',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Portuguese - Portugal ',
                'code' => 'pt',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-17 15:01:31',
                'updated_at' => '2019-09-17 15:01:31',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Italian',
                'code' => 'it',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-17 15:01:31',
                'updated_at' => '2019-09-17 15:01:31',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Dutch',
                'code' => 'nl',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-17 15:01:31',
                'updated_at' => '2019-09-17 15:01:31',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Polish',
                'code' => 'pl',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-17 15:01:31',
                'updated_at' => '2019-09-17 15:01:31',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Russian',
                'code' => 'ru',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-17 15:01:31',
                'updated_at' => '2019-09-17 15:01:31',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Japanese',
                'code' => 'ja',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-17 15:01:31',
                'updated_at' => '2019-09-17 15:01:31',
            ),
            11 => 
            array (
                'id' => 12,
            'name' => 'Chinese (simplified)',
                'code' => 'zh',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-17 15:01:31',
                'updated_at' => '2019-09-17 15:01:31',
            ),
        ));
        
        
    }
}