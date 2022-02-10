<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use App\Models\EmailTemplate;
use App\Models\PasswordReset;
use App\Http\Resources\EmailTemplateResource;
use Hash;
use Hashids;

class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function sendResetLink(Request $request)
    {
        $lang = $request->has('lang') && $request->lang != 'en' ? $request->lang : 'en';
        $lang_file = public_path('i18n/translations/'.$lang.'.json');
        $lang_arr = json_decode(file_get_contents($lang_file),true);

        $messages = [
            'email.required' => $lang_arr['validation_messages']['field_is_required'],
            'email.email' => $lang_arr['validation_messages']['field_must_valid_email'],
            'email.max' => $lang_arr['validation_messages']['field_not_greater_than_100_characters'],
        ];

        $validation_rules = array(
            'email' => 'required|string|email|max:100',
        );

        if($request->has('platform') && $request->platform == 'mobile')
        {
            $validator = Validator::make($request->all(),$validation_rules,$messages);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'message' => $validator->errors()]);
            }
        }
        else
        {
            $request->validate($validation_rules,$messages);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user)
        {
            return response()->json([
                'status' => 0,
                'message' => $lang_arr['alert_messages']['password_send_reset_link_error']
            ]);
        }

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => \Str::random(60)
            ]
        );

        if ($user && $passwordReset)
        {
            $name = $user->name;
            $email = $user->email;
            $reset_link = url('/resetPassword/find/'.$passwordReset->token);

            $email_template = EmailTemplate::where('type','reset_password')->first();
            $email_template = transformEmailTemplateModel($email_template,$lang);
            
            $subject = $email_template['subject'];
            $content = $email_template['content'];

            $search = array("{{name}}","{{link}}","{{app_name}}");
            $replace = array($name,$reset_link,env('APP_NAME'));
            $content  = str_replace($search,$replace,$content);

            sendEmail($email, $subject, $content, '', '', $lang);

            return response()->json([
                'status' => 1,
                'message' => $lang_arr['alert_messages']['password_send_reset_link_success']
            ]);
        }
    }
    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function validateResetToken($token, Request $request)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();

        $lang = $request->has('lang') && $request->lang != 'en' ? $request->lang : 'en';
        $lang_file = public_path('i18n/translations/'.$lang.'.json');
        $lang_arr = json_decode(file_get_contents($lang_file),true);
        
        if (!$passwordReset)
        {
            return response()->json([
                'status' => 0,
                'message' => $lang_arr['alert_messages']['password_send_reset_link_error']
            ]);
        }

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(60)->isPast()) 
        {
            $passwordReset->delete();
            return response()->json([
                'status' => 0,
                'message' => $lang_arr['alert_messages']['password_send_reset_link_error']
            ]);
        }

        return response()->json([
            'data' => $passwordReset,
            'status' => 1,
            'message' => 'This password reset token is valid.'
        ]);
    }
     /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        $lang = $request->has('lang') && $request->lang != 'en' ? $request->lang : 'en';
        $lang_file = public_path('i18n/translations/'.$lang.'.json');
        $lang_arr = json_decode(file_get_contents($lang_file),true);

        $messages = [
            'email.required' => $lang_arr['validation_messages']['field_is_required'],
            'email.email' => $lang_arr['validation_messages']['field_must_valid_email'],
            'email.max' => $lang_arr['validation_messages']['field_not_greater_than_100_characters'],
            'password.required' => $lang_arr['validation_messages']['field_is_required'],
            'password.max' => $lang_arr['validation_messages']['field_not_greater_than_30_characters'],
            'password.min' => $lang_arr['validation_messages']['field_not_less_than_8_characters'],
            'password.regex' => $lang_arr['password_field_title'],
            'password_confirmation.same' => $lang_arr['validation_messages']['password_confirmation_must_match'],
        ];

        $request->validate([
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|min:8|max:30|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/',
            'password_confirmation' => 'same:password',
            'token' => 'required|string'
        ],$messages);

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();

        if(!$passwordReset)
        {
            return response()->json([
                'status' => 0,
                'message' => $lang_arr['alert_messages']['password_send_reset_link_error']
            ]);
        }
            
        $user = User::where('email', $passwordReset->email)->first();
        if (!$user)
        {
            return response()->json([
                'status' => 0,
                'message' => $lang_arr['alert_messages']['password_send_reset_link_error']
            ]);
        }
            
        $user->original_password = $request->password;
        $user->password = Hash::make($request->password);
        $user->save();
        $passwordReset->delete();

        return response()->json([
            'data' => $user,
            'status' => 1,
            'message' => $lang_arr['alert_messages']['password_reset_success']
        ]);
    }
}