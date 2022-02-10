<?php

use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('modules')->delete();

        \DB::table('modules')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Users',
                'status' => 1,
                'created_at' => '2019-11-29 10:39:55',
                'updated_at' => '2019-11-29 10:39:55',
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'Lawful Interception',
                'status' => 1,
                'created_at' => '2019-11-29 10:39:55',
                'updated_at' => '2019-11-29 10:39:55',
            ),
            2 =>
            array (
                'id' => 3,
                'name' => 'Roles',
                'status' => 1,
                'created_at' => '2019-11-29 10:39:55',
                'updated_at' => '2019-11-29 10:39:55',
            ),
            3 =>
            array (
                'id' => 4,
                'name' => 'Sub-Admins',
                'status' => 1,
                'created_at' => '2019-11-29 10:39:55',
                'updated_at' => '2019-11-29 10:39:55',
            ),
            4 =>
            array (
                'id' => 5,
                'name' => 'Country Wise VAT',
                'status' => 1,
                'created_at' => '2019-11-29 10:39:55',
                'updated_at' => '2019-11-29 10:39:55',
            ),
            5 =>
            array (
                'id' => 6,
                'name' => 'Languages',
                'status' => 1,
                'created_at' => '2019-11-29 10:39:55',
                'updated_at' => '2019-11-29 10:39:55',
            ),
            6 =>
            array (
                'id' => 7,
                'name' => 'Language Modules',
                'status' => 1,
                'created_at' => '2019-11-29 10:39:55',
                'updated_at' => '2019-11-29 10:39:55',
            ),
            7 =>
            array (
                'id' => 8,
                'name' => 'Language Translations',
                'status' => 1,
                'created_at' => '2019-11-29 10:39:55',
                'updated_at' => '2019-11-29 10:39:55',
            ),
            8 =>
            array (
                'id' => 9,
                'name' => 'Label Translations',
                'status' => 1,
                'created_at' => '2019-11-29 10:39:55',
                'updated_at' => '2019-11-29 10:39:55',
            ),
            9 =>
            array (
                'id' => 10,
                'name' => 'Email Templates',
                'status' => 1,
                'created_at' => '2019-11-29 10:39:55',
                'updated_at' => '2019-11-29 10:39:55',
            ),
            10 =>
            array (
                'id' => 11,
                'name' => 'Email Template Labels',
                'status' => 1,
                'created_at' => '2019-11-29 10:39:55',
                'updated_at' => '2019-11-29 10:39:55',
            ),
            11 =>
            array (
                'id' => 12,
                'name' => 'CMS Pages',
                'status' => 1,
                'created_at' => '2019-11-29 10:39:55',
                'updated_at' => '2019-11-29 10:39:55',
            ),
            12 =>
            array (
                'id' => 13,
                'name' => 'CMS Page Labels',
                'status' => 1,
                'created_at' => '2019-11-29 10:39:55',
                'updated_at' => '2019-11-29 10:39:55',
            ),
            13 =>
            array (
                'id' => 14,
                'name' => 'FAQs',
                'status' => 1,
                'created_at' => '2019-11-29 10:39:55',
                'updated_at' => '2019-11-29 10:39:55',
            ),
            14 =>
            array (
                'id' => 15,
                'name' => 'Features',
                'status' => 1,
                'created_at' => '2019-11-29 10:39:55',
                'updated_at' => '2019-11-29 10:39:55',
            ),
            15 =>
            array (
                'id' => 16,
                'name' => 'Contact Us Queries',
                'status' => 1,
                'created_at' => '2019-11-29 10:39:55',
                'updated_at' => '2019-11-29 10:39:55',
            ),
            16 =>
            array (
                'id' => 17,
                'name' => 'Payment Gateways',
                'status' => 1,
                'created_at' => '2019-11-29 10:39:55',
                'updated_at' => '2019-11-29 10:39:55',
            ),
            17 =>
            array (
                'id' => 18,
                'name' => 'QR Code Shapes',
                'status' => 1,
                'created_at' => '2021-05-23 23:22:20',
                'updated_at' => '2021-05-23 23:22:20',
            ),
            18 =>
            array (
                'id' => 19,
                'name' => 'QR Code Logos',
                'status' => 1,
                'created_at' => '2021-05-23 23:32:19',
                'updated_at' => '2021-05-23 23:32:19',
            ),
            19 =>
            array (
                'id' => 20,
                'name' => 'QR Code Templates',
                'status' => 1,
                'created_at' => '2021-05-23 23:37:06',
                'updated_at' => '2021-05-23 23:37:06',
            ),
            20 =>
            array (
                'id' => 21,
                'name' => 'Package Features',
                'status' => 1,
                'created_at' => '2021-05-23 23:42:45',
                'updated_at' => '2021-05-23 23:42:45',
            ),
            21 =>
            array (
                'id' => 22,
                'name' => 'Packages',
                'status' => 1,
                'created_at' => '2021-05-23 23:44:57',
                'updated_at' => '2021-05-23 23:44:57',
            ),
            22 =>
            array (
                'id' => 23,
                'name' => 'Settings',
                'status' => 1,
                'created_at' => '2021-05-24 00:24:20',
                'updated_at' => '2021-05-24 00:24:20',
            ),
            23 =>
            array (
                'id' => 24,
                'name' => 'Subscribers',
                'status' => 1,
                'created_at' => '2021-05-24 00:24:20',
                'updated_at' => '2021-05-24 00:24:20',
            ),
            array (
                'id' => 25,
                'name' => 'Guest User Qr Code',
                'status' => 1,
                'created_at' => '2021-05-24 00:24:20',
                'updated_at' => '2021-05-24 00:24:20',
            ),
        ));


    }
}
