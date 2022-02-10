<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Models\EmailTemplate;
use App\Models\PackageSubscription;
use App\Models\Payment;
use App\Models\PaymentGatewaySetting;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Classes\PaymentHandler;
use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageFeature;
use Hashids;
use Session;
use Config;
use LaravelPDF;

class SubscriptionController extends Controller
{
    public function upgradePackage()
    {
        $data['packageFeatures'] = PackageFeature::where('status', 1)->get();
        $data['packages'] = Package::where('status', 1)->where('id', '!=', 1)->get();
        $data['packageSubscription'] = auth()->user()->subscription;

        $vat_percentage = settingValue('vat');

        if(!empty(auth()->user()->country_id) && auth()->user()->country->apply_default_vat == 0 && auth()->user()->country->status == 1)
        {
            $vat_percentage =  auth()->user()->country->vat;
        }

        $data['vatAmount'] =  $vat_percentage / 100;
        $data['vatPercentage'] =  $vat_percentage;

        return view('frontend.dashboard.user-packages.update_package', $data);
    }

    public function subscribe(Request $request)
    {
        $data['package'] = Package::findOrFail($request->package_id);
        $data['type'] = $request->type;

        if ($data['package']->id == 2) // Free Package
        {
            $package_id = $data['package']->id;
            $user_id = auth()->user()->id;

            $response = PaymentHandler::checkout($request, $package_id, $request->type, $user_id, 2);
            $get_result_arr = json_decode($response->getContent(), true);
            if ($get_result_arr['status'] == 1)
            {
                Session::flash('flash_success', $get_result_arr['message']);
                return redirect()->route('frontend.user.account');
            }
        }

        // Vat calculate
        $user = auth()->user();
        $amount = $request->type == 1 ? $data['package']->monthly_price : $data['package']->yearly_price;
        $repetition = $request->has('repetition') ? $request->repetition : '1';
        $amount = $amount * $repetition;
        $data['vat_percentage'] = settingValue('vat');
        if (!empty($user->country_id) && $user->country->apply_default_vat == 0 && $user->country->status == 1) {
            $data['vat_percentage'] = $user->country->vat;
        }
        $data['amount'] = $amount ;
        $data['vat_amount'] = ($amount * $data['vat_percentage']) / 100;
        $data['total_amount'] = $amount + $data['vat_amount'];
        $data['repetition'] = $repetition;

        return view('frontend.dashboard.user-packages.subscribe', $data);
    }

    public function checkout(Request $request)
    {
        $id = Hashids::decode($request->id)[0];
        $package = Package::findOrFail($id);
        $return_url = $cancel_url = '';
        session(['lang' => $request->lang]);

        $package_id = $package->id;
        $user_id = auth()->user()->id;

        $response = PaymentHandler::checkout($request, $package_id, $request->type, $user_id, 2);
        $get_result_arr = json_decode($response->getContent(), true);

        if ($get_result_arr['status'] == 1) {
            return redirect($get_result_arr['redirect_url']);
        }
    }

    public function mollieCallback(Request $request)
    {
        $paymentGatewaySettings = PaymentGatewaySetting::first();
        if($paymentGatewaySettings->mollie_mode == 'sandbox')
        {
            $mollie_api_key = $paymentGatewaySettings->mollie_sandbox_api_key;
        }
        else if($paymentGatewaySettings->mollie_mode == 'live')
        {
            $mollie_api_key = $paymentGatewaySettings->mollie_live_api_key;
        }

        $mollie = new \Mollie\Api\MollieApiClient();
        $mollie->setApiKey($mollie_api_key);
        $payment = $mollie->payments->get($request->id);
        $paymentArr = (array)$payment;

        \Log::info('Mollie payment response', array(
            'response' => (array)$payment,
            'isPaid' => $payment->isPaid(),
            'hasRefunds' => $payment->hasRefunds(),
            'hasChargebacks' => $payment->hasChargebacks()
        ));

        $status = 3;
        session(['lang' => $payment->metadata->language]);
        $lang = $payment->metadata->language;

        $paymentModel = Payment::where(['txn_id' => $request->id])->first();
        $new_subscription = $paymentModel->subscription;

        if ($payment->isPaid() && !$payment->hasRefunds() && !$payment->hasChargebacks() && empty($paymentModel->profile_id) && empty($paymentModel->profile_data)) {
            /*
            * The payment is paid and isn't refunded or charged back. At this point you'd  probably want to start the process of delivering the product to the customer.
            */
            $status = 1;

            $package = Package::find($new_subscription->package_id);
            $packageLinkedFeatures = $package->linkedFeatures->pluck('count', 'feature_id')->toArray();

            $user = User::find($new_subscription->user_id);
            $current_subscription = $user->subscription;

            $customer = $mollie->customers->get($user->mollie_customer_id);

            /*
            *Cancel Previous Subscription
            */

            if (!empty($current_subscription->payment) && $user->package_recurring_flag) {
                try {
                    $response = $customer->cancelSubscription($current_subscription->payment->profile_id);
                    \Log::info('Mollie Cancel Previous Subscription Response Success', array(
                        'response' => (array)$response
                    ));
                } catch (\Mollie\Api\Exceptions\ApiException $e) {
                    \Log::info('Mollie Cancel Previous Subscription Response Fail', array(
                        'response' => $e->getMessage()
                    ));
                }
            }

            /*
            *Create New Subscription
            */

            $total_amount = $paymentModel->total_amount + $paymentModel->discount_amount;

            if ($package->sub_title == 'daily') {
                $repetition = 1;
                $response = $customer->createSubscription([
                    "amount" => [
                        "currency" => strtoupper(Config::get('constants.currency')['code']),
                        "value" => (string)number_format($total_amount, 2)
                    ],
                    "interval" => "1 day",
                    "startDate" => Carbon::now('UTC')->addDays(1)->format('Y-m-d'),
                    "description" => $paymentModel->item . ' ' . uniqid(),
                    "webhookUrl" => url("/mollie/subscriptions/webhook"),
                ]);
            } else {
                $interval = "1 month";
                $repetition = $new_subscription->repetition;
                if ($new_subscription->type == 1) {
                    if ($repetition > 1) {
                        $interval = $new_subscription->repetition . " months";
                    }
                } else {
                    $interval = $new_subscription->repetition * 12 . " months";
                }
                \Log::info('Mollie Subscription Response', array(
                    'response' => $interval
                ));
                $response = $customer->createSubscription([
                    "amount" => [
                        "currency" => strtoupper(Config::get('constants.currency')['code']),
                        "value" => (string)number_format($total_amount, 2)
                    ],
                    "interval" => $interval,
                    "startDate" => ($new_subscription->type == 1) ? Carbon::now('UTC')->addMonth($repetition)->format('Y-m-d') : Carbon::now('UTC')->addYear($repetition)->format('Y-m-d'),
                    "description" => $paymentModel->item . ' ' . uniqid(),
                    "webhookUrl" => url("/mollie/subscriptions/webhook"),
                ]);
            }

            $previousUserPackageId = $user->package_id;
            $previousUserSubscriptionId = $user->package_subscription_id;

            $response = (array)$response;

            \Log::info('Mollie Subscription Response', array(
                'response' => $response
            ));

            /*
            ** Update User
            */

            $user->update([
                'package_id'                => $new_subscription->package_id,
                'package_subscription_id'   => $new_subscription->id,
                'payment_id'                => $paymentModel->id,
                'payment_method'            => Config::get('constants.payment_methods')['MOLLIE'],
                'package_recurring_flag'    => 1,
                'on_trial'                  => 0,
                'on_hold_package_id'        => NULL,
                'is_expired'                => 0,
                'switch_to_paid_package'    => 1,
                'package_updated_by_admin'  => 0,
                'unpaid_package_email_by_admin' => 0,
                'expired_package_disclaimer' => 0,
                'last_quota_revised'        => $new_subscription->type == 2 || $repetition != 1  ? date("Y-m-d H:i:s") : NULL,
                'dynamic_qr_codes' => array_key_exists(1, $packageLinkedFeatures) ? $packageLinkedFeatures[1] : null,
                'static_qr_codes' => array_key_exists(2, $packageLinkedFeatures) ? $packageLinkedFeatures[2] : null,
                'qr_code_scans' => array_key_exists(3, $packageLinkedFeatures) ? $packageLinkedFeatures[3] : null,
                'bulk_import_limit' => array_key_exists(13, $packageLinkedFeatures) ? $packageLinkedFeatures[13] : null
            ]);

            Payment::where('txn_id', $request->id)->update([
                'profile_id' => $response['id'],
                'profile_data' => $response
            ]);

            \Log::info('Payment Paid', array(
                'txn_id' => $request->id,
                'status' => $status,
                'package' => $package
            ));

            /*
            ** Start Send call to product immunity to redeem voucher
            */

            if(!empty($paymentModel->voucher))
            {
                $data = array(
                    "voucher" => $paymentModel->voucher,
                    "platform" => "QRC",
                    "apply_voucher" => 0
                );

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => Config::get('constants.product_immunity_url')."/api/vouchers/redeem?lang=".$lang,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => http_build_query($data),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                $response = json_decode($response, true);

                if(!$response['status']){
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => Config::get('constants.oddo_url')."/api/redeem-voucher?lang=".$lang,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => http_build_query($data),
                    ));

                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);

                    $response = json_decode($response, true);
                }

                if(!empty($response) && array_key_exists('status', $response) && $response['status'])
                {
                    $reseller = $response['data']['reseller']['name'].' ('.$response['data']['reseller']['email'].')';

                    /*
                    ** Create accounts on secondary projects
                    */

                    $secondary_project_user_ids = [];
                    $secondary_projects = [];

                    if(!empty($response['data']) && array_key_exists('secondary_projects', $response['data']) && !empty($response['data']['secondary_projects']))
                    {
                        foreach ($response['data']['secondary_projects'] as $key => $value)
                        {
                            if($value == "NED")
                            {
                                $data = array(
                                    'primaryEmail' => $user->email,
                                    'password' => $user->original_password,
                                    'username' => $user->username,
                                    'firstName' => $user->name,
                                    'lastName' => '',
                                    'subscribeToMinPricePlan' => true,
                                    'voucher' => $paymentModel->voucher,
                                    'reseller' => $reseller,
                                );

                                $curl = curl_init();

                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => Config::get('constants.ned_link_url').'/accounts/member/register',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS => http_build_query($data),
                                    CURLOPT_HTTPHEADER => array(
                                        'Content-Type: application/x-www-form-urlencoded',
                                    ),
                                ));

                                $response_ned = curl_exec($curl);
                                curl_close($curl);

                                $response_ned_arr = json_decode($response_ned,true);

                                if(!empty($response_ned_arr) && array_key_exists('status', $response_ned_arr) && $response_ned_arr['status'])
                                {
                                    $secondary_project_user_ids[] = $response_ned_arr['data']['_id'];
                                    $secondary_projects[] = 'NED.link';
                                }

                                \Log::info('Ned Link Account Sign Up', array(
                                    'response' => $response_ned
                                ));
                            }
                            else if($value == "TRF")
                            {
                                $data = array(
                                    'name' => $user->name,
                                    'email' => $user->email,
                                    'password' => $user->original_password,
                                    'password_confirmation' => $user->original_password,
                                    'timezone' => $user->timezone,
                                    'country_id' => $user->country_id,
                                    'subscribeToMinPricePlan' => true,
                                    'platform' => 12,
                                    'voucher' => $paymentModel->voucher,
                                    'reseller' => $reseller,
                                );

                                $curl = curl_init();

                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => Config::get('constants.transfer_immunity_url').'/api/auth/register',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS => $data,
                                ));

                                $response = curl_exec($curl);
                                curl_close($curl);

                                $response_arr = json_decode($response,true);

                                if(!empty($response_arr) && array_key_exists('status', $response_arr) && $response_arr['status'])
                                {
                                    $secondary_project_user_ids[] = $response_arr['data']['id'];
                                    $secondary_projects[] = 'Transfer Immunity';
                                }

                                \Log::info('Transfer Immunity Account Sign Up', array(
                                    'response' => $response
                                ));
                            }
                            else if($value == "MOV")
                            {
                                $data = array(
                                    'name' => $user->name,
                                    'email' => $user->email,
                                    'password' => $user->original_password,
                                    'password_confirmation' => $user->original_password,
                                    'timezone' => $user->timezone,
                                    'country_id' => $user->country_id,
                                    'subscribeToMinPricePlan' => true,
                                    'platform' => 12,
                                    'voucher' => $paymentModel->voucher,
                                    'reseller' => $reseller,
                                );

                                $curl = curl_init();

                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => Config::get('constants.move_immunity_url').'/api/auth/register',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS => $data,
                                ));

                                $response = curl_exec($curl);
                                curl_close($curl);

                                $response_arr = json_decode($response,true);

                                if(!empty($response_arr) && array_key_exists('status', $response_arr) && $response_arr['status'])
                                {
                                    $secondary_project_user_ids[] = $response_arr['data']['id'];
                                    $secondary_projects[] = 'Move Immunity';
                                }

                                \Log::info('Move Immunity Account Sign Up', array(
                                    'response' => $response
                                ));
                            }
                            else if($value == "AKQ")
                            {
                                $data = array(
                                    'action' => 'signup',
                                    'email_local' => $user->email,
                                    'pass1' => $user->original_password,
                                    'pass2' => $user->original_password,
                                    'timezone' => $user->timezone,
                                    'country_id' => $user->country_id,
                                    'firstname' => $user->name,
                                    'surname' => '',
                                    'subscribeToMinPricePlan' => true,
                                    'voucher' => $paymentModel->voucher,
                                    'reseller' => $reseller,
                                );

                                $curl = curl_init();

                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => Config::get('constants.aikq_url').'/api/index.php',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS => $data,
                                ));

                                $response = curl_exec($curl);
                                curl_close($curl);

                                $response_arr = json_decode($response,true);

                                if(!empty($response_arr) && array_key_exists('status', $response_arr) && $response_arr['status'])
                                {
                                    $secondary_project_user_ids[] = $response_arr['data']['id'];
                                    $secondary_projects[] = 'aikQ';
                                }

                                \Log::info('aikQ Account Sign Up', array(
                                    'response' => $response
                                ));
                            }
                            else if($value == "INB")
                            {
                                $data = array(
                                    'action' => 'signup',
                                    'email_local' => $user->email,
                                    'pass1' => $user->original_password,
                                    'pass2' => $user->original_password,
                                    'timezone' => $user->timezone,
                                    'country_id' => $user->country_id,
                                    'firstname' => $user->name,
                                    'surname' => '',
                                    'subscribeToMinPricePlan' => true,
                                    'voucher' => $paymentModel->voucher,
                                    'reseller' => $reseller,
                                );

                                $curl = curl_init();

                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => Config::get('constants.inbox_de_url').'/api/index.php',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS => $data,
                                ));

                                $response = curl_exec($curl);
                                curl_close($curl);

                                $response_arr = json_decode($response,true);

                                if(!empty($response_arr) && array_key_exists('status', $response_arr) && $response_arr['status'])
                                {
                                    $secondary_project_user_ids[] = $response_arr['data']['id'];
                                    $secondary_projects[] = 'Inbox';
                                }

                                \Log::info('Inbox.de Account Sign Up', array(
                                    'response' => $response
                                ));
                            }
                            else if($value == "OVM")
                            {
                                $data = array(
                                    'action' => 'signup',
                                    'email_local' => $user->email,
                                    'pass1' => $user->original_password,
                                    'pass2' => $user->original_password,
                                    'timezone' => $user->timezone,
                                    'country_id' => $user->country_id,
                                    'firstname' => $user->name,
                                    'surname' => '',
                                    'subscribeToMinPricePlan' => true,
                                    'voucher' => $paymentModel->voucher,
                                    'reseller' => $reseller,
                                );

                                $curl = curl_init();

                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => Config::get('constants.overmail_url').'/api/index.php',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS => $data,
                                ));

                                $response = curl_exec($curl);
                                curl_close($curl);

                                $response_arr = json_decode($response,true);

                                if(!empty($response_arr) && array_key_exists('status', $response_arr) && $response_arr['status'])
                                {
                                    $secondary_project_user_ids[] = $response_arr['data']['id'];
                                    $secondary_projects[] = 'Overmail';
                                }

                                \Log::info('Overmail Account Sign Up', array(
                                    'response' => $response
                                ));
                            }
                            else if($value == "MAI")
                            {
                                $data = array(
                                    'action' => 'signup',
                                    'email_local' => $user->email,
                                    'pass1' => $user->original_password,
                                    'pass2' => $user->original_password,
                                    'timezone' => $user->timezone,
                                    'country_id' => $user->country_id,
                                    'firstname' => $user->name,
                                    'surname' => '',
                                    'subscribeToMinPricePlan' => true,
                                    'voucher' => $paymentModel->voucher,
                                    'reseller' => $reseller,
                                );

                                $curl = curl_init();

                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => Config::get('constants.maili_de_url').'/api/index.php',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS => $data,
                                ));

                                $response = curl_exec($curl);
                                curl_close($curl);

                                $response_arr = json_decode($response,true);

                                if(!empty($response_arr) && array_key_exists('status', $response_arr) && $response_arr['status'])
                                {
                                    $secondary_project_user_ids[] = $response_arr['data']['id'];
                                    $secondary_projects[] = 'Maili';
                                }

                                \Log::info('Maili.de Account Sign Up', array(
                                    'response' => $response
                                ));
                            }
                            else if($value == "EMK")
                            {
                                $data = array(
                                    'name' => $user->name,
                                    'email' => $user->email,
                                    'password' => $user->original_password,
                                    'password_confirmation' => $user->original_password,
                                    'subscribeToMinPricePlan' => true,
                                    'platform' => 12,
                                    'voucher' => $paymentModel->voucher,
                                    'reseller' => $reseller,
                                );

                                $curl = curl_init();

                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => Config::get('constants.email_marketing').'/api/auth/voucher-register',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS => $data,
                                ));

                                $response = curl_exec($curl);
                                curl_close($curl);

                                $response_arr = json_decode($response,true);

                                if(!empty($response_arr) && array_key_exists('status', $response_arr) && $response_arr['status'])
                                {
                                    $secondary_project_user_ids[] = $response_arr['data']['id'];
                                    $secondary_projects[] = 'EMK';
                                }

                                \Log::info('Email Marketing Account Sign Up', array(
                                    'response' => $response
                                ));
                            }
                        }
                    }

                    /*
                    ** Start Send call to product immunity to redeem voucher
                    */

                    $data = array(
                        "voucher" => $paymentModel->voucher,
                        "platform" => "QRC",
                        "user_data" => array(
                            'name' => $user->name,
                            'email' => $user->email
                        ),
                        "main_project_user_id" => $user->id,
                        "secondary_project_user_ids" => implode(',', $secondary_project_user_ids),
                        "apply_voucher" => 1
                    );

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => Config::get('constants.product_immunity_url')."/api/vouchers/redeem?lang=".$lang,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => http_build_query($data),
                    ));

                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);

                    $responseArr = json_decode($response, true);



                    if(!$responseArr['status']){
                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                            CURLOPT_URL => Config::get('constants.oddo_url')."/api/redeem-voucher?lang=".$lang,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => http_build_query($data),
                        ));

                        $response = curl_exec($curl);
                        $err = curl_error($curl);
                        curl_close($curl);

                        $responseArr = json_decode($response, true);
                    }

                    \Log::info('Redeem Voucher', array(
                        'response' => $response
                    ));
                    if(array_key_exists('status', $responseArr) && $responseArr['status'])
                    {
                        $reseller = $responseArr['data']['reseller']['name'].' ('.$responseArr['data']['reseller']['email'].')';

                        $paymentModel->update([
                            'reseller' => $reseller
                        ]);
                    }

                    /*
                    ** End Send call to product immunity to redeem voucher
                    */

                    if(!empty($secondary_projects))
                    {
                        /*
                        ** Start send email to user
                        */

                        $name = $user->name;
                        $email = $user->email;

                        $email_template = EmailTemplate::where('type','lite_account_created_on_other_platforms')->first();
                        $email_template = transformEmailTemplateModel($email_template,$lang);

                        $subject = $email_template['subject'];
                        $content = $email_template['content'];

                        $search = array("{{name}}","{{app_name}}","{{platforms}}");
                        $replace = array($name,env('APP_NAME'),implode(',', $secondary_projects));
                        $content  = str_replace($search,$replace,$content);

                        sendEmail($email, $subject, $content, '', '', $lang);

                        /*
                        ** End send email to user
                        */

                        $user->update([
                            'is_secondary_accounts_created' => 1
                        ]);
                    }
                    else
                    {
                        $user->update([
                            'is_secondary_accounts_created' => 0
                        ]);
                    }
                }
            }

            /*
            ** End Send call to product immunity to validate voucher
            */

            PaymentHandler::sendInvoiceEmail($paymentModel);

            /* Start Switch Package Notification with email */
            switchPackageNotification($paymentModel,$previousUserPackageId,$previousUserSubscriptionId,$lang);
            /* End Switch Package Notification with email */

        } elseif ($payment->isOpen()) {
            $status = 2;
        } elseif ($payment->isPending()) {
            $status = 3;
        } elseif ($payment->isFailed()) {
            $status = 4;
        } elseif ($payment->isExpired()) {
            $status = 5;
        } elseif ($payment->isCanceled()) {
            $status = 6;
            PaymentHandler::deleteRecords($paymentModel);
        } elseif ($payment->hasRefunds()) {
            /*
             * The payment has been (partially) refunded.
             * The status of the payment is still "paid"
            */
            $status = 7;
        } elseif ($payment->hasChargebacks()) {
            /*
            *The payment has been (partially) charged back. The status of the payment is still paid
            */
            $status = 8;
        }

        Payment::where('txn_id', $request->id)->update([
            'status' => $status,
            'data' => (array)$payment
        ]);
    }

    public function mollieConfirmation(Request $request)
    {
        // Ajax call
        $data['order_id'] = Hashids::decode($request->order_id)[0];
        $data['payment'] = Payment::where('subscription_id', $data['order_id'])->first();
        return view('frontend.dashboard.accounts.waiting_mollie_confirmation', $data);
    }

    public function mollieWaiting(Request $request)
    {
        $payment = Payment::where('subscription_id', $request->order_id)->first();
        $user = $payment->user;

        $lang = \App::getLocale();


        $message = __('Your transaction is completed and payment has been successfully processed.');
        if ($payment->status == 1) {
            if($user->is_secondary_accounts_created == 1 && !empty($payment->voucher))
            {
                $message = $message . __(' You have got a lite account on other platforms. Please check your email for further details.');
            }
            Session::flash('flash_success', $message);
        } else if($request->numberOfRequest >= 4) {
            Session::flash('flash_info', __('We are processing your payment and your package will update soon!'));
        }
        return response()->json([
            'status' => $payment->status,
            'message' => $message,
            'url'  => route('frontend.user.account')
        ]);
    }

    public function mollieSubscriptionWebhook(Request $request)
    {
        \Log::info('Mollie Subscription Webhook Response', array(
            'response' => $request->all()
        ));

        if (!$request->has('id')) {
            return;
        }

        $paymentGatewaySettings = PaymentGatewaySetting::first();
        if ($paymentGatewaySettings->mollie_mode == 'sandbox') {
            $mollie_api_key = $paymentGatewaySettings->mollie_sandbox_api_key;
        } else if ($paymentGatewaySettings->mollie_mode == 'live') {
            $mollie_api_key = $paymentGatewaySettings->mollie_live_api_key;
        }

        $mollie = new \Mollie\Api\MollieApiClient();
        $mollie->setApiKey($mollie_api_key);
        $payment = $mollie->payments->get($request->id);

        \Log::info('Mollie Subscription Payment Response', array(
            'response' => (array)$payment
        ));

        if ($payment->isPaid()) {
            $paymentModel = Payment::where(['profile_id' => $payment->subscriptionId])->orderBy('id', 'DESC')->first();
            $subscription = $paymentModel->subscription;
            $user = $paymentModel->user;
            $packageLinkedFeatures = json_decode($subscription->features, true);

            /*
            *Create new subscription
            */

            $newSubscription = $subscription->replicate();
            $newSubscription->start_date = Carbon::now('UTC')->timestamp;
            $newSubscription->description = Package::find($subscription->package_id)->description;
            if ($subscription->package->sub_title == 'daily') {
                $newSubscription->end_date = Carbon::now('UTC')->addDays(1)->timestamp;
            } else {
                $newSubscription->end_date = ($subscription->type == 1) ? Carbon::now('UTC')->addMonth()->timestamp : Carbon::now('UTC')->addYear()->timestamp;
            }
            $newSubscription->save();

            /*
            *Create new payment
            */

            $newPaymentModel = $paymentModel->replicate();
            $newPaymentModel->subscription_id = $newSubscription->id;
            $newPaymentModel->txn_id = $request->id;
            $newPaymentModel->data = json_encode((array) $payment);
            $newPaymentModel->timestamp = Carbon::now('UTC')->timestamp;
            $newPaymentModel->total_amount = $paymentModel->amount + $paymentModel->vat_amount;
            $newPaymentModel->reseller = null;
            $newPaymentModel->voucher = null;
            $newPaymentModel->discount_percentage = 0;
            $newPaymentModel->discount_amount = 0;
            $newPaymentModel->save();

            /*
            *Update user
            */

            $user->update([
                'package_subscription_id'   => $newSubscription->id,
                'payment_id'                => $newPaymentModel->id,
                'payment_method'            => Config::get('constants.payment_methods')['MOLLIE'],
                'package_recurring_flag'    => 1,
                'is_expired'                => 0,
                'package_updated_by_admin'  => 0,
                'unpaid_package_email_by_admin' => 0,
                'expired_package_disclaimer' => 0,
                'dynamic_qr_codes' => array_key_exists(1, $packageLinkedFeatures) ? $packageLinkedFeatures[1] : null,
                'static_qr_codes' => array_key_exists(2, $packageLinkedFeatures) ? $packageLinkedFeatures[2] : null,
                'qr_code_scans' => array_key_exists(3, $packageLinkedFeatures) ? $packageLinkedFeatures[3] : null,
                'bulk_import_limit' => array_key_exists(13, $packageLinkedFeatures) ? $packageLinkedFeatures[13] : null
            ]);

            PaymentHandler::sendInvoiceEmail($newPaymentModel);
        }
    }

    public function history(Request $request){
        $limit = $request->has('limit') ? $request->limit : 15;
        $sort = $request->has('sort') ? $request->sort : 'created_at-desc';
        $text = $request->has('text') ? $request->text : '';

        $sortArr = explode('-', $sort);
        $db_record = PackageSubscription::where(['user_id' => auth()->user()->id])->orderBy($sortArr[0], $sortArr[1]);

        if ($request->has('text') && !empty($request->text)) {
            $db_record = $db_record->whereHas('package',function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->text . '%');
            });
        }

        $data['subscriptions'] = $db_record->paginate($limit);

        $data['limit'] = $limit;
        $data['sort'] = $sort;
        $data['text'] = $text;
        $data['limits'] = [15, 25, 50, 75, 100];
        return view('frontend.dashboard.subscriptions.history',$data);
    }

    public function invoices(Request $request){
        $limit = $request->has('limit') ? $request->limit : 15;
        $sort = $request->has('sort') ? $request->sort : 'created_at-desc';
        $text = $request->has('text') ? $request->text : '';
        $amount = $request->has('amount') ? $request->amount : '';

        $sortArr = explode('-', $sort);
        $db_record = Payment::where(['user_id' => auth()->user()->id])->whereNotNull('timestamp')->orderBy($sortArr[0], $sortArr[1]);

        if ($request->has('text') && !empty($request->text)) {
            $db_record = $db_record->where('item', 'LIKE', '%' . $request->text . '%');
        }

        if ($request->has('amount') && !empty($request->amount)) {
            $db_record = $db_record->where(function($q) use ($request) {
                $q->where('amount', 'LIKE', '%' . $request->amount . '%')
                    ->orWhere('vat_amount', 'LIKE', '%' . $request->amount . '%')
                    ->orWhere('discount_amount', 'LIKE', '%' . $request->amount . '%')
                    ->orWhere('total_amount', 'LIKE', '%' . $request->amount . '%');
            });
        }

        $data['payments'] = $db_record->paginate($limit);

        $data['limit'] = $limit;
        $data['sort'] = $sort;
        $data['text'] = $text;
        $data['amount'] = $amount;
        $data['limits'] = [15, 25, 50, 75, 100];
        return view('frontend.dashboard.subscriptions.invoices',$data);
    }

    public function downloadPaymentInvoice($id, Request $request)
    {
        $lang = \App::getLocale();

        $payment = Payment::find(\Hashids::decode($id)[0]);
        $data = array();
        $data = PaymentHandler::generatePaymentInvoice($payment);
        $data['global_font_family'] = in_array($lang, ['ja','zh']) ? 'chinesefont' : 'Segoe';
        $data['payment_font_family'] = in_array($payment->lang, ['ja','zh']) ? 'chinesefont' : 'Segoe';
// Get only package price without voucher and discount
        if($payment->subscription->type == 1)
        {
            $data['package_price'] = $payment->subscription->package->monthly_price;
        }
        else
        {
            $data['package_price'] = $payment->subscription->package->yearly_price;
        }
        // Fetch Repetition
        if($payment->subscription->repetition > 1){
            $data['repetition'] = $payment->subscription->repetition;
        }

        $pdf = LaravelPDF::loadView('emails.invoice', $data);
        //return $pdf->stream();
        return $pdf->download('invoice '.Carbon::now('UTC')->tz($payment->user->timezone)->format('Y-m-d H.i.s').'.pdf');
    }

    /**
     * Validate Voucher of Product Immunity
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function validateVoucher(Request $request)
    {
        if(empty($request->voucher))
        {
            return 0;
        }
        else
        {
            $data = array(
                'voucher' => $request->voucher,
                'platform' => 'QRC',
                'apply_voucher' => 0
            );

            $lang = \App::getLocale();

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => \Config::get('constants.product_immunity_url')."/api/vouchers/redeem"."?lang=".$lang,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data,
            ));

            $response = curl_exec($curl);
            curl_close($curl);

            if($response == false || !json_decode($response)->status){

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => \Config::get('constants.oddo_url')."/api/redeem-voucher"."?lang=".$lang,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $data,
                ));

                $response = curl_exec($curl);
                curl_close($curl);
            }
            return $response;
        }
    }

    /**
     * remove the expired package disclaimer flag
     *
     * @param  \Illuminate\Http\Request  $request
     */

    public function expiredPackageDisclaimerFlag()
    {
        $user = auth()->user();

        $user->update([
            'expired_package_disclaimer' => 0
        ]);

        return redirect()->route('frontend.user.dashboard');
    }

    /**
     * remove the package updated by admin flag
     *
     * @param  \Illuminate\Http\Request  $request
     */

    public function updatePackageByAdminFlag()
    {
        $user = auth()->user();
        $user->update([
            'package_updated_by_admin' => 0
        ]);

        return redirect()->route('frontend.user.dashboard');
    }

    /**
     * remove the unpaid package email by admin flag
     *
     * @param  \Illuminate\Http\Request  $request
     */

    public function unpaidPackageEmailByAdminFlag()
    {
        $user = auth()->user();
        $user->update([
            'unpaid_package_email_by_admin' => 0
        ]);

        return redirect()->route('frontend.user.dashboard');
    }

    public function cancelCurrentPackage(Request $request)
    {
        $user = auth()->user();

        $paymentGatewaySettings = PaymentGatewaySetting::first();
        if($paymentGatewaySettings->mollie_mode == 'sandbox')
        {
            $mollie_api_key = $paymentGatewaySettings->mollie_sandbox_api_key;
        }
        else if($paymentGatewaySettings->mollie_mode == 'live')
        {
            $mollie_api_key = $paymentGatewaySettings->mollie_live_api_key;
        }

        $mollie = new \Mollie\Api\MollieApiClient();
        $mollie->setApiKey($mollie_api_key);

        $customer = $mollie->customers->get($user->mollie_customer_id);
        $subscription = $customer->cancelSubscription($user->payment->profile_id);

        \Log::info('Mollie Cancel Subscription Response', array(
            'response' => (array) $subscription
        ));

        $user->update([
            'package_recurring_flag'  => 0
        ]);

        Session::flash('flash_success', __('Your subscription has been cancelled successfully!'));

        return redirect()->back();
    }
}
