<?php

namespace App\Http\Controllers\Api;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\User;
use App\Models\Package;
use App\Models\PackageSubscription;
use App\Models\EmailTemplate;
use App\Models\AccountSetting;
use App\Http\Resources\EmailTemplateResource;
use Carbon\Carbon;
use Hashids;
use Storage;
use Hash;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register', 'login', 'verifyAccount', 'resendVerificationEmail']]);
    }

    public function register(Request $request)
    {
        $lang = $request->has('lang') && $request->lang != 'en' ? $request->lang : 'en';
        \App::setLocale($lang);

        $messages = [
            'name.required' => __('The name field is required.'),
            'name.max' => __('The name may not be greater than 100 characters.'),
            'email.required' => __('The email field is required.'),
            'email.max' => __('The email field may not be greater than 255 characters.'),
            'email.email' => __('The email must be a valid email address.'),
            'email.unique' => __('The email has already been taken.'),
            'password.required' => __('The password field is required.'),
            'password.max' => __('This field may not be greater than 30 characters.'),
            'password.min' => __('The password must be at least 8 characters.'),
//            'password.regex' => __('Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters'),
            'password_confirmation.same' => __('The password confirmation does not match.'),
//            'country_id.required' => __('The country id field is required.'),
        ];

        $validation_rules = array(
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
//            'country_id' => 'required',
            'password' => ' required|string|min:8|max:30',
            'password_confirmation' => 'same:password',
        );

        $validator = Validator::make($request->all(),$validation_rules,$messages);

        if ($validator->fails())
        {
            return response()->json([
                'status' => 0,
                'messages' => $validator->errors(),
            ]);
        }

        $platform = 1;
        if ($request->has('platform')) {
            $platform = ($request->platform == 'mobile') ? 2 : $request->platform;
        }

        $user = User::create([
            'name' => $request->input('name'),
            'username' => $request->input('email'),
            'email' => $request->input('email'),
            'country_id' => isset($request->country_id) ? $request->input('country_id') : 81,
            'password' => Hash::make($request->input('password')),
            'original_password' => $request->input('password'),
            'language' => $lang,
            'timezone' => $request->has('timezone') ? $request->timezone : 'UTC',
            'last_login' => Carbon::now('UTC')->timestamp,
            'status' => 2,
            'platform' => $platform,
            'package_recurring_flag' => 0,
        ]);

        if ($user) {
            if($request->has('subscribeToMinPricePlan') && $request->subscribeToMinPricePlan == 1)
            {
                $package = Package::find(3); // Lite Package
                $end_date = Carbon::now('UTC')->addMonth()->timestamp;
                $on_trial = 0;
                $type = 1;
            }
            else
            {
                $package = Package::where(['id' => 1, 'status' => 1])->first(); // Trial Package
                $end_date = Carbon::now('UTC')->addDays(settingValue('number_of_days'))->timestamp;
                $on_trial = 1;
                $type = Null;

                if(empty($package) || (!empty($package) && settingValue('number_of_days') == 0)) // Trial is not active
                {
                    $package = Package::find(2); // Free Package
                    $end_date = Null;
                    $on_trial = 0;
                }
            }

            $packageLinkedFeatures = $package->linkedFeatures->pluck('count', 'feature_id')->toArray();

            $packageSubscription = PackageSubscription::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'price' => 0,
                'features' => empty($package->linkedFeatures) ? '' : json_encode($packageLinkedFeatures),
                'description' => $package->description,
                'type' => $type,
                'start_date' => Carbon::now('UTC')->timestamp,
                'end_date' => $end_date,
                'payment_option' => 1,
                'is_active' => 1
            ]);

            $user->update([
                'package_id' => $package->id,
                'package_subscription_id' => $packageSubscription->id,
                'on_trial' => $on_trial,
                'dynamic_qr_codes' => isset($packageLinkedFeatures[1]) ? $packageLinkedFeatures[1] : null,
                'static_qr_codes' => isset($packageLinkedFeatures[2]) ? $packageLinkedFeatures[2] : null,
                'qr_code_scans' => isset($packageLinkedFeatures[3]) ? $packageLinkedFeatures[3] : null,
                'bulk_import_limit' => isset($packageLinkedFeatures[13]) ? $packageLinkedFeatures[13] : null,
            ]);

            if($request->has('voucher') && !empty($request->voucher))
            {
                $user->update([
                    'voucher' => $request->voucher,
                    'is_voucher_redeemed' => 0,
                ]);
            }

            if($request->has('subscribeToMinPricePlan') && $request->subscribeToMinPricePlan == 1) {
                $data['subscription_desc'] = $package->title . ' Package (Monthly Subscription)';

                $payment = Payment::create([
                    'user_id' => $user->id,
                    'subscription_id' => $packageSubscription->id,
                    'item' => $package->title,
                    'payment_method' => Config::get('constants.payment_methods')['VOUCHER_PROMOTION'],
                    'amount' => 0,
                    'vat_percentage' => 0,
                    'vat_amount' => 0,
                    'voucher' => $request->voucher,
                    'reseller' => $request->reseller,
                    'discount_percentage' => 0,
                    'discount_amount' => 0,
                    'total_amount' => 0,
                    'payload' => json_encode($data),
                    'lang' => $lang,
                    'timestamp' => Carbon::now('UTC')->timestamp,
                ]);

                $user->update([
                    'voucher' => '',
                    'payment_method' => Config::get('constants.payment_methods')['VOUCHER_PROMOTION'],
                    'payment_id' => $payment->id,
                    'status' => 1,
                    'is_approved' => 1,
                ]);

                return response()->json([
                    'data' => $user->makeHidden(['subscription','original_password']),
                    'status' => 1,
                    'message' => __('Your account has been verified successfully.')
                ]);
            } else {

                // ************************* //
                // Send Verify Link To User
                // ************************* //

                $name = $user->name;
                $email = $user->email;
                $link = url('/verify-account/' . Hashids::encode($user->id));

                $email_template = EmailTemplate::where('type', 'sign_up_confirmation')->first();
                $email_template = transformEmailTemplateModel($email_template, $lang);

                $subject = $email_template['subject'];
                $content = $email_template['content'];

                $search = array("{{name}}", "{{link}}", "{{app_name}}");
                $replace = array($name, $link, env('APP_NAME'));
                $content = str_replace($search, $replace, $content);

                sendEmail($email, $subject, $content, '', '', $lang);

                return response()->json([
                    'status' => 1,
                    'message' => __('A verification link has been sent to your email account. Check your spam folder in case the email incorrectly identified.')
                ]);
            }
        } else {
            return response()->json([
                'status' => 0,
                'message' => __('Oops something went wrong. Unable to create your account. Please try again.')
            ]);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $lang = $request->has('lang') && $request->lang != 'en' ? $request->lang : 'en';
        $lang_file = public_path('i18n/translations/' . $lang . '.json');
        $lang_arr = json_decode(file_get_contents($lang_file), true);

        $messages = [
            'email.required' => $lang_arr['validation_messages']['field_is_required'],
            'email.email' => $lang_arr['validation_messages']['field_must_valid_email'],
            'password.required' => $lang_arr['validation_messages']['field_is_required'],
        ];

        $validation_rules = array(
            'email' => 'required|string|email',
            'password' => 'required|string'
        );

        if ($request->has('platform') && $request->platform == 'mobile') {
            $validator = Validator::make($request->all(), $validation_rules, $messages);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'message' => $validator->errors()]);
            }
        } else {
            $validatedData = $request->validate($validation_rules, $messages);
        }

        $credentials = ['email' => $request->email, 'password' => $request->password];

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['status' => 0, 'message' => $lang_arr['alert_messages']['invalid_email_password']], 200, ['Content-Type' => 'application/json']);
        }

        $user = User::where(['email' => $request->input('email')])->first();

        $is_user_active = true;
        $resend_email_flag = false;
        $message = '';

        switch ($user->status) {
            case 0:
                $message = $lang_arr['alert_messages']['account_disabled_contact_admin'];
                $is_user_active = false;
                break;
            case 2:
                $message = $lang_arr['alert_messages']['verify_email_or_recend_email'];
                $is_user_active = false;
                $resend_email_flag = true;
                break;
            case 3:
                $message = $lang_arr['alert_messages']['account_deleted_contact_admin'];
                $is_user_active = false;
                break;
        }

        if ($is_user_active == false) {
            auth()->logout();
            return response()->json(['resend_email_flag' => $resend_email_flag, 'email' => $request->email, 'status' => 0, 'user_status' => $user->status, 'message' => $message], 200, ['Content-Type' => 'application/json']);
        }

        $is_approved = true;
        $message = '';

        switch ($user->is_approved) {
            case 0:
                $message = $lang_arr['alert_messages']['account_under_review_contact_admin'];
                $is_approved = false;
                break;
            case 2:
                $message = $lang_arr['alert_messages']['account_rejected_contact_admin'];
                $is_approved = false;
                break;
        }

        if ($is_approved == false) {
            auth()->logout();
            return response()->json(['status' => 0, 'user_approved' => $is_approved, 'message' => $message]);
        }

        $user->update([
            'last_login' => Carbon::now('UTC')->timestamp,
            'ip_address' => $_SERVER['REMOTE_ADDR']
        ]);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL(),
            'data' => $user->makeHidden(['subscription', 'original_password']),
            'status' => 1,
            'message' => $lang_arr['alert_messages']['user_logged_success']
        ], 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        return response()->json([
            'data' => auth()->user()->makeHidden(['subscription', 'original_password']),
            'status' => 1,
            'message' => 'Your Profile'
        ], 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        auth()->logout();

        return response()->json(['status' => 1, 'message' => 'Successfully logged out'], 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL(),
            'status' => 1,
            'message' => 'User Token'
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|same:confirm_password',
            'confirm_password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->all()], 400, ['Content-Type' => 'application/json']);
        }

        $user = auth()->user();

        if (Hash::check($request->input('old_password'), $user->password)) {
            $user->update([
                'password' => Hash::make($request->input('new_password')),
                'original_password' => $request->input('new_password')
            ]);

            return response()->json(['status' => 1, 'message' => 'Password has been updated successfully'], 200, ['Content-Type' => 'application/json']);
        } else {
            return response()->json(['status' => 0, 'message' => 'Old password is not correct.'], 400, ['Content-Type' => 'application/json']);
        }
    }

    public function updateProfile(Request $request)
    {
        $lang = $request->has('lang') && $request->lang != 'en' ? $request->lang : 'en';
        $lang_file = public_path('i18n/translations/' . $lang . '.json');
        $lang_arr = json_decode(file_get_contents($lang_file), true);

        $user = auth()->user();

        $messages = [
            'name.required' => $lang_arr['validation_messages']['field_is_required'],
            'name.max' => $lang_arr['validation_messages']['field_not_greater_than_100_characters'],
            'email.required' => $lang_arr['validation_messages']['field_is_required'],
            'email.max' => $lang_arr['validation_messages']['field_not_greater_than_100_characters'],
            'email.email' => $lang_arr['validation_messages']['field_must_valid_email'],
            'email.unique' => $lang_arr['validation_messages']['email_already_exists'],
            'country_id.required' => $lang_arr['validation_messages']['field_is_required']
        ];

        $validation_rules = array(
            'name' => 'required|string|max:100',
            'country_id' => 'required',
            'email' => ['required', 'email', 'string', 'max:100', Rule::unique('users')->ignore($user->id)]
        );

        if ($request->has('platform') && $request->platform == 'mobile') {
            $validator = Validator::make($request->all(), $validation_rules, $messages);

            if ($validator->fails()) {
                return response()->json(['status' => 0, 'message' => $validator->errors()]);
            }
        } else {
            $request->validate($validation_rules, $messages);
        }

        if ($request->has('timezone')) {
            $user->update([
                'timezone' => $request->input('timezone')
            ]);
        }

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'street' => $request->input('street'),
            'city' => $request->input('city'),
            'postcode' => $request->input('postcode'),
            'country_id' => $request->input('country_id')
        ]);

        if (!empty($request->password)) {
            // ******************* //
            // Password Validation //
            // ******************* //

            $messages = [
                'old_password.required' => $lang_arr['validation_messages']['field_is_required'],
                'password.required' => $lang_arr['validation_messages']['field_is_required'],
                'password.max' => $lang_arr['validation_messages']['field_not_greater_than_30_characters'],
                'password.min' => $lang_arr['validation_messages']['field_not_less_than_8_characters'],
                'password.regex' => $lang_arr['password_field_title'],
                'password_confirmation.same' => $lang_arr['validation_messages']['password_confirmation_must_match'],
            ];

            $validation_rules = array(
                'old_password' => 'required|string',
                'password' => 'required|string|min:8|max:30|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/',
                'password_confirmation' => 'same:password',
            );

            if ($request->has('platform') && $request->platform == 'mobile') {
                $validator = Validator::make($request->all(), $validation_rules, $messages);

                if ($validator->fails()) {
                    return response()->json(['status' => 0, 'message' => $validator->errors()]);
                }
            } else {
                $request->validate($validation_rules, $messages);
            }

            if (Hash::check($request->input('old_password'), $user->password)) {
                $user->update([
                    'password' => Hash::make($request->input('password')),
                    'original_password' => $request->input('password')
                ]);
            } else {
                return response()->json(['status' => 0, 'message' => $lang_arr['alert_messages']['old_password_incorrect']], 200, ['Content-Type' => 'application/json']);
            }
        }

        if (!empty($request->files) && $request->hasFile('profile_image')) {
            // *************** //
            // File Validation //
            // *************** //

            $messages = [
                'profile_image.required' => $lang_arr['validation_messages']['field_is_required'],
                'profile_image.image' => $lang_arr['validation_messages']['file_must_be_image'],
                'profile_image.mimes' => $lang_arr['validation_messages']['allowed_image_jpg_jpeg_png_svg']
            ];

            $validation_rules = array(
                'profile_image' => 'required|file|image|mimes:jpg,jpeg,png,svg'
            );

            if ($request->has('platform') && $request->platform == 'mobile') {
                $validator = Validator::make($request->all(), $validation_rules, $messages);

                if ($validator->fails()) {
                    return response()->json(['status' => 0, 'message' => $validator->errors()]);
                }
            } else {
                $request->validate($validation_rules, $messages);
            }

            // *********** //
            // Upload File //
            // *********** //

            $target_path = 'public/users/' . $user->id . '/profile-image';
            $file = $request->file('profile_image');
            $profile_image = 'profile_image-' . uniqid() . '.' . $file->getClientOriginalExtension();

            $old_file = public_path() . '/storage/users/' . $user->id . '/profile-image/' . $user->profile_image;
            if (file_exists($old_file) && !empty($user->profile_image)) {
                Storage::delete($target_path . '/' . $user->profile_image);
            }

            $path = $file->storeAs($target_path, $profile_image);

            $user->update([
                'profile_image' => $profile_image
            ]);
        }

        return response()->json([
            'data' => auth()->user()->makeHidden(['subscription', 'original_password']),
            'status' => 1,
            'message' => $lang_arr['alert_messages']['update_profile_success']
        ], 200, ['Content-Type' => 'application/json']);
    }

    public function deleteAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->all()[0]], 400, ['Content-Type' => 'application/json']);
        }

        if (Hash::check($request->input('password'), auth()->user()->password)) {
            User::destroy(auth()->user()->id);

            return response()->json(['status' => 1, 'message' => 'Your account has been deleted successfully'], 200, ['Content-Type' => 'application/json']);
        } else {
            return response()->json(['status' => 0, 'message' => 'Password is not correct.'], 400, ['Content-Type' => 'application/json']);
        }
    }

    public function verifyAccount($id, Request $request)
    {
        $lang = $request->has('lang') && $request->lang != 'en' ? $request->lang : 'en';
        $lang_file = public_path('i18n/translations/' . $lang . '.json');
        $lang_arr = json_decode(file_get_contents($lang_file), true);

        if (!isset(Hashids::decode($id)[0])) {
            return response()->json([
                'status' => 0,
                'message' => $lang_arr['alert_messages']['verify_account_error']
            ]);
        }

        $user = User::find(Hashids::decode($id)[0]);
        $message = '';

        if ($user) {
            $is_approved = true;
            switch ($user->is_approved) {
                case 0:
                    $message = $lang_arr['alert_messages']['account_under_review_contact_admin'];
                    $is_approved = false;
                    break;
                case 2:
                    $message = $lang_arr['alert_messages']['account_rejected_contact_admin'];
                    $is_approved = false;
                    break;
            }

            $user->update([
                'status' => 1,
            ]);

            if ($is_approved == false) {
                return response()->json(['status' => 0, 'user_approved' => $is_approved, 'message' => $message]);
            }

            $credentials = ['email' => $user->email, 'password' => $user->original_password];
            $token = auth()->attempt($credentials);

            $user->update([
                'last_login' => Carbon::now('UTC')->timestamp,
                'ip_address' => $_SERVER['REMOTE_ADDR']
            ]);

            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL(),
                'data' => $user->makeHidden(['subscription', 'original_password']),
                'status' => 1,
                'message' => $lang_arr['alert_messages']['account_verified_successfully']
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => $lang_arr['alert_messages']['verify_account_error']
            ]);
        }
    }

    public function accountSettings(Request $request)
    {
        return response()->json([
            'data' => auth()->user()->accountSettings,
            'status' => 1,
            'message' => 'Your Account Settings'
        ]);
    }

    public function updateAccountSettings(Request $request)
    {
        $request->validate([
            'address' => 'required',
            'card_holder_name' => 'required|max:250',
            'card_brand' => 'required|max:100',
            'card_number' => 'required|max:16',
            'expire_month' => 'required',
            'expire_year' => 'required',
            'cvc' => 'required|max:4',
        ]);

        $lang = $request->has('lang') && $request->lang != 'en' ? $request->lang : 'en';
        $lang_file = public_path('i18n/translations/' . $lang . '.json');
        $lang_arr = json_decode(file_get_contents($lang_file), true);

        AccountSetting::updateOrCreate(
            ['user_id' => auth()->user()->id],
            [
                'user_id' => auth()->user()->id,
                'address' => $request->address,
                'card_holder_name' => $request->card_holder_name,
                'card_brand' => $request->card_brand,
                'card_number' => encrypt($request->card_number),
                'card_last_four_digits' => substr($request->card_number, -4),
                'expire_month' => $request->expire_month,
                'expire_year' => $request->expire_year,
                'cvc' => encrypt($request->cvc)
            ]
        );

        return response()->json([
            'status' => 1,
            'message' => $lang_arr['alert_messages']['auth_update_account_settings_success']
        ]);
    }

    public function resendVerificationEmail(Request $request)
    {
        $lang = $request->has('lang') && $request->lang != 'en' ? $request->lang : 'en';
        $lang_file = public_path('i18n/translations/' . $lang . '.json');
        $lang_arr = json_decode(file_get_contents($lang_file), true);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            // ************************* //
            // Send Verify Link To User
            // ************************* //

            $name = $user->name;
            $email = $user->email;
            $link = url('/verify-account/' . Hashids::encode($user->id));

            $email_template = EmailTemplate::where('type', 'sign_up_confirmation')->first();
            $email_template = transformEmailTemplateModel($email_template, $lang);

            $subject = $email_template['subject'];
            $content = $email_template['content'];

            $search = array("{{name}}", "{{link}}", "{{app_name}}");
            $replace = array($name, $link, env('APP_NAME'));
            $content = str_replace($search, $replace, $content);

            sendEmail($email, $subject, $content, '', '', $lang);

            return response()->json([
                'status' => 1,
                'message' => $lang_arr['alert_messages']['verification_email_success']
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => $lang_arr['alert_messages']['password_send_reset_link_error']
            ]);
        }
    }
}
