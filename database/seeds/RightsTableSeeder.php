<?php

use Illuminate\Database\Seeder;

class RightsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('rights')->delete();

        \DB::table('rights')->insert(array (
            0 =>
            array (
                'id' => 1,
                'module_id' => 1,
                'name' => 'User Payments',
                'status' => 1,
                'created_at' => '2021-05-23 22:51:02',
                'updated_at' => '2021-05-23 22:51:02',
            ),
            1 =>
            array (
                'id' => 2,
                'module_id' => 1,
                'name' => 'User Subscriptions',
                'status' => 1,
                'created_at' => '2021-05-23 22:51:43',
                'updated_at' => '2021-05-23 22:51:43',
            ),
            2 =>
            array (
                'id' => 3,
                'module_id' => 1,
                'name' => 'User Update Packages',
                'status' => 1,
                'created_at' => '2021-05-23 22:52:34',
                'updated_at' => '2021-05-23 22:52:34',
            ),
            3 =>
            array (
                'id' => 4,
                'module_id' => 1,
                'name' => 'User QR Codes',
                'status' => 1,
                'created_at' => '2021-05-23 22:53:03',
                'updated_at' => '2021-05-23 22:53:03',
            ),
            4 =>
            array (
                'id' => 5,
                'module_id' => 1,
                'name' => 'User Add',
                'status' => 1,
                'created_at' => '2021-05-23 22:54:54',
                'updated_at' => '2021-05-23 22:54:54',
            ),
            5 =>
            array (
                'id' => 6,
                'module_id' => 1,
                'name' => 'User View',
                'status' => 1,
                'created_at' => '2021-05-23 22:54:54',
                'updated_at' => '2021-05-23 22:54:54',
            ),
            6 =>
            array (
                'id' => 7,
                'module_id' => 1,
                'name' => 'User Edit',
                'status' => 1,
                'created_at' => '2021-05-23 22:56:13',
                'updated_at' => '2021-05-23 22:56:13',
            ),
            7 =>
            array (
                'id' => 8,
                'module_id' => 1,
                'name' => 'User Delete',
                'status' => 1,
                'created_at' => '2021-05-23 22:58:06',
                'updated_at' => '2021-05-23 22:58:06',
            ),
            8 =>
            array (
                'id' => 9,
                'module_id' => 3,
                'name' => 'Role Add',
                'status' => 1,
                'created_at' => '2021-05-23 23:00:22',
                'updated_at' => '2021-05-23 23:00:22',
            ),
            9 =>
            array (
                'id' => 10,
                'module_id' => 3,
                'name' => 'Role Edit',
                'status' => 1,
                'created_at' => '2021-05-23 23:00:22',
                'updated_at' => '2021-05-23 23:00:22',
            ),
            10 =>
            array (
                'id' => 11,
                'module_id' => 3,
                'name' => 'Role Delete',
                'status' => 1,
                'created_at' => '2021-05-23 23:02:19',
                'updated_at' => '2021-05-23 23:02:19',
            ),
            11 =>
            array (
                'id' => 12,
                'module_id' => 4,
                'name' => 'Sub-Admin Add',
                'status' => 1,
                'created_at' => '2021-05-23 23:04:38',
                'updated_at' => '2021-05-23 23:04:38',
            ),
            12 =>
            array (
                'id' => 13,
                'module_id' => 4,
                'name' => 'Sub-Admin Edit',
                'status' => 1,
                'created_at' => '2021-05-23 23:04:38',
                'updated_at' => '2021-05-23 23:04:38',
            ),
            13 =>
            array (
                'id' => 14,
                'module_id' => 4,
                'name' => 'Sub-Admin Delete',
                'status' => 1,
                'created_at' => '2021-05-23 23:05:43',
                'updated_at' => '2021-05-23 23:05:43',
            ),
            14 =>
            array (
                'id' => 15,
                'module_id' => 5,
                'name' => 'Country Wise VAT Add',
                'status' => 1,
                'created_at' => '2021-05-23 23:13:17',
                'updated_at' => '2021-05-23 23:13:17',
            ),
            15 =>
            array (
                'id' => 16,
                'module_id' => 5,
                'name' => 'Country Wise VAT Edit',
                'status' => 1,
                'created_at' => '2021-05-23 23:13:17',
                'updated_at' => '2021-05-23 23:13:17',
            ),
            16 =>
            array (
                'id' => 17,
                'module_id' => 5,
                'name' => 'Country Wise VAT Delete',
                'status' => 1,
                'created_at' => '2021-05-23 23:16:26',
                'updated_at' => '2021-05-23 23:16:26',
            ),
            17 =>
            array (
                'id' => 18,
                'module_id' => 18,
                'name' => 'QR Code Shapes Add',
                'status' => 0,
                'created_at' => '2021-05-23 23:25:28',
                'updated_at' => '2021-05-23 23:25:28',
            ),
            18 =>
            array (
                'id' => 19,
                'module_id' => 18,
                'name' => 'QR Code Shapes Edit',
                'status' => 1,
                'created_at' => '2021-05-23 23:25:28',
                'updated_at' => '2021-05-23 23:25:28',
            ),
            19 =>
            array (
                'id' => 20,
                'module_id' => 18,
                'name' => 'QR Code Delete',
                'status' => 0,
                'created_at' => '2021-05-23 23:31:19',
                'updated_at' => '2021-05-23 23:31:19',
            ),
            20 =>
            array (
                'id' => 21,
                'module_id' => 19,
                'name' => 'QR Code Logo Add',
                'status' => 1,
                'created_at' => '2021-05-23 23:33:00',
                'updated_at' => '2021-05-23 23:33:00',
            ),
            21 =>
            array (
                'id' => 22,
                'module_id' => 19,
                'name' => 'QR Code Logo Edit',
                'status' => 1,
                'created_at' => '2021-05-23 23:33:00',
                'updated_at' => '2021-05-23 23:33:00',
            ),
            22 =>
            array (
                'id' => 23,
                'module_id' => 19,
                'name' => 'QR Code Logo Delete',
                'status' => 1,
                'created_at' => '2021-05-23 23:33:57',
                'updated_at' => '2021-05-23 23:33:57',
            ),
            23 =>
            array (
                'id' => 24,
                'module_id' => 20,
                'name' => 'QR Code Template Add',
                'status' => 1,
                'created_at' => '2021-05-23 23:37:42',
                'updated_at' => '2021-05-23 23:37:42',
            ),
            24 =>
            array (
                'id' => 25,
                'module_id' => 20,
                'name' => 'QR Code Template Edit',
                'status' => 1,
                'created_at' => '2021-05-23 23:37:42',
                'updated_at' => '2021-05-23 23:37:42',
            ),
            25 =>
            array (
                'id' => 26,
                'module_id' => 20,
                'name' => 'QR Code Template Delete',
                'status' => 1,
                'created_at' => '2021-05-23 23:39:22',
                'updated_at' => '2021-05-23 23:39:22',
            ),
            26 =>
            array (
                'id' => 27,
                'module_id' => 21,
                'name' => 'Package Features Add',
                'status' => 1,
                'created_at' => '2021-05-23 23:43:30',
                'updated_at' => '2021-05-23 23:43:30',
            ),
            27 =>
            array (
                'id' => 28,
                'module_id' => 21,
                'name' => 'Package Features Edit',
                'status' => 1,
                'created_at' => '2021-05-23 23:43:30',
                'updated_at' => '2021-05-23 23:43:30',
            ),
            28 =>
            array (
                'id' => 29,
                'module_id' => 21,
                'name' => 'Package Features Delete',
                'status' => 0,
                'created_at' => '2021-05-23 23:44:06',
                'updated_at' => '2021-05-23 23:44:06',
            ),
            29 =>
            array (
                'id' => 30,
                'module_id' => 22,
                'name' => 'Packages Add',
                'status' => 1,
                'created_at' => '2021-05-23 23:45:40',
                'updated_at' => '2021-05-23 23:45:40',
            ),
            30 =>
            array (
                'id' => 31,
                'module_id' => 22,
                'name' => 'Packages Edit',
                'status' => 1,
                'created_at' => '2021-05-23 23:45:40',
                'updated_at' => '2021-05-23 23:45:40',
            ),
            31 =>
            array (
                'id' => 32,
                'module_id' => 22,
                'name' => 'Packages Delete',
                'status' => 1,
                'created_at' => '2021-05-23 23:46:20',
                'updated_at' => '2021-05-23 23:46:20',
            ),
            32 =>
            array (
                'id' => 33,
                'module_id' => 22,
                'name' => 'Packages Subscriptions',
                'status' => 1,
                'created_at' => '2021-05-23 23:46:40',
                'updated_at' => '2021-05-23 23:46:40',
            ),
            33 =>
            array (
                'id' => 34,
                'module_id' => 22,
                'name' => 'Packages Clone',
                'status' => 1,
                'created_at' => '2021-05-23 23:47:22',
                'updated_at' => '2021-05-23 23:47:22',
            ),
            34 =>
            array (
                'id' => 35,
                'module_id' => 6,
                'name' => 'Language Add',
                'status' => 1,
                'created_at' => '2021-05-23 23:48:25',
                'updated_at' => '2021-05-23 23:48:25',
            ),
            35 =>
            array (
                'id' => 36,
                'module_id' => 6,
                'name' => 'Language Edit',
                'status' => 1,
                'created_at' => '2021-05-23 23:48:25',
                'updated_at' => '2021-05-23 23:48:25',
            ),
            36 =>
            array (
                'id' => 37,
                'module_id' => 6,
                'name' => 'Language Delete',
                'status' => 0,
                'created_at' => '2021-05-23 23:49:08',
                'updated_at' => '2021-05-23 23:49:08',
            ),
            37 =>
            array (
                'id' => 38,
                'module_id' => 7,
                'name' => 'Language Modules Add',
                'status' => 0,
                'created_at' => '2021-05-23 23:54:24',
                'updated_at' => '2021-05-23 23:54:24',
            ),
            38 =>
            array (
                'id' => 39,
                'module_id' => 7,
                'name' => 'Language Modules Edit',
                'status' => 0,
                'created_at' => '2021-05-23 23:54:24',
                'updated_at' => '2021-05-23 23:54:24',
            ),
            39 =>
            array (
                'id' => 40,
                'module_id' => 7,
                'name' => 'Language Modules Delete',
                'status' => 0,
                'created_at' => '2021-05-23 23:55:08',
                'updated_at' => '2021-05-23 23:55:08',
            ),
            40 =>
            array (
                'id' => 41,
                'module_id' => 8,
                'name' => 'Language Translation Partial',
                'status' => 1,
                'created_at' => '2021-05-23 23:58:58',
                'updated_at' => '2021-05-23 23:58:58',
            ),
            41 =>
            array (
                'id' => 42,
                'module_id' => 8,
                'name' => 'Language Translation Bulk',
                'status' => 1,
                'created_at' => '2021-05-24 23:58:58',
                'updated_at' => '2021-05-24 23:58:58',
            ),
            42 =>
            array (
                'id' => 43,
                'module_id' => 8,
                'name' => 'Language Translation Edit',
                'status' => 1,
                'created_at' => '2021-05-24 00:00:39',
                'updated_at' => '2021-05-24 00:00:39',
            ),
            43 =>
            array (
                'id' => 44,
                'module_id' => 8,
                'name' => 'Language Translation Delete',
                'status' => 1,
                'created_at' => '2021-05-24 00:00:39',
                'updated_at' => '2021-05-24 00:00:39',
            ),
            44 =>
            array (
                'id' => 48,
                'module_id' => 10,
                'name' => 'Emil Templates Add',
                'status' => 0,
                'created_at' => '2021-05-24 00:09:54',
                'updated_at' => '2021-05-24 00:09:54',
            ),
            45 =>
            array (
                'id' => 49,
                'module_id' => 10,
                'name' => 'Emil Templates Edit',
                'status' => 1,
                'created_at' => '2021-05-24 00:10:12',
                'updated_at' => '2021-05-24 00:10:12',
            ),
            46 =>
            array (
                'id' => 50,
                'module_id' => 10,
                'name' => 'Emil Templates Delete',
                'status' => 0,
                'created_at' => '2021-05-24 00:10:12',
                'updated_at' => '2021-05-24 00:10:12',
            ),
            47 =>
            array (
                'id' => 51,
                'module_id' => 11,
                'name' => 'Emil Template Labels Add',
                'status' => 1,
                'created_at' => '2021-05-24 00:11:21',
                'updated_at' => '2021-05-24 00:11:21',
            ),
            48 =>
            array (
                'id' => 52,
                'module_id' => 11,
                'name' => 'Emil Template Labels Edit',
                'status' => 1,
                'created_at' => '2021-05-24 00:11:21',
                'updated_at' => '2021-05-24 00:11:21',
            ),
            49 =>
            array (
                'id' => 53,
                'module_id' => 11,
                'name' => 'Emil Template Labels Delete',
                'status' => 1,
                'created_at' => '2021-05-24 00:12:13',
                'updated_at' => '2021-05-24 00:12:13',
            ),
            50 =>
            array (
                'id' => 54,
                'module_id' => 12,
                'name' => 'CMS Pages Add',
                'status' => 1,
                'created_at' => '2021-05-24 00:13:19',
                'updated_at' => '2021-05-24 00:13:19',
            ),
            51 =>
            array (
                'id' => 55,
                'module_id' => 12,
                'name' => 'CMS Pages Edit',
                'status' => 1,
                'created_at' => '2021-05-24 00:13:19',
                'updated_at' => '2021-05-24 00:13:19',
            ),
            52 =>
            array (
                'id' => 56,
                'module_id' => 12,
                'name' => 'CMS Pages Delete',
                'status' => 0,
                'created_at' => '2021-05-24 00:14:09',
                'updated_at' => '2021-05-24 00:14:09',
            ),
            53 =>
            array (
                'id' => 57,
                'module_id' => 13,
                'name' => 'CMS Page Labels Add',
                'status' => 1,
                'created_at' => '2021-05-24 00:14:46',
                'updated_at' => '2021-05-24 00:14:46',
            ),
            54 =>
            array (
                'id' => 58,
                'module_id' => 13,
                'name' => 'CMS Page Labels Edit',
                'status' => 1,
                'created_at' => '2021-05-24 00:14:46',
                'updated_at' => '2021-05-24 00:14:46',
            ),
            55 =>
            array (
                'id' => 59,
                'module_id' => 13,
                'name' => 'CMS Page Labels Delete',
                'status' => 1,
                'created_at' => '2021-05-24 00:15:57',
                'updated_at' => '2021-05-24 00:15:57',
            ),
            56 =>
            array (
                'id' => 60,
                'module_id' => 14,
                'name' => 'FAQs Add',
                'status' => 1,
                'created_at' => '2021-05-24 00:16:58',
                'updated_at' => '2021-05-24 00:16:58',
            ),
            57 =>
            array (
                'id' => 61,
                'module_id' => 14,
                'name' => 'FAQs Edit',
                'status' => 1,
                'created_at' => '2021-05-24 00:16:58',
                'updated_at' => '2021-05-24 00:16:58',
            ),
            58 =>
            array (
                'id' => 62,
                'module_id' => 14,
                'name' => 'FAQs Delete',
                'status' => 1,
                'created_at' => '2021-05-24 00:17:43',
                'updated_at' => '2021-05-24 00:17:43',
            ),
            59 =>
            array (
                'id' => 63,
                'module_id' => 15,
                'name' => 'Features Add',
                'status' => 1,
                'created_at' => '2021-05-24 00:18:55',
                'updated_at' => '2021-05-24 00:18:55',
            ),
            60 =>
            array (
                'id' => 64,
                'module_id' => 15,
                'name' => 'Features Edit',
                'status' => 1,
                'created_at' => '2021-05-24 00:18:55',
                'updated_at' => '2021-05-24 00:18:55',
            ),
            61 =>
            array (
                'id' => 65,
                'module_id' => 15,
                'name' => 'Features Delete',
                'status' => 1,
                'created_at' => '2021-05-24 00:19:57',
                'updated_at' => '2021-05-24 00:19:57',
            ),
            62 =>
            array (
                'id' => 66,
                'module_id' => 16,
                'name' => 'Contact Us Queries Add',
                'status' => 0,
                'created_at' => '2021-05-24 00:20:56',
                'updated_at' => '2021-05-24 00:20:56',
            ),
            63 =>
            array (
                'id' => 67,
                'module_id' => 16,
                'name' => 'Contact Us Queries Edit',
                'status' => 1,
                'created_at' => '2021-05-24 00:20:56',
                'updated_at' => '2021-05-24 00:20:56',
            ),
            64 =>
            array (
                'id' => 68,
                'module_id' => 16,
                'name' => 'Contact Us Queries Delete',
                'status' => 0,
                'created_at' => '2021-05-24 00:21:49',
                'updated_at' => '2021-05-24 00:21:49',
            ),
            65 =>
            array (
                'id' => 69,
                'module_id' => 17,
                'name' => 'Payment Gateways Edit',
                'status' => 1,
                'created_at' => '2021-05-24 00:23:03',
                'updated_at' => '2021-05-24 00:23:03',
            ),
            66 =>
            array (
                'id' => 70,
                'module_id' => 23,
                'name' => 'Settings',
                'status' => 1,
                'created_at' => '2021-05-24 00:24:51',
                'updated_at' => '2021-05-24 00:24:51',
            ),
            67 =>
            array (
                'id' => 72,
                'module_id' => 1,
                'name' => 'User View List',
                'status' => 1,
                'created_at' => '2021-05-24 01:03:27',
                'updated_at' => '2021-05-24 01:03:27',
            ),
            68 =>
            array (
                'id' => 73,
                'module_id' => 3,
                'name' => 'Role List',
                'status' => 1,
                'created_at' => '2021-05-24 01:23:01',
                'updated_at' => '2021-05-24 01:23:01',
            ),
            69 =>
            array (
                'id' => 74,
                'module_id' => 4,
                'name' => 'Sub-Admin List',
                'status' => 1,
                'created_at' => '2021-05-24 01:23:25',
                'updated_at' => '2021-05-24 01:23:25',
            ),
            70 =>
            array (
                'id' => 75,
                'module_id' => 4,
                'name' => 'Sub-Admin Profile',
                'status' => 1,
                'created_at' => '2021-05-24 02:51:53',
                'updated_at' => '2021-05-24 02:51:53',
            ),
            71 =>
            array (
                'id' => 76,
                'module_id' => 5,
                'name' => 'Country Wise VAT Management',
                'status' => 1,
                'created_at' => '2021-05-24 02:59:38',
                'updated_at' => '2021-05-24 02:59:38',
            ),
            72 =>
            array (
                'id' => 77,
                'module_id' => 18,
                'name' => 'QR Code Shapes Management',
                'status' => 1,
                'created_at' => '2021-05-24 03:13:02',
                'updated_at' => '2021-05-24 03:13:02',
            ),
            73 =>
            array (
                'id' => 78,
                'module_id' => 19,
                'name' => 'QR Code Logos Management',
                'status' => 1,
                'created_at' => '2021-05-24 03:13:02',
                'updated_at' => '2021-05-24 03:13:02',
            ),
            74 =>
            array (
                'id' => 79,
                'module_id' => 20,
                'name' => 'QR Code Templates Managment',
                'status' => 1,
                'created_at' => '2021-05-24 03:13:56',
                'updated_at' => '2021-05-24 03:13:56',
            ),
            75 =>
            array (
                'id' => 80,
                'module_id' => 22,
                'name' => 'Packages Management',
                'status' => 1,
                'created_at' => '2021-05-24 03:50:31',
                'updated_at' => '2021-05-24 03:50:31',
            ),
            76 =>
            array (
                'id' => 81,
                'module_id' => 21,
                'name' => 'Package Features Management',
                'status' => 1,
                'created_at' => '2021-05-24 03:53:38',
                'updated_at' => '2021-05-24 03:53:38',
            ),
            77 =>
            array (
                'id' => 82,
                'module_id' => 6,
                'name' => 'Languages Management',
                'status' => 1,
                'created_at' => '2021-05-24 04:13:55',
                'updated_at' => '2021-05-24 04:13:55',
            ),
            78 =>
            array (
                'id' => 83,
                'module_id' => 7,
                'name' => 'Language Modules Management',
                'status' => 1,
                'created_at' => '2021-05-24 04:13:55',
                'updated_at' => '2021-05-24 04:13:55',
            ),
            79 =>
            array (
                'id' => 84,
                'module_id' => 8,
                'name' => 'Languages Translation Management',
                'status' => 1,
                'created_at' => '2021-05-24 04:15:32',
                'updated_at' => '2021-05-24 04:15:32',
            ),
            80 =>
            array (
                'id' => 85,
                'module_id' => 9,
                'name' => 'Labels Translation',
                'status' => 1,
                'created_at' => '2021-05-24 04:16:56',
                'updated_at' => '2021-05-24 04:16:56',
            ),
            81 =>
            array (
                'id' => 86,
                'module_id' => 8,
                'name' => 'Language Translations Filter',
                'status' => 1,
                'created_at' => '2021-05-24 04:35:43',
                'updated_at' => '2021-05-24 04:35:43',
            ),
            82 =>
            array (
                'id' => 87,
                'module_id' => 10,
                'name' => 'Email Template Listing',
                'status' => 1,
                'created_at' => '2021-05-24 04:43:36',
                'updated_at' => '2021-05-24 04:43:36',
            ),
            83 =>
            array (
                'id' => 88,
                'module_id' => 11,
                'name' => 'Email Template Labels Management',
                'status' => 1,
                'created_at' => '2021-05-24 04:43:36',
                'updated_at' => '2021-05-24 04:43:36',
            ),
            84 =>
            array (
                'id' => 89,
                'module_id' => 11,
                'name' => 'Email Template Label Filters',
                'status' => 1,
                'created_at' => '2021-05-24 04:57:14',
                'updated_at' => '2021-05-24 04:57:14',
            ),
            85 =>
            array (
                'id' => 90,
                'module_id' => 12,
                'name' => 'CMS Pages Management',
                'status' => 1,
                'created_at' => '2021-05-24 05:03:53',
                'updated_at' => '2021-05-24 05:03:53',
            ),
            86 =>
            array (
                'id' => 91,
                'module_id' => 13,
                'name' => 'CMS Pages Label Management',
                'status' => 1,
                'created_at' => '2021-05-24 05:03:53',
                'updated_at' => '2021-05-24 05:03:53',
            ),
            87 =>
            array (
                'id' => 92,
                'module_id' => 13,
                'name' => 'CMS Pages Labels Filter',
                'status' => 1,
                'created_at' => '2021-05-24 05:18:34',
                'updated_at' => '2021-05-24 05:18:34',
            ),
            88 =>
            array (
                'id' => 93,
                'module_id' => 14,
                'name' => 'FAQs Management',
                'status' => 1,
                'created_at' => '2021-05-24 05:34:00',
                'updated_at' => '2021-05-24 05:34:00',
            ),
            89 =>
            array (
                'id' => 94,
                'module_id' => 15,
                'name' => 'Features Management',
                'status' => 1,
                'created_at' => '2021-05-24 05:39:16',
                'updated_at' => '2021-05-24 05:39:16',
            ),
            90 =>
            array (
                'id' => 95,
                'module_id' => 16,
                'name' => 'Contact Us Queries Management',
                'status' => 1,
                'created_at' => '2021-05-24 05:56:25',
                'updated_at' => '2021-05-24 05:56:25',
            ),
            91 =>
            array (
                'id' => 97,
                'module_id' => 2,
                'name' => 'Lawful Interception Listing',
                'status' => 1,
                'created_at' => '2021-05-24 05:56:25',
                'updated_at' => '2021-05-24 05:56:25',
            ),
            92 =>
            array (
                'id' => 98,
                'module_id' => 2,
                'name' => 'User Details PDF',
                'status' => 1,
                'created_at' => '2021-05-24 05:56:25',
                'updated_at' => '2021-05-24 05:56:25',
            ),
            93 =>
            array (
                'id' => 99,
                'module_id' => 2,
                'name' => 'User Payments PDF',
                'status' => 1,
                'created_at' => '2021-05-24 05:56:25',
                'updated_at' => '2021-05-24 05:56:25',
            ),
            94 =>
            array (
                'id' => 100,
                'module_id' => 2,
                'name' => 'User Subscriptions PDF',
                'status' => 1,
                'created_at' => '2021-05-24 05:56:25',
                'updated_at' => '2021-05-24 05:56:25',
            ),
            95 =>
            array (
                'id' => 101,
                'module_id' => 2,
                'name' => 'User QrCodes PDF',
                'status' => 1,
                'created_at' => '2021-05-24 05:56:25',
                'updated_at' => '2021-05-24 05:56:25',
            ),
            96 =>
            array (
                'id' => 102,
                'module_id' => 2,
                'name' => 'User QrCodes Download',
                'status' => 1,
                'created_at' => '2021-05-24 05:56:25',
                'updated_at' => '2021-05-24 05:56:25',
            ),
            97 =>
            array (
                'id' => 103,
                'module_id' => 2,
                'name' => 'Download All Data',
                'status' => 1,
                'created_at' => '2021-05-24 05:56:25',
                'updated_at' => '2021-05-24 05:56:25',
            ),
            98 =>
            array (
                'id' => 104,
                'module_id' => 24,
                'name' => 'Subscribers Listing',
                'status' => 1,
                'created_at' => '2021-05-24 05:56:25',
                'updated_at' => '2021-05-24 05:56:25',
            ),
            99 =>
            array (
                'id' => 105,
                'module_id' => 1,
                'name' => 'User`s Qr Code View',
                'status' => 1,
                'created_at' => '2021-05-24 05:56:25',
                'updated_at' => '2021-05-24 05:56:25',
            ),
            100 =>
            array (
                'id' => 106,
                'module_id' => 1,
                'name' => 'User`s Qr Code Stats',
                'status' => 1,
                'created_at' => '2021-05-24 05:56:25',
                'updated_at' => '2021-05-24 05:56:25',
            ),
            array (
                'id' => 107,
                'module_id' => 25,
                'name' => 'Guest`s Qr Code list',
                'status' => 1,
                'created_at' => '2021-05-24 05:56:25',
                'updated_at' => '2021-05-24 05:56:25',
            ),
            array (
                'id' => 108,
                'module_id' => 25,
                'name' => 'Guest`s Qr Code View',
                'status' => 1,
                'created_at' => '2021-05-24 05:56:25',
                'updated_at' => '2021-05-24 05:56:25',
            ),
            array (
                'id' => 109,
                'module_id' => 25,
                'name' => 'Guest`s Qr Code Delete',
                'status' => 1,
                'created_at' => '2021-05-24 05:56:25',
                'updated_at' => '2021-05-24 05:56:25',
            ),
        ));
    }
}
