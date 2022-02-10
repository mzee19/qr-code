<?php

use Illuminate\Database\Seeder;

class FaqsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('faqs')->delete();
        
        \DB::table('faqs')->insert(array (
            0 => 
            array (
                'id' => 1,
                'question' => '1. Who can buy and use these vouchers?',
            'answer' => 'This voucher selling system is built for vendors to buy and sell TIMmunity Product vouchers. With this reseller program, you can buy vouchers related to specific products (AikQ, Move Immunity, etc.) and resell those vouchers to your current and prospective customers at a profit. Moreover, anyone can get these vouchers from vendors owning them and having the code keys they get through Product Immunity and use them to use TIMmunity solutions at lower rates.',
                'order_by' => 1,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-11-27 12:58:27',
                'updated_at' => '2020-01-28 12:13:07',
            ),
            1 => 
            array (
                'id' => 2,
                'question' => '2. For which products the vouchers can be redeemed?',
                'answer' => 'Vouchers can only be redeemed for the products offered by TIMmunity GmbH that have been saved in the vendor account after purchase.',
                'order_by' => 2,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-11-27 12:59:17',
                'updated_at' => '2020-01-28 12:13:16',
            ),
            2 => 
            array (
                'id' => 3,
                'question' => '3. Can discount codes be used for buying vouchers?',
                'answer' => 'No, it’s not possible.',
                'order_by' => 3,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-11-27 13:00:41',
                'updated_at' => '2020-01-28 12:14:06',
            ),
            3 => 
            array (
                'id' => 4,
                'question' => '4. Can an order be canceled?',
                'answer' => 'No, because he’s only paying for the used vouchers.',
                'order_by' => 4,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-11-27 13:01:07',
                'updated_at' => '2020-01-28 12:14:13',
            ),
            4 => 
            array (
                'id' => 5,
                'question' => '5. Can an order be modified?',
                'answer' => 'A submitted order cannot be modified.',
                'order_by' => 5,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-11-27 13:01:07',
                'updated_at' => '2019-11-27 13:01:07',
            ),
            5 => 
            array (
                'id' => 6,
                'question' => '6. Are there monthly or usage fees on vouchers?',
                'answer' => 'The usage fees are applied on the purchased vouchers. It means the reseller/vendor will only be charged for the used voucher codes and not for all the vouchers he purchased.',
                'order_by' => 6,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-11-27 13:01:07',
                'updated_at' => '2019-11-27 13:01:07',
            ),
            6 => 
            array (
                'id' => 7,
                'question' => '7. Are the vouchers refundable?',
                'answer' => 'No, the vouchers are non-refundable.',
                'order_by' => 7,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-11-27 13:01:07',
                'updated_at' => '2019-11-27 13:01:07',
            ),
            7 => 
            array (
                'id' => 8,
                'question' => '8. Is there any restriction on purchasing the vouchers?',
                'answer' => 'A vendor can purchase as many vouchers as he wants for one or more TIMmunity products.',
                'order_by' => 8,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-11-27 13:01:07',
                'updated_at' => '2019-11-27 13:01:07',
            ),
            8 => 
            array (
                'id' => 9,
                'question' => '9. Do the vouchers expire?',
                'answer' => 'Product Immunity vouchers don’t have an expiration date.',
                'order_by' => 9,
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-11-27 13:01:07',
                'updated_at' => '2019-11-27 13:01:07',
            ),
        ));
        
        
    }
}