<?php

use Illuminate\Database\Seeder;

class PackageFeaturesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('package_features')->delete();
        
        \DB::table('package_features')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Total allocated space in GB',
                'info' => NULL,
                'count' => 1,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-26 20:57:00',
                'updated_at' => '2020-05-04 05:01:58',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Maximum file size in GB',
                'info' => NULL,
                'count' => 1,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-26 20:57:00',
                'updated_at' => '2020-05-04 05:01:58',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Unlimited link transmissions',
                'info' => NULL,
                'count' => 0,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-26 20:57:00',
                'updated_at' => '2019-09-26 20:57:00',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Transfers expire after number of days',
                'info' => NULL,
                'count' => 1,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-26 20:57:00',
                'updated_at' => '2019-09-26 20:57:00',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Files are deleted after number of days',
                'info' => NULL,
                'count' => 1,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-26 20:57:00',
                'updated_at' => '2019-09-26 20:57:00',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Resending, forwarding or deleting transmissions',
                'info' => NULL,
                'count' => 0,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-26 20:57:00',
                'updated_at' => '2019-09-26 20:57:00',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Short link with ned.link',
                'info' => NULL,
                'count' => 0,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-26 20:57:00',
                'updated_at' => '2019-09-26 20:57:00',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Password protection for transfers',
                'info' => NULL,
                'count' => 0,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-26 20:57:00',
                'updated_at' => '2019-09-26 20:57:00',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Decide for yourself when transmissions expire',
                'info' => NULL,
                'count' => 0,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-26 20:57:00',
                'updated_at' => '2019-09-26 20:57:00',
            ),
        ));
        
        
    }
}