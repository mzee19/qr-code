<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Administrator',
                'right_ids' => NULL,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-17 14:47:46',
                'updated_at' => '2019-09-17 19:47:46',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Manager',
                'right_ids' => '1,2,3',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-17 14:51:35',
                'updated_at' => '2020-01-10 18:36:54',
            ),
        ));
        
        
    }
}