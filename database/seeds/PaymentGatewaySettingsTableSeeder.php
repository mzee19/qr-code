<?php

use Illuminate\Database\Seeder;

class PaymentGatewaySettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payment_gateway_settings')->delete();
        
        \DB::table('payment_gateway_settings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'paypal_sandbox_api_username' => 'sb-ul1nw753290_api1.business.example.com',
                'paypal_sandbox_api_password' => '5X8SQ4UVGPWAP66Z',
                'paypal_sandbox_api_secret' => 'AjbmgrksNUOA20RdVBIPaJAqgv.mAtF4trGgMp2VcnBxb9-tjTKXDvRY',
                'paypal_sandbox_api_base_url' => 'https://api-3t.sandbox.paypal.com',
                'paypal_live_api_username' => NULL,
                'paypal_live_api_password' => NULL,
                'paypal_live_api_secret' => NULL,
                'paypal_live_api_base_url' => NULL,
                'paypal_mode' => 'sandbox',
                'paypal_status' => 1,
                'mollie_status' => 1,
                'mollie_mode' => 'sandbox',
                'mollie_live_api_key' => NULL,
                'mollie_sandbox_api_key' => 'test_T9PfwQ7NJ37VFkV4UNVs9tQv7SkKzf',
                'deleted_at' => NULL,
                'created_at' => '2020-05-15 17:11:43',
                'updated_at' => '2020-08-27 11:44:13',
            ),
        ));
        
        
    }
}