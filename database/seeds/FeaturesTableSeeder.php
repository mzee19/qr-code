<?php

use Illuminate\Database\Seeder;

class FeaturesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('features')->delete();
        
        \DB::table('features')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Easy Vouchers Ordering',
                'description' => 'Incredibly easy to order vouchers and send to your current and prospective customers to subscribe and use TIMmunity products.',
                'image' => NULL,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-07-22 13:42:37',
                'updated_at' => '2020-12-24 06:39:52',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Pay for Used Vouchers',
                'description' => 'Pay for redeemed vouchers only. Vendors will be charged for the used vouchers and not for all, which he purchased in bulk. ',
                'image' => NULL,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-07-22 13:47:04',
                'updated_at' => '2020-10-21 07:20:20',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Buy in Bulk',
                'description' => 'Place unlimited voucher orders to give your customers an opportunity to purchase and redeem them for TIMmunity products.',
                'image' => NULL,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-07-22 13:47:45',
                'updated_at' => '2020-10-21 07:20:39',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Unique Code Keys',
                'description' => 'Get the required number of specific codes with a unique key to identify the voucher type.  Codes will be sent to resellers via email.',
                'image' => NULL,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-10-06 11:11:28',
                'updated_at' => '2020-12-24 06:32:39',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Flexible & Secure',
                'description' => 'This voucher reselling system is fully secured and flexible. Get more choices with the ease of TIMmunity solutions access.',
                'image' => NULL,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-10-06 11:11:28',
                'updated_at' => '2020-10-06 11:11:28',
            ),
        ));
        
        
    }
}