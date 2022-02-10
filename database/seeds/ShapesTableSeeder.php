<?php

use Illuminate\Database\Seeder;

class ShapesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('shapes')->delete();
        
        \DB::table('shapes')->insert(array (
            0 => 
            array (
                'id' => 2,
                'name' => 'shape1',
                'type' => 1,
                'image' => 'shape-Shape2.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-25 04:43:08',
                'updated_at' => '2021-04-05 11:31:44',
            ),
            1 => 
            array (
                'id' => 3,
                'name' => 'shape2',
                'type' => 1,
                'image' => 'shape-Shape3.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-25 04:43:27',
                'updated_at' => '2021-04-05 11:31:35',
            ),
            2 => 
            array (
                'id' => 4,
                'name' => 'shape3',
                'type' => 1,
                'image' => 'shape-Shape4.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-25 04:43:42',
                'updated_at' => '2021-04-05 11:31:24',
            ),
            3 => 
            array (
                'id' => 5,
                'name' => 'frame1',
                'type' => 2,
                'image' => 'shape-Frame1.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-25 04:46:26',
                'updated_at' => '2021-03-26 04:20:10',
            ),
            4 => 
            array (
                'id' => 6,
                'name' => 'frame2',
                'type' => 2,
                'image' => 'shape-frame2.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-25 04:47:51',
                'updated_at' => '2021-03-26 04:19:58',
            ),
            5 => 
            array (
                'id' => 7,
                'name' => 'frame3',
                'type' => 2,
                'image' => 'shape-Frame3.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-25 04:48:48',
                'updated_at' => '2021-03-26 04:19:48',
            ),
            6 => 
            array (
                'id' => 8,
                'name' => 'eye1',
                'type' => 3,
                'image' => 'shape-EyeBall1.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-25 04:51:38',
                'updated_at' => '2021-03-26 04:19:37',
            ),
            7 => 
            array (
                'id' => 9,
                'name' => 'eye2',
                'type' => 3,
                'image' => 'shape-Eyeball2.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-25 04:52:00',
                'updated_at' => '2021-03-26 04:19:24',
            ),
            8 => 
            array (
                'id' => 10,
                'name' => 'eye3',
                'type' => 3,
                'image' => 'shape-EyeBall3.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-25 04:52:20',
                'updated_at' => '2021-03-26 04:19:10',
            ),
        ));
        
        
    }
}