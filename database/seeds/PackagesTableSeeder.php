<?php

use Illuminate\Database\Seeder;

class PackagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('packages')->delete();
        
        \DB::table('packages')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => 'Trial',
                'sub_title' => 'Free For Specific Days',
                'icon' => NULL,
                'monthly_price' => 0.0,
                'yearly_price' => 0.0,
                'description' => '<ul><li>Max. file size = 1024 MB</li><li>Total allocated space up-to 1024 MB</li><li>Unlimited link transmissions</li><li>Transfers expire after 7 days</li><li>Files are deleted after 14 days</li><li>Re-sending, forwarding or deleting transmissions</li><li>Short link with ned.link<br></li></ul><p><br></p>',
                'status' => 0,
                'deleted_at' => NULL,
                'created_at' => '2019-09-17 14:57:17',
                'updated_at' => '2020-08-24 11:06:18',
            ),
            1 => 
            array (
                'id' => 2,
                'title' => 'Free',
                'sub_title' => 'Always Free',
                'icon' => NULL,
                'monthly_price' => 0.0,
                'yearly_price' => 0.0,
                'description' => '<ul><li>Max. file size = 2024 MB</li><li>Total allocated space up-to 2079 MB</li><li>Unlimited link transmissions</li><li>Transfers expire after 14 days</li><li>Files are deleted after 21 days</li><li>Re-sending, forwarding or deleting transmissions</li><li>Short link with ned.link<br></li></ul><p><br></p>',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-17 14:58:50',
                'updated_at' => '2020-08-24 11:16:53',
            ),
            2 => 
            array (
                'id' => 3,
                'title' => 'Lite',
                'sub_title' => 'Paid',
                'icon' => NULL,
                'monthly_price' => 0.5,
                'yearly_price' => 6.0,
                'description' => '<ul><li>Max. file size = 2560 MB</li><li>Total allocated space up-to 10752 MB</li><li>Unlimited link transmissions</li><li>Transfers expire after 31 days</li><li>Files are deleted after 93 days</li><li>Re-sending, forwarding or deleting transmissions</li><li>Password protection for transfers<br></li><li>Short link with ned.link<br></li></ul><p><br></p>',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2019-09-17 14:59:34',
                'updated_at' => '2020-08-24 11:44:43',
            ),
            3 => 
            array (
                'id' => 4,
                'title' => 'Basic',
                'sub_title' => 'Paid',
                'icon' => NULL,
                'monthly_price' => 1.0,
                'yearly_price' => 12.0,
                'description' => '<ul><li>Max. file size = 3 GB</li><li>Total allocated space up-to 50 GB</li><li>Unlimited link transmissions</li><li>Decide for yourself when transmissions expire</li><li>Re-sending, forwarding or deleting transmissions</li><li>Password protection for transfers</li><li>Short link with ned.link<br></li></ul><p><br></p>',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-01-08 18:29:24',
                'updated_at' => '2020-08-24 11:53:51',
            ),
            4 => 
            array (
                'id' => 5,
                'title' => 'Plus',
                'sub_title' => 'Paid',
                'icon' => NULL,
                'monthly_price' => 1.99,
                'yearly_price' => 21.89,
                'description' => '<ul><li>Max. file size = 5 GB</li><li>Total allocated space up-to 100 GB</li><li>Unlimited link transmissions</li><li>Decide for yourself when transmissions expire</li><li>Re-sending, forwarding or deleting transmissions</li><li>Password protection for transfers</li><li>Short link with ned.link<br></li></ul><p><br></p>',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-06-23 14:12:49',
                'updated_at' => '2020-08-24 12:59:09',
            ),
            5 => 
            array (
                'id' => 6,
                'title' => 'Professional',
                'sub_title' => 'Paid',
                'icon' => NULL,
                'monthly_price' => 2.99,
                'yearly_price' => 32.89,
                'description' => '<ul><li>Max. file size = 10 GB</li><li>Total allocated space up-to 200 GB</li><li>Unlimited link transmissions</li><li>Decide for yourself when transmissions expire</li><li>Re-sending, forwarding or deleting transmissions</li><li>Password protection for transfers</li><li>Short link with ned.link<br></li></ul><p><br></p>',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-08-24 12:07:23',
                'updated_at' => '2020-08-24 12:59:29',
            ),
            6 => 
            array (
                'id' => 7,
                'title' => 'Premium',
                'sub_title' => 'Paid',
                'icon' => NULL,
                'monthly_price' => 8.99,
                'yearly_price' => 98.89,
                'description' => '<ul><li>Max. file size = 20 GB</li><li>Total allocated space up-to 2 TB</li><li>Unlimited link transmissions</li><li>Decide for yourself when transmissions expire</li><li>Re-sending, forwarding or deleting transmissions</li><li>Password protection for transfers</li><li>Short link with ned.link<br></li></ul><p><br></p>',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-08-24 12:10:26',
                'updated_at' => '2020-08-24 12:59:39',
            ),
        ));
        
        
    }
}