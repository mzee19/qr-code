<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\EmailTemplate;

class OtpAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function setupTwoFactorAuthentication(Request $request)
    {
        $lang = $request->has('lang') && $request->lang != 'en' ? $request->lang : 'en';
        $lang_file = public_path('i18n/translations/'.$lang.'.json');
        $lang_arr = json_decode(file_get_contents($lang_file),true);

        // Get User
        $user = auth()->user();

        // Initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');

        // Add the secret key to the user data
        $otp_auth_secret_key = $google2fa->generateSecretKey();

        // Generate the QR image. This is the image the user will scan with their app
        // to set up two factor authentication
        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $otp_auth_secret_key
        );

        $user->update([
            'otp_auth_secret_key' => $otp_auth_secret_key,
            'otp_auth_qr_image' => $QR_Image
        ]);

        return response()->json([
            'data' => array('otp_auth_qr_image' => $QR_Image),
            'status' => 1,
            'message' => $lang_arr['alert_messages']['two_fa_image']
        ]);
    }

    public function enableTwoFactorAuthentication(Request $request)
    {
        $lang = $request->has('lang') && $request->lang != 'en' ? $request->lang : 'en';
        $lang_file = public_path('i18n/translations/'.$lang.'.json');
        $lang_arr = json_decode(file_get_contents($lang_file),true);

        $user = auth()->user();

        $user->update([
            'otp_auth_status' => 1
        ]);

        return response()->json([
            'status' => 1,
            'message' => $lang_arr['alert_messages']['enable_twofa_success']
        ]);
    }

    public function disableTwoFactorAuthentication(Request $request)
    {
        $lang = $request->has('lang') && $request->lang != 'en' ? $request->lang : 'en';
        $lang_file = public_path('i18n/translations/'.$lang.'.json');
        $lang_arr = json_decode(file_get_contents($lang_file),true);

        $user = auth()->user();

        $user->update([
            'otp_auth_secret_key' => Null,
            'otp_auth_qr_image' => Null,
            'otp_auth_status' => 0
        ]);

        return response()->json([
            'status' => 1,
            'message' => $lang_arr['alert_messages']['disable_twofa_success']
        ]);
    }

    public function GetTwoFactorAuthenticationData(Request $request)
    {
        $lang = $request->has('lang') && $request->lang != 'en' ? $request->lang : 'en';
        $lang_file = public_path('i18n/translations/'.$lang.'.json');
        $lang_arr = json_decode(file_get_contents($lang_file),true);

        $user = auth()->user();

        $data = array(
            'app_name' => config('app.name'),
            'email' => $user->email,
            'otp_auth_status' => $user->otp_auth_status,
            'otp_auth_secret_key' => $user->otp_auth_secret_key,
            'otp_auth_qr_image' => $user->otp_auth_qr_image
        );

        return response()->json([
            'data' => $data,
            'status' => 1,
            'message' => $lang_arr['alert_messages']['two_fa_authentication_data']
        ]);
    }

    public function verifyTwoFactorAuthentication(Request $request)
    {
        $lang = $request->has('lang') && $request->lang != 'en' ? $request->lang : 'en';
        $lang_file = public_path('i18n/translations/'.$lang.'.json');
        $lang_arr = json_decode(file_get_contents($lang_file),true);
        
        $messages = [
            'one_time_password.required' => $lang_arr['validation_messages']['field_is_required'],
        ];

        $validatedData = $request->validate([
            'one_time_password' => 'required|string',
        ],$messages);

        // Get User
        $user = auth()->user();

        // Initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');

        // Add the secret key to the user data
        $response = $google2fa->verifyKey($user->otp_auth_secret_key,$request->one_time_password);

        if($response)
        {
            return response()->json([
                'status' => 1,
                'message' => $lang_arr['alert_messages']['two_fa_verified_successfully']
            ]);
        }
        else
        {
            return response()->json([
                'status' => 0,
                'message' => $lang_arr['alert_messages']['two_fa_authentication_incorrect']
            ]);
        }
    }

    public function resetTwoFactorAuthentication(Request $request)
    {
        $lang = $request->has('lang') && $request->lang != 'en' ? $request->lang : 'en';
        $lang_file = public_path('i18n/translations/'.$lang.'.json');
        $lang_arr = json_decode(file_get_contents($lang_file),true);

        // Get User
        $user = auth()->user();

        // Initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');

        // Add the secret key to the user data
        $otp_auth_secret_key = $google2fa->generateSecretKey();

        // Generate the QR image. This is the image the user will scan with their app
        // to set up two factor authentication
        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $otp_auth_secret_key
        );

        $user->update([
            'otp_auth_secret_key' => $otp_auth_secret_key,
            'otp_auth_qr_image' => $QR_Image
        ]);

        $name = auth()->user()->name;
        $email = auth()->user()->email;

        // ********************* //
        // Send email to Support //
        // ********************* //

        $email_template = EmailTemplate::where('type','reset_two_factor_authentication')->first();
        $email_template = transformEmailTemplateModel($email_template,$lang);
            
        $subject = $email_template['subject'];
        $content = $email_template['content'];

        $search = array("{{name}}","{{email}}","{{app_name}}","{{secret_key}}");
        $replace = array($name,$email,env('APP_NAME'),$otp_auth_secret_key);
        $content  = str_replace($search,$replace,$content);

        sendEmail($email, $subject, $content, '', '', $lang);

        return response()->json([
            'status' => 1,
            'message' => $lang_arr['alert_messages']['reset_twofa_success']
        ]);
    }
}