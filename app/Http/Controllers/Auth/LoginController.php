<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use Hashids;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest:web')->except('logout');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
        $messages = [
            'email.required' => __('The email field is required.'),
            'email.email' => __('The email must be a valid email address.'),
            'password.required' => __('The password field is required.'),
            'g-recaptcha-response.recaptcha' => __('The captcha field is required.')
        ];
        // Validate the form data
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => 'recaptcha',
        ], $messages);

        // Attempt to log the user in
        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            $user = auth()->user();

            $message = '';
            $is_user_active = true;
            $is_approved = true;

            switch ($user->status) {
                case 0:
                    $message = __('Your account has been disabled. Please') . ' ' . '<a class="font-weight-bold btn-link" href="' . route('frontend.contact') . '">' . __('contact') . '</a>' . ' ' . __('Admin in case of any concerns.');
                    $is_user_active = false;
                    break;
                case 2:
                    $message = __('Your account is not verified. If you didn`t receive verification email then') . ' ' . '<a class="font-weight-bold btn-link" href="' . url('/resend-email?email=' . $user->email) . '&_token=' . Str::random(60) . '">' . __('click here.') . '</a>';
                    $is_user_active = false;
                    $resend_email_flag = true;
                    break;
                case 3:
                    $message = __('Your account has been deleted. Please') . ' ' . '<a class="font-weight-bold btn-link" href="' . route('frontend.contact') . '">' . __('contact') . '</a>' . ' ' . __('Admin in case of any concerns.');
                    $is_user_active = false;
                    break;
            }

            if ($is_user_active == false) {
                auth()->logout();
                return redirect()->back()->withErrors(['error' => $message]);
            }

            switch ($user->is_approved) {
                case 0:
                    $message = __('Your account is under review. Please contact Admin in case of any concerns.');
                    $is_approved = false;
                    break;
                case 2:
                    $message = __('Your account is rejected. Please contact Admin in case of any concerns.');
                    $is_approved = false;
                    break;
            }

            if ($is_approved == false) {
                auth()->logout();
                return redirect()->back()->withErrors(['error' => $message]);
            }

            if ($user->otp_auth_status) {
                $data['id'] = \Hashids::encode($user->id);
                $data['email'] = $request->email;
                $data['password'] = $request->password;
                auth()->logout();
                return view('frontend.dashboard.otp-auth.verify', $data);
            } else {
                // if successful, then redirect to their intended location
                session(['timezone' => $request->timezone]);
                return redirect()->intended(route('frontend.user.dashboard'));
            }
        }
        // if unsuccessful, then redirect back to the login with the form data
        return redirect()->back()->withErrors(['error' => __('These credentials do not match our records.')]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        return redirect()->route('login');
    }

    public function resendEmail(Request $request)
    {
        $lang = \App::getLocale();
        $user = User::where('email', str_replace(' ', '+', $request->email))->first();
        $email = $user->email;

        $email_template = EmailTemplate::where('type', 'sign_up_confirmation')->first();
        $email_template = transformEmailTemplateModel($email_template, $lang);

        $subject = $email_template['subject'];
        $content = $email_template['content'];

        $hashId = Hashids::encode($user->id);
        $link = url('/verify-account/' . $hashId);
        $search = array("{{name}}", "{{app_name}}", "{{link}}");
        $replace = array($user->username, env('APP_NAME'), $link);
        $content = str_replace($search, $replace, $content);

        sendEmail($email, $subject, $content);

        return redirect()->back()->with('flash_success', __('Account verification link has been sent to your account.'));
    }
}
