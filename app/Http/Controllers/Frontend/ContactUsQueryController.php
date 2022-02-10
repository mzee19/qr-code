<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\ContactUsQuery;
use App\Models\EmailTemplate;
use Session;

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
        $lang = \App::getLocale();
         $messages = [
            'name.required' => __('The name field is required.'),
            'name.max' => __('The name may not be greater than 250 characters.'),
            'email.required' => __('The email field is required.'),
            'email.max' => __('The email  may not be greater than 100 characters.'),
            'email.email' => __('The email must be a valid email address.'),
            'phone.required' => __('The phone field is required.'),
            'phone.max' => __('The phone may not be greater than 50 characters.'),
            'subject.required' => __('The subject field is required.'),
            'subject.max' => __('The subject may not be greater than 250 characters.'),
            'message.required' => __('The message field is required.'),
             'g-recaptcha-response.recaptcha' => __('The captcha field is required.')
         ];

        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:250',
            'email'         => 'required|string|email|max:100',
            'phone'         => 'required|string|max:50',
            'subject'       => 'required|string|max:250',
            'message'       => 'required|string',
            'g-recaptcha-response'       => 'required|recaptcha',
        ],$messages);

        if ($validator->fails())
        {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

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

        Session::flash('flash_success', __('Your query has been submitted successfully.'));
        return redirect()->back();
    }
}
