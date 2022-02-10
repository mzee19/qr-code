<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\ContactUsQuery;
use App\Models\EmailTemplate;
use App\Http\Resources\EmailTemplateResource;

class ContactUsQueryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(Request $request)
    {
        $lang = $request->has('lang') && $request->lang != 'en' ? $request->lang : 'en';
        $lang_file = public_path('i18n/translations/'.$lang.'.json');
        $lang_arr = json_decode(file_get_contents($lang_file),true);

        $messages = [
            'name.required' => $lang_arr['validation_messages']['field_is_required'],
            'name.max' => $lang_arr['validation_messages']['field_not_greater_than_250_characters'],
            'email.required' => $lang_arr['validation_messages']['field_is_required'],
            'email.max' => $lang_arr['validation_messages']['field_not_greater_than_100_characters'],
            'email.email' => $lang_arr['validation_messages']['field_must_valid_email'],
            'phone.required' => $lang_arr['validation_messages']['field_is_required'],
            'phone.max' => $lang_arr['validation_messages']['field_not_greater_than_50_characters'],
            'subject.required' => $lang_arr['validation_messages']['field_is_required'],
            'subject.max' => $lang_arr['validation_messages']['field_not_greater_than_250_characters'],
            'message.required' => $lang_arr['validation_messages']['field_is_required'],
            'message.max' => $lang_arr['validation_messages']['field_not_greater_than_1000_characters'],
        ];

        $request->validate([
            'name'          => 'required|string|max:250',
            'email'         => 'required|string|email|max:100',
            'phone'         => 'required|string|max:50',
            'subject'       => 'required|string|max:250',
            'message'       => 'required|string',
        ],$messages);

        $contact_us_query = ContactUsQuery::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'subject'       => $request->subject,
            'message'       => $request->message,
        ]);

        //*****
        //***** Send Email To User
        //*****
        
        $name = $request->name;
        $email = $request->email;

        $email_template = EmailTemplate::where('type','contact_us_inquiry_received')->first();
        $email_template = transformEmailTemplateModel($email_template,$lang);
            
        $subject = $email_template['subject'];
        $content = $email_template['content'];

        $search = array("{{name}}","{{app_name}}");
        $replace = array($name,env('APP_NAME'));
        $content  = str_replace($search,$replace,$content);

        sendEmail($email, $subject, $content, '', '', $lang);

        //*****
        //***** Send Email To Admin
        //*****

        $email = settingValue('contact_email');

        $email_template = EmailTemplate::where('type','contact_us_inquiry_submitted')->first();
        $subject = $email_template->subject;
        $content = $email_template->content;

        $search = array("{{name}}","{{app_name}}","{{date}}","{{fullname}}","{{email}}","{{phone}}","{{subject}}","{{message}}");
        $replace = array($name,env('APP_NAME'),$contact_us_query->created_at,$contact_us_query->name,$contact_us_query->email,$contact_us_query->phone,$contact_us_query->subject,$contact_us_query->message);
        $content  = str_replace($search,$replace,$content);

        sendEmail($email, $subject, $content);

        return response()->json([
            'status' => 1,
            'message' => $lang_arr['alert_messages']['contact_us_query_success']
        ]);
    }
}