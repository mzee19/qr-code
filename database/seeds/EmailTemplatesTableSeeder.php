<?php

use Illuminate\Database\Seeder;

class EmailTemplatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('email_templates')->delete();
        
        \DB::table('email_templates')->insert(array (
            0 => 
            array (
                'id' => 1,
                'type' => 'reset_password',
                'subject' => 'Reset Password',
                'content' => '<div style=" padding:10px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<h3 style=" font-size: 22px; font-family: Segoe, \'Segoe UI\', \'sans-serif\'; margin-top: 20px;color: #000000;">{{label_1.0}}</h3>
<h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
{{label_1.1}} {{name}}, 
</h3>                
<h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
{{label_1.2}}
</h3>
<div style="margin: 40px 0; text-align: center;">
<a href="{{link}}" target="_blank" style="display: inline-block;padding: 12px 15px;font-family: \'Source Sans Pro\', Helvetica, Arial, sans-serif;font-size: 16px;color: #ffffff;text-decoration: none;border-radius: 6px;width: 130px;background-color:#2345a4;text-align: center;">{{label_1.3}}</a>
</div>
<p style="font-size:17px;line-height: 25px;font-weight: normal;margin-top: 40px;margin-bottom: 40px;color: #555;">{{label_1.4}}:{{link}}</p>
</div>

<div style=" padding:30px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<div style="font-size: 15px; color: #555;">
<p style="font-size: 15px; font-style: italic; font-weight: 600; margin-bottom: 0;">{{label_1.5}},</p>
{{app_name}}
</div>
</div>',
                'info' => '{"name":"User full name","link":"Link for reset password","app_name":"Website name"}',
                'status' => 1,
                'created_at' => '2019-11-13 17:38:27',
                'updated_at' => '2021-01-06 09:43:28',
            ),
            1 => 
            array (
                'id' => 2,
                'type' => 'sign_up_confirmation',
                'subject' => 'Sign up Confirmation',
                'content' => '<div style=" padding:10px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<h3 style=" font-size: 22px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\'; margin-top: 20px;">{{label_1.0}} {{app_name}}</h3>
<h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
{{label_1.1}}  {{name}}, 
</h3>                
<h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
{{label_1.2}}{{app_name}} {{label_1.3}}.
</h3>
<div style="margin: 40px 0; text-align:center;">
<a href="{{link}}" target="_blank" style="display: inline-block;padding: 12px 15px;font-family: \'Source Sans Pro\', Helvetica, Arial, sans-serif;font-size: 16px;color: #ffffff;text-decoration: none;border-radius: 6px;width: 150px;background-color:#2345a4;text-align: center;">{{label_1.4}}</a>
</div>
<p style="font-size:17px;line-height: 25px;font-weight: normal;margin-top: 40px;margin-bottom: 40px;color: #555;">{{label_1.5}}: <a href="{{link}}" target="_blank">{{link}}</a></p>
<p style="font-size:17px;line-height: 25px;font-weight: normal;color: #555;">{{label_1.6}}.</p>
</div>

<div style=" padding:30px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<div style="margin-top: 30px;  font-size: 15px; color: #555;">
<p style="font-size: 15px; font-style: italic; font-weight: 600; margin-bottom: 0;">{{label_1.7}},</p>
{{app_name}}
</div>
</div>',
                'info' => '{"name":"User full name","link":"Link for Verify Email Address","app_name":"Website name"}',
                'status' => 1,
                'created_at' => '2019-12-03 18:28:21',
                'updated_at' => '2021-01-06 08:10:36',
            ),
            2 => 
            array (
                'id' => 3,
                'type' => 'payment_success',
                'subject' => 'Payment Success',
                'content' => '<div style=" padding:10px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<h3 style=" font-size: 22px; font-family: Segoe, \'Segoe UI\', \'sans-serif\'; margin-top: 20px;color: #000000;">{{label_1.0}}</h3>
<h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
{{label_1.1}} {{name}}, 
</h3>
<p style="font-size:17px;line-height: 25px;font-weight: normal;margin-top: 40px;margin-bottom: 40px;color: #555;">{{label_1.2}}</p>
</div>

<div style=" padding:30px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<div style="font-size: 15px; color: #555;">
<p style="font-size: 15px; font-style: italic; font-weight: 600; margin-bottom: 0;">{{label_1.3}},</p>
{{app_name}}
</div>
</div>',
                'info' => '{"name":"User full name","app_name":"Website name"}',
                'status' => 1,
                'created_at' => '2020-01-13 13:22:23',
                'updated_at' => '2021-01-06 10:37:23',
            ),
            3 => 
            array (
                'id' => 4,
                'type' => 'send_password',
                'subject' => 'Account Password',
                'content' => '<div style=" padding:10px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<h3 style=" font-size: 22px; font-family: Segoe, \'Segoe UI\', \'sans-serif\'; margin-top: 20px;color: #000000;">{{label_1.0}}</h3><h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
{{label_1.1}} {{name}}, 
</h3>
<p style="font-size: 17px; line-height: 25px; margin-top: 40px; margin-bottom: 40px; color: rgb(85, 85, 85);"><span style="font-weight: normal;">{{label_1.2}}: </span><b>{{password}}</b></p>
<p style="font-size: 17px; line-height: 25px; margin-top: 40px; margin-bottom: 40px; color: rgb(85, 85, 85);"><span style="font-weight: normal;">{{label_1.3}}. {{app_name}} {{label_1.4}}. {{app_name}} {{label_1.5}}.</span></p>
</div>

<div style=" padding:30px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<div style="font-size: 15px; color: #555;">
<p style="font-size: 15px; font-style: italic; font-weight: 600; margin-bottom: 0;">{{label_1.6}},</p>
{{app_name}}
</div>
</div>',
                'info' => '{"name":"User full name","app_name":"Website name","password":"Account Password"}',
                'status' => 1,
                'created_at' => '2020-02-28 07:34:55',
                'updated_at' => '2021-01-06 12:54:01',
            ),
            4 => 
            array (
                'id' => 5,
                'type' => 'contact_us_inquiry_received',
                'subject' => 'Contact Us Inquiry Received',
                'content' => '<div style=" padding:10px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<h3 style=" font-size: 22px; font-family: Segoe, \'Segoe UI\', \'sans-serif\'; margin-top: 20px;color: #000000;">{{label_1.0}}</h3>
<h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
{{label_1.1}} {{name}},</h3>
<p style="font-size:16px;line-height: 25px;font-weight: normal;margin-top: 40px;margin-bottom: 40px;color: #555;">{{label_1.2}}</p>
<p style="font-size:16px;line-height: 25px;font-weight: normal;color: #555;">{{label_1.3}}</p>
</div>

<div style=" padding:30px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<div style="margin-top: 30px;  font-size: 15px; color: #555;">
<p style="font-size: 15px; font-style: italic; font-weight: 600; margin-bottom: 0;">{{label_1.4}},</p>
{{app_name}}
</div>
</div>',
                'info' => '{"name":"User full name","app_name":"Website name"}',
                'status' => 1,
                'created_at' => '2020-05-13 07:59:59',
                'updated_at' => '2021-04-12 12:21:37',
            ),
            5 => 
            array (
                'id' => 6,
                'type' => 'contact_us_inquiry_submitted',
                'subject' => 'Contact Us Inquiry Submitted',
                'content' => '<div style=" padding:10px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
Hi Admin,
</h3>                
<h3 style="font-size:18px;line-height: 25px;font-weight: normal;margin-bottom: 0;">
An inquiry has been submitted by {{name}} with the following details:
</h3>
</div>

<div style=" padding:30px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<div style="font-size:18px;   line-height: 25px;">
<table style="border: 1px solid #ddd;width: 100%;">
<tbody>
<tr>
<th style="padding: 10px 15px; border-bottom: 1px solid #ddd; text-align: left;">Date :</th>
<td style="padding: 10px 15px; border-bottom: 1px solid #ddd;">{{date}}</td>
</tr>
<tr>
<th style="padding: 10px 15px; border-bottom: 1px solid #ddd; text-align: left;">Name :</th>
<td style="padding: 10px 15px; border-bottom: 1px solid #ddd;">{{fullname}}</td>
</tr>
<tr>
<th style="padding: 10px 15px; border-bottom: 1px solid #ddd; text-align: left;">Email :</th>
<td style="padding: 10px 15px; border-bottom: 1px solid #ddd;">{{email}}</td>
</tr>
<tr>
<th style="padding: 10px 15px; border-bottom: 1px solid #ddd; text-align: left;">Phone :</th>
<td style="padding: 10px 15px; border-bottom: 1px solid #ddd;">{{phone}}</td>
</tr>
<tr>
<th style="padding: 10px 15px; border-bottom: 1px solid #ddd; text-align: left;">Subject :</th>
<td style="padding: 10px 15px; border-bottom: 1px solid #ddd;">{{subject}}</td>
</tr>
<tr>
<th colspan="2" style="padding: 10px 15px; border-bottom: 1px solid #ddd; text-align: left;">Message :</th>
</tr>
<tr>
<td colspan="2" style="padding: 10px 15px; border-bottom: 1px solid #ddd;">{{message}}</td>
</tr>
</tbody></table>

</div>
<div style="margin-top: 30px;  font-size: 15px; color: #555;">
<p style="font-size: 15px; font-style: italic; font-weight: 600; margin-bottom: 0;">Cheers,</p>
{{app_name}}
</div>
</div>',
                'info' => '{"name":"User full name","app_name":"Website name","date":"Submission Date","fullname":"FullName","email":"Email Address","phone":"Contact Number","subject":"Subject","message":"Message"}',
                'status' => 1,
                'created_at' => '2020-05-13 08:07:17',
                'updated_at' => '2021-01-06 08:11:25',
            ),
            6 => 
            array (
                'id' => 7,
                'type' => 'reset_two_factor_authentication',
            'subject' => 'Reset Two Factor Authentication (2FA)',
                'content' => '<div style=" padding:10px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<h3 style="font-size:18px;line-height: 25px;font-weight: normal;">
</h3><h3 style="font-family: Segoe, &quot;Segoe UI&quot;, sans-serif; color: rgb(0, 0, 0); font-size: 22px;">{{label_1.0}} (2FA)</h3><h3 style="font-size:18px;line-height: 25px;font-weight: normal;">{{label_1.1}} {{name}},
</h3>                
<h3 style="font-size:18px;line-height: 25px;font-weight: normal;margin-bottom: 0;">
{{label_1.2}} 2FA {{label_1.3}}:
</h3>
</div>

<div style=" padding:30px 30px 10px;  font-family: Segoe, \'Segoe UI\', \'sans-serif\';">
<div style="font-size:18px;   line-height: 25px;">
<table style="border: 1px solid #ddd;width: 100%;">
<tbody>
<tr>
<th style="padding: 10px 15px; border-bottom: 1px solid #ddd; text-align: left;">{{label_1.4}} :</th>
<td style="padding: 10px 15px; border-bottom: 1px solid #ddd;">{{app_name}}</td>
</tr>
<tr>
<th style="padding: 10px 15px; border-bottom: 1px solid #ddd; text-align: left;">{{label_1.5}} :</th>
<td style="padding: 10px 15px; border-bottom: 1px solid #ddd;">{{email}}</td>
</tr>
<tr>
<th style="padding: 10px 15px; border-bottom: 1px solid #ddd; text-align: left;">{{label_1.6}} :</th>
<td style="padding: 10px 15px; border-bottom: 1px solid #ddd;">{{secret_key}}</td>
</tr>
</tbody></table>

</div>
<div style="margin-top: 30px;  font-size: 15px; color: #555;">
<p style="font-size: 15px; font-style: italic; font-weight: 600; margin-bottom: 0;">{{label_1.7}},</p>
{{app_name}}
</div>
</div>',
                'info' => '{"name":"User full name","app_name":"Website name","email":"User email","secret_key":"Google Authenticator Secret Key For Reset Two Factor Authentication"}',
                'status' => 1,
                'created_at' => '2020-10-05 07:47:13',
                'updated_at' => '2021-01-06 13:16:24',
            ),
        ));
        
        
    }
}