<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('settings')->delete();
        
        \DB::table('settings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'option_name' => 'site_title',
                'option_value' => 'QR Code',
            ),
            1 => 
            array (
                'id' => 2,
                'option_name' => 'office_address',
                'option_value' => 'Erftstr. 15
38120 Braunschweig',
            ),
            2 => 
            array (
                'id' => 3,
                'option_name' => 'contact_number',
                'option_value' => '+49 1579 2301998',
            ),
            3 => 
            array (
                'id' => 4,
                'option_name' => 'contact_email',
                'option_value' => 'support@qrcode.com',
            ),
            4 => 
            array (
                'id' => 5,
                'option_name' => 'operating_hours',
                'option_value' => 'Monday to Friday 9 AM - 4 PM
(UTC+02:00)',
            ),
            5 => 
            array (
                'id' => 6,
                'option_name' => 'pinterest',
                'option_value' => 'https://www.pinterest.com/',
            ),
            6 => 
            array (
                'id' => 7,
                'option_name' => 'facebook',
                'option_value' => 'https://www.facebook.com/facebook',
            ),
            7 => 
            array (
                'id' => 8,
                'option_name' => 'twitter',
                'option_value' => 'https://www.twitter.com/twitter',
            ),
            8 => 
            array (
                'id' => 9,
                'option_name' => 'linkedin',
                'option_value' => 'https://linkedin.com/',
            ),
            9 => 
            array (
                'id' => 10,
                'option_name' => 'apply_permissions_on_frontend',
                'option_value' => '1',
            ),
            10 => 
            array (
                'id' => 11,
                'option_name' => 'number_of_days',
                'option_value' => '0',
            ),
            11 => 
            array (
                'id' => 12,
                'option_name' => 'vat',
                'option_value' => '19',
            ),
            12 => 
            array (
                'id' => 13,
                'option_name' => 'user_deletion_days',
                'option_value' => '90',
            ),
            13 => 
            array (
                'id' => 14,
                'option_name' => 'payment_relief_days',
                'option_value' => '5',
            ),
            14 => 
            array (
                'id' => 15,
                'option_name' => 'website',
                'option_value' => 'https://timmunity.org',
            ),
            15 => 
            array (
                'id' => 16,
                'option_name' => 'commercial_register_address',
                'option_value' => 'Handelsregister Braunschweig HRB 208156',
            ),
            16 => 
            array (
                'id' => 17,
                'option_name' => 'vat_id',
                'option_value' => 'DE327709293',
            ),
            17 => 
            array (
                'id' => 18,
                'option_name' => 'bank_name',
                'option_value' => 'Commerzbank',
            ),
            18 => 
            array (
                'id' => 19,
                'option_name' => 'iban',
                'option_value' => 'DE12 2704 0080 0480 6725 00',
            ),
            19 => 
            array (
                'id' => 20,
                'option_name' => 'code',
                'option_value' => 'COBADEFFXXX',
            ),
            20 => 
            array (
                'id' => 21,
                'option_name' => 'company_name',
                'option_value' => 'TIMmunity GmbH',
            ),
            21 => 
            array (
                'id' => 22,
                'option_name' => 'company_registration_number',
                'option_value' => 'DE327709293',
            ),
            22 => 
            array (
                'id' => 23,
                'option_name' => 'company_street',
                'option_value' => 'Erftstr. 15',
            ),
            23 => 
            array (
                'id' => 24,
                'option_name' => 'company_zip_code',
                'option_value' => '38120',
            ),
            24 => 
            array (
                'id' => 25,
                'option_name' => 'company_city',
                'option_value' => 'Braunschweig',
            ),
            25 => 
            array (
                'id' => 26,
                'option_name' => 'company_country',
                'option_value' => 'Germany',
            ),
            26 => 
            array (
                'id' => 27,
                'option_name' => 'tax_id',
                'option_value' => '14/201/04214',
            ),
            27 => 
            array (
                'id' => 28,
                'option_name' => 'youtube',
                'option_value' => 'https://www.youtube.com',
            ),
        ));
        
        
    }
}