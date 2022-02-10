<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\EmailTemplate;
use App\Models\Package;
use App\Models\PackageSubscription;
use App\Models\Payment;
use App\Models\Timezone;
use App\Providers\RouteServiceProvider;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Session;
use Hashids;
use Config;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        $messages = [
            'name.required' => __('The name field is required.'),
            'name.max' => __('The name may not be greater than 100 characters.'),
            'email.required' => __('The email field is required.'),
            'email.email' => __('The email must be a valid email address.'),
            'email.max' => __('The email field may not be greater than 255 characters.'),
            'email.unique' => __('The email has already been taken.'),
            'password.required' => __('The password field is required.'),
            'password.min' => __('The password must be at least 8 characters.'),
            'password.max' => __('This field may not be greater than 30 characters.'),
            'password.regex' => __('Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters'),
            'password.confirmed' => __('The password confirmation does not match.'),
            'country_id.required' => __('The country id field is required.'),
            'g-recaptcha-response.recaptcha' => __('The captcha field is required.')
        ];
        $request = new Request($data);
        $this->validate($request, [
            'name' => ['required','string','max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8','max:30', 'confirmed','regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/'],
            'country_id' => ['required'],
            'g-recaptcha-response' => 'recaptcha'
        ], $messages);

        return true;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $lang =  \App::getLocale();
        // Match the timezone
        $timezone = Timezone::where('name',$data['time_zone'])->first();
        if(empty($timezone)){
            $timezoneName = 'Pacific/Midway';
        } else {
            $timezoneName = $timezone->name;
        }

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['email'],
            'email' => $data['email'],
            'country_id' => $data['country_id'],
            'timezone' => $timezoneName,
            'status' => 2,
            'is_approved' => 1,
            'original_password' => $data['password'],
            'password' => Hash::make($data['password']),
        ]);

        if(request()->has('subscribeToMinPricePlan') && request()->subscribeToMinPricePlan == 1)
        {
            $package = Package::find(3); // Min Price Package
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

        $packageLinkedFeatures = $package->linkedFeatures->pluck('count','feature_id')->toArray();

        $user->dynamic_qr_codes = array_key_exists(1, $packageLinkedFeatures) ? $packageLinkedFeatures[1] : null;
        $user->static_qr_codes = array_key_exists(2, $packageLinkedFeatures) ? $packageLinkedFeatures[2] : null;
        $user->qr_code_scans = array_key_exists(3, $packageLinkedFeatures) ? $packageLinkedFeatures[3] : null;
        $user->bulk_import_limit = array_key_exists(13, $packageLinkedFeatures) ? $packageLinkedFeatures[13] : null;

        $packageSubscription = PackageSubscription::create([
            'user_id'       =>  $user->id,
            'package_id'    =>  $package->id,
            'price'         =>  0,
            'features'      =>  empty($package->linkedFeatures) ? '' : json_encode($packageLinkedFeatures),
            'description'   =>  $package->description,
            'type'          =>  $type,
            'start_date'    =>  Carbon::now('UTC')->timestamp,
            'end_date'      =>  $end_date,
            'is_active'     =>  1
        ]);

        $user->update([
            'package_id' => $package->id,
            'package_subscription_id' => $packageSubscription->id,
            'on_trial' => $on_trial,
            'package_recurring_flag' => 0
        ]);

        if(request()->has('subscribeToMinPricePlan') && request()->subscribeToMinPricePlan == 1)
        {
            $data['subscription_desc'] = $package->title.' Package (Monthly Subscription)';

            $payment = Payment::create([
                'user_id'                   =>  $user->id,
                'subscription_id'           =>  $packageSubscription->id,
                'item'                      =>  $package->title,
                'payment_method'            =>  Config::get('constants.payment_methods')['VOUCHER_PROMOTION'],
                'amount'                    =>  0,
                'vat_percentage'            =>  0,
                'vat_amount'                =>  0,
                'voucher'                   =>  $data['voucher'],
                'reseller'                  =>  $data['reseller'],
                'discount_percentage'       =>  0,
                'discount_amount'           =>  0,
                'total_amount'              =>  0,
                'payload'                   =>  json_encode($data),
                'lang'                      =>  \App::getLocale(),
                'timestamp'                 =>  Carbon::now('UTC')->timestamp,
            ]);

            $user->update([
                'payment_method'            => Config::get('constants.payment_methods')['VOUCHER_PROMOTION'],
                'payment_id'                => $payment->id,
                'status'                    => 1,
            ]);
        }
        else
        {
            $email = $user->email;

            $email_template = EmailTemplate::where('type','sign_up_confirmation')->first();
            $email_template = transformEmailTemplateModel($email_template,$lang);

            $subject = $email_template['subject'];
            $content = $email_template['content'];

            $hashId = Hashids::encode($user->id);
            $link = url('/verify-account/'.$hashId);
            $search = array("{{name}}","{{app_name}}","{{link}}");
            $replace = array($user->username,env('APP_NAME'),$link);
            $content  = str_replace($search,$replace,$content);

            sendEmail($email, $subject, $content);
        }

        return $user;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $data['countries'] = Country::all();
        return view('frontend.auth.register')->with($data);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
//        $this->validator($request->all())->validate();
        $this->validator($request->all());

        event(new Registered($user = $this->create($request->all())));

        if($request->has('subscribeToMinPricePlan') && $request->subscribeToMinPricePlan == 1)
        {
            return response()->json([
                'data' => $user->makeHidden(['subscription','original_password']),
                'status' => 1,
                'message' => __('Your account has been verified successfully.')
            ]);
        }

        $this->sendRegisterResponse();

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    public function sendRegisterResponse() {
        return redirect($this->redirectPath())
            ->with('flash_success', __('Account verification link has been sent to your account.'));
    }
}
