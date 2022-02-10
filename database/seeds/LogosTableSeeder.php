<?php

use Illuminate\Database\Seeder;

class LogosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('logos')->delete();
        
        \DB::table('logos')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => 'Qr Code',
                'image' => 'logo-phone-outline.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:41:42',
                'updated_at' => '2021-04-05 09:51:00',
            ),
            1 => 
            array (
                'id' => 2,
                'title' => 'FaceBook',
                'image' => 'logo-584ac2d03ac3a570f94a666d.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:42:13',
                'updated_at' => '2021-03-31 06:27:02',
            ),
            2 => 
            array (
                'id' => 3,
                'title' => 'Facebook Cirle',
            'image' => 'logo-download (5).png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:42:36',
                'updated_at' => '2021-03-31 06:26:33',
            ),
            3 => 
            array (
                'id' => 4,
                'title' => 'Twitter Circle',
                'image' => 'logo-twitter-circle-1868970-1583134.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:43:34',
                'updated_at' => '2021-03-31 06:26:19',
            ),
            4 => 
            array (
                'id' => 5,
                'title' => 'Youtube',
                'image' => 'logo-images.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:44:14',
                'updated_at' => '2021-03-31 06:24:06',
            ),
            5 => 
            array (
                'id' => 6,
                'title' => 'Youtube Circle',
                'image' => 'logo-numix-circle-for-windows-youtube-icon-png-icon.jpg',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:44:44',
                'updated_at' => '2021-03-31 06:23:25',
            ),
            6 => 
            array (
                'id' => 7,
                'title' => 'Google Plus Circle',
            'image' => 'logo-download (4).png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:45:10',
                'updated_at' => '2021-03-31 06:22:25',
            ),
            7 => 
            array (
                'id' => 8,
                'title' => 'Instagram Circle',
                'image' => 'logo-instagram-new-flat.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:45:39',
                'updated_at' => '2021-03-31 06:22:14',
            ),
            8 => 
            array (
                'id' => 9,
                'title' => 'LinkedIn Circle',
                'image' => 'logo-unnamed.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:46:10',
                'updated_at' => '2021-03-31 06:19:32',
            ),
            9 => 
            array (
                'id' => 10,
                'title' => 'Xing Circle',
                'image' => 'logo-xing-23-898051.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:46:37',
                'updated_at' => '2021-03-31 06:20:31',
            ),
            10 => 
            array (
                'id' => 11,
                'title' => 'Pinterest Circle',
            'image' => 'logo-download (3).png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:47:22',
                'updated_at' => '2021-03-31 06:17:53',
            ),
            11 => 
            array (
                'id' => 12,
                'title' => 'Vimeo Circle',
                'image' => 'logo-vimeo-flat.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:47:49',
                'updated_at' => '2021-03-31 06:17:41',
            ),
            12 => 
            array (
                'id' => 13,
                'title' => 'Sound Cloud Circle',
            'image' => 'logo-download (2).png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:48:27',
                'updated_at' => '2021-03-31 06:16:15',
            ),
            13 => 
            array (
                'id' => 14,
                'title' => 'VK Circle',
                'image' => 'logo-vk-11-721983.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:49:00',
                'updated_at' => '2021-03-31 06:16:04',
            ),
            14 => 
            array (
                'id' => 15,
                'title' => 'Whatsapp Circle',
                'image' => 'logo-79dc31280371b8ffbe56ec656418e122.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:49:23',
                'updated_at' => '2021-03-31 06:14:25',
            ),
            15 => 
            array (
                'id' => 16,
                'title' => 'App Store',
            'image' => 'logo-download (1).png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:49:48',
                'updated_at' => '2021-03-31 06:12:37',
            ),
            16 => 
            array (
                'id' => 17,
                'title' => 'Google Play',
                'image' => 'logo-google-play.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:50:19',
                'updated_at' => '2021-03-30 12:26:22',
            ),
            17 => 
            array (
                'id' => 18,
                'title' => 'Gmail',
                'image' => 'logo-gmail.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:50:41',
                'updated_at' => '2021-03-30 12:25:28',
            ),
            18 => 
            array (
                'id' => 19,
                'title' => 'Calendar Circle',
                'image' => 'logo-calendar-2887151-2394209.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:51:09',
                'updated_at' => '2021-03-31 06:12:26',
            ),
            19 => 
            array (
                'id' => 20,
                'title' => 'Document Circle',
                'image' => 'logo-documents-outline.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:51:37',
                'updated_at' => '2021-03-31 06:10:53',
            ),
            20 => 
            array (
                'id' => 21,
                'title' => 'Viber',
                'image' => 'logo-phone-outline.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:52:02',
                'updated_at' => '2021-04-05 09:49:40',
            ),
            21 => 
            array (
                'id' => 22,
                'title' => 'Share Circle',
                'image' => 'logo-share-outline.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:52:27',
                'updated_at' => '2021-03-31 06:10:31',
            ),
            22 => 
            array (
                'id' => 23,
                'title' => 'Wifi Circle',
                'image' => 'logo-wifi-icon-13-256.png',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-09 13:53:02',
                'updated_at' => '2021-03-31 06:10:16',
            ),
            23 => 
            array (
                'id' => 24,
                'title' => 'Bitcoin',
                'image' => 'logo-white-transparent-leaf-on-mirror-260nw-1029171697.jpg',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-03-19 12:37:24',
                'updated_at' => '2021-03-24 11:30:18',
            ),
        ));
        
        
    }
}