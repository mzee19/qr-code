<?php

use Illuminate\Database\Seeder;

class EmailTemplateLabelsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('email_template_labels')->delete();
        
        \DB::table('email_template_labels')->insert(array (
            0 => 
            array (
                'id' => 1,
                'email_template_id' => 1,
                'label' => 'label_1.0',
                'value' => 'Reset Your Password',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 08:30:02',
                'updated_at' => '2021-01-06 08:30:02',
            ),
            1 => 
            array (
                'id' => 2,
                'email_template_id' => 1,
                'label' => 'label_1.1',
                'value' => 'Hi',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 08:30:02',
                'updated_at' => '2021-01-06 08:30:02',
            ),
            2 => 
            array (
                'id' => 3,
                'email_template_id' => 1,
                'label' => 'label_1.2',
                'value' => 'Tap the button below to reset your account password. If you didn\'t request for reset password, you can safely delete this email.',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 08:30:02',
                'updated_at' => '2021-01-06 08:30:02',
            ),
            3 => 
            array (
                'id' => 4,
                'email_template_id' => 1,
                'label' => 'label_1.3',
                'value' => 'Reset Password',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 08:30:02',
                'updated_at' => '2021-01-06 08:30:02',
            ),
            4 => 
            array (
                'id' => 5,
                'email_template_id' => 1,
                'label' => 'label_1.4',
                'value' => 'If that doesn\'t work, copy and paste the following link in your browser',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 08:30:02',
                'updated_at' => '2021-01-06 08:30:02',
            ),
            5 => 
            array (
                'id' => 6,
                'email_template_id' => 1,
                'label' => 'label_1.5',
                'value' => 'Cheers',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 08:30:02',
                'updated_at' => '2021-01-06 08:30:02',
            ),
            6 => 
            array (
                'id' => 7,
                'email_template_id' => 2,
                'label' => 'label_1.0',
                'value' => 'Verify Your Email To Start Using',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 08:30:02',
                'updated_at' => '2021-01-06 08:30:02',
            ),
            7 => 
            array (
                'id' => 8,
                'email_template_id' => 2,
                'label' => 'label_1.1',
                'value' => 'Hi',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 08:30:02',
                'updated_at' => '2021-01-06 08:30:02',
            ),
            8 => 
            array (
                'id' => 9,
                'email_template_id' => 2,
                'label' => 'label_1.2',
                'value' => 'Thank you for signing up. Click the button below to verify your',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 08:30:02',
                'updated_at' => '2021-01-06 08:30:02',
            ),
            9 => 
            array (
                'id' => 10,
                'email_template_id' => 2,
                'label' => 'label_1.3',
                'value' => 'account',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 08:30:02',
                'updated_at' => '2021-01-06 08:30:02',
            ),
            10 => 
            array (
                'id' => 11,
                'email_template_id' => 2,
                'label' => 'label_1.4',
                'value' => 'Verify Email Address',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 08:30:02',
                'updated_at' => '2021-04-12 10:33:20',
            ),
            11 => 
            array (
                'id' => 12,
                'email_template_id' => 2,
                'label' => 'label_1.5',
                'value' => 'If that doesn\'t work, copy and paste the following link in your browser',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 08:30:02',
                'updated_at' => '2021-01-06 08:30:02',
            ),
            12 => 
            array (
                'id' => 13,
                'email_template_id' => 2,
                'label' => 'label_1.6',
                'value' => 'If you did not create an account, no further action is required',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 08:30:02',
                'updated_at' => '2021-01-06 08:30:02',
            ),
            13 => 
            array (
                'id' => 14,
                'email_template_id' => 2,
                'label' => 'label_1.7',
                'value' => 'Cheers',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 08:30:02',
                'updated_at' => '2021-01-06 08:30:02',
            ),
            14 => 
            array (
                'id' => 15,
                'email_template_id' => 3,
                'label' => 'label_1.0',
                'value' => 'Transaction Invoice',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 08:30:02',
                'updated_at' => '2021-01-06 08:30:02',
            ),
            15 => 
            array (
                'id' => 16,
                'email_template_id' => 3,
                'label' => 'label_1.1	',
                'value' => 'Hi',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 08:30:02',
                'updated_at' => '2021-01-06 08:30:02',
            ),
            16 => 
            array (
                'id' => 17,
                'email_template_id' => 3,
                'label' => 'label_1.2',
                'value' => 'Your transaction is completed and payment has been successfully processed. A summary of your transaction is attached below.',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 08:30:02',
                'updated_at' => '2021-01-06 08:30:02',
            ),
            17 => 
            array (
                'id' => 18,
                'email_template_id' => 3,
                'label' => 'label_1.3',
                'value' => 'Cheers',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 08:30:02',
                'updated_at' => '2021-01-06 08:30:02',
            ),
            18 => 
            array (
                'id' => 19,
                'email_template_id' => 4,
                'label' => 'label_1.0',
                'value' => 'Account Password',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 11:34:09',
                'updated_at' => '2021-01-06 11:34:09',
            ),
            19 => 
            array (
                'id' => 20,
                'email_template_id' => 4,
                'label' => 'label_1.1',
                'value' => 'Hi',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 11:34:09',
                'updated_at' => '2021-01-06 11:34:09',
            ),
            20 => 
            array (
                'id' => 21,
                'email_template_id' => 4,
                'label' => 'label_1.2',
                'value' => 'To login your account, please use the following password',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 11:34:09',
                'updated_at' => '2021-01-06 11:34:09',
            ),
            21 => 
            array (
                'id' => 22,
                'email_template_id' => 4,
                'label' => 'label_1.3',
                'value' => 'Do not share this password with anyone',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 11:34:09',
                'updated_at' => '2021-01-06 11:34:09',
            ),
            22 => 
            array (
                'id' => 23,
                'email_template_id' => 4,
                'label' => 'label_1.4',
                'value' => 'takes your account security very seriously',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 11:34:09',
                'updated_at' => '2021-01-06 11:34:09',
            ),
            23 => 
            array (
                'id' => 24,
                'email_template_id' => 4,
                'label' => 'label_1.5',
                'value' => 'will never ask you to disclose your password',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 11:34:09',
                'updated_at' => '2021-01-06 11:34:09',
            ),
            24 => 
            array (
                'id' => 25,
                'email_template_id' => 4,
                'label' => 'label_1.6',
                'value' => 'Cheers',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 11:34:09',
                'updated_at' => '2021-01-06 11:34:09',
            ),
            25 => 
            array (
                'id' => 26,
                'email_template_id' => 5,
                'label' => 'label_1.0',
                'value' => 'Thank You For Contacting Us',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 11:36:23',
                'updated_at' => '2021-01-06 11:36:23',
            ),
            26 => 
            array (
                'id' => 27,
                'email_template_id' => 5,
                'label' => 'label_1.1',
                'value' => 'Hi',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 11:36:23',
                'updated_at' => '2021-01-06 11:36:23',
            ),
            27 => 
            array (
                'id' => 28,
                'email_template_id' => 5,
                'label' => 'label_1.2',
                'value' => 'We have received your inquiry and is currently being reviewed by our Customer Service Team. We will respond to you very soon.',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 11:36:23',
                'updated_at' => '2021-01-06 11:36:23',
            ),
            28 => 
            array (
                'id' => 29,
                'email_template_id' => 5,
                'label' => 'label_1.3',
                'value' => 'Please also refer to our FAQs for useful information.',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 11:36:23',
                'updated_at' => '2021-01-06 11:36:23',
            ),
            29 => 
            array (
                'id' => 30,
                'email_template_id' => 6,
                'label' => 'label_1.0',
                'value' => 'Hi',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 12:06:19',
                'updated_at' => '2021-01-06 12:06:19',
            ),
            30 => 
            array (
                'id' => 31,
                'email_template_id' => 6,
                'label' => 'label_1.1',
                'value' => 'An inquiry has been submitted by',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 12:06:19',
                'updated_at' => '2021-01-06 12:06:19',
            ),
            31 => 
            array (
                'id' => 32,
                'email_template_id' => 6,
                'label' => 'label_1.2',
                'value' => 'with following details',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 12:06:19',
                'updated_at' => '2021-01-06 12:06:19',
            ),
            32 => 
            array (
                'id' => 33,
                'email_template_id' => 6,
                'label' => 'label_1.3',
                'value' => 'Date',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 12:06:19',
                'updated_at' => '2021-01-06 12:06:19',
            ),
            33 => 
            array (
                'id' => 34,
                'email_template_id' => 6,
                'label' => 'label_1.4',
                'value' => 'Name',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 12:06:19',
                'updated_at' => '2021-01-06 12:06:19',
            ),
            34 => 
            array (
                'id' => 35,
                'email_template_id' => 6,
                'label' => 'label_1.5',
                'value' => 'Email',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 12:06:19',
                'updated_at' => '2021-01-06 12:06:19',
            ),
            35 => 
            array (
                'id' => 36,
                'email_template_id' => 6,
                'label' => 'label_1.6',
                'value' => 'Phone',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 12:06:19',
                'updated_at' => '2021-01-06 12:06:19',
            ),
            36 => 
            array (
                'id' => 37,
                'email_template_id' => 6,
                'label' => 'label_1.7',
                'value' => 'Subject',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 12:06:19',
                'updated_at' => '2021-01-06 12:06:19',
            ),
            37 => 
            array (
                'id' => 38,
                'email_template_id' => 6,
                'label' => 'label_1.8',
                'value' => 'Message',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 12:06:19',
                'updated_at' => '2021-01-06 12:06:19',
            ),
            38 => 
            array (
                'id' => 39,
                'email_template_id' => 7,
                'label' => 'label_1.0',
                'value' => 'Reset Two Factor Authentication',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 12:11:16',
                'updated_at' => '2021-01-06 12:11:16',
            ),
            39 => 
            array (
                'id' => 40,
                'email_template_id' => 7,
                'label' => 'label_1.1',
                'value' => 'Hi',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 12:11:16',
                'updated_at' => '2021-01-06 12:11:16',
            ),
            40 => 
            array (
                'id' => 41,
                'email_template_id' => 7,
                'label' => 'label_1.2',
                'value' => 'Please open Google Authenticator App and reset your',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 12:11:16',
                'updated_at' => '2021-01-06 12:11:16',
            ),
            41 => 
            array (
                'id' => 42,
                'email_template_id' => 7,
                'label' => 'label_1.3',
                'value' => 'by adding below details',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 12:11:16',
                'updated_at' => '2021-01-06 12:11:16',
            ),
            42 => 
            array (
                'id' => 43,
                'email_template_id' => 7,
                'label' => 'label_1.4',
                'value' => 'App Name',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 12:11:16',
                'updated_at' => '2021-01-06 12:11:16',
            ),
            43 => 
            array (
                'id' => 44,
                'email_template_id' => 7,
                'label' => 'label_1.5',
                'value' => 'Email',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 12:11:16',
                'updated_at' => '2021-01-06 12:11:16',
            ),
            44 => 
            array (
                'id' => 45,
                'email_template_id' => 7,
                'label' => 'label_1.6',
                'value' => 'Secret Key',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 12:11:16',
                'updated_at' => '2021-01-06 12:11:16',
            ),
            45 => 
            array (
                'id' => 46,
                'email_template_id' => 7,
                'label' => 'label_1.7',
                'value' => 'Cheers',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 12:11:16',
                'updated_at' => '2021-01-06 12:11:16',
            ),
            46 => 
            array (
                'id' => 47,
                'email_template_id' => 5,
                'label' => 'label_1.4',
                'value' => 'Cheers',
                'status' => 1,
                'deleted_at' => NULL,
                'created_at' => '2021-01-06 12:56:48',
                'updated_at' => '2021-01-06 12:56:48',
            ),
        ));
        
        
    }
}