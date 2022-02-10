<?php

namespace App\Classes;

use App\Models\Package;
use App\Models\PackageSubscription;
use App\Models\Payment;
use App\User;
use Carbon\Carbon;
use App\Models\Card;
use App\Models\PackageFeature;
use App\Models\EmailTemplate;
use App\Models\EmailSetting;
use App\Models\PaymentGatewaySetting;
use Config;
use PDF;
use Hashids;
use LaravelPDF;

class PaymentHandler
{
    public static function createDataPayload($package,$type,$repetition)
    {
        $data = [];

        $subscription_type = ($type == 1) ? 'Monthly' : 'Yearly';
        $description = $package->title.' Package ('.$subscription_type.' Subscription)';
        $price = ($type == 1) ? $package->monthly_price*$repetition : $package->yearly_price*$repetition;

        $data['items'] = [
            [
                'name'  => $package->title.' Package',
                'desc'  => $description,
                'price' => $price,
                'qty'   => 1,
            ],
        ];

        $data['title'] = 'Package Subscription';
        $data['subscription_desc'] = $data['invoice_description'] = $description;

        $total = 0;
        foreach ($data['items'] as $item) {
            $total += $item['price'] * $item['qty'];
        }

        $data['total'] = $total;

        return $data;
    }

	public static function checkout($request, $package_id,$type,$user_id,$payment_method,$return_url='',$cancel_url='',$paid_by='')
	{
        $user = User::find($user_id);
        $current_subscription = $user->subscription;
        $package = Package::find($package_id);

        $repetition = $request->has('repetition') ? $request->repetition : 1;
        $payment_option = $request->has('payment_option') ? $request->payment_option : 2;

        // ****************************************** //
        //    Send Email Of Package Updated By Admin  //
        // ****************************************** //
        $lang = \App::getLocale();

        $email_template = EmailTemplate::where('type','paid_package_upgrade_downgrade_by_admin')->first();
        $name = $user->name;
        $email = $user->email;
        $previous_type = ($current_subscription->type == 1) ? 'Monthly' : 'Yearly';
        $new_type = ($type == 1) ? 'Monthly' : 'Yearly';
        $subject = $email_template->subject;
        $content = $email_template->content;

        $search = array("{{name}}","{{from}}","{{previous_type}}","{{to}}","{{new_type}}","{{app_name}}");
        $replace = array($name,$current_subscription->package_title,$previous_type,$package->title,$new_type,env('APP_NAME'));
        $content  = str_replace($search,$replace,$content);

        // ************************** //
        //    Mollie Payment Gateway  //
        // ************************** //

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

        // ************************** //
        //    Add New Subscription    //
        // ************************** //

        if($package->sub_title == 'daily')
        {
            $end_date = Carbon::now('UTC')->addDays(1)->timestamp;
            $price = $package->monthly_price;
        }
        else
        {
            if($type == 1)
            {
                $end_date = Carbon::now('UTC')->addMonth($repetition)->timestamp;
                $price = $package->monthly_price*$repetition;
            }
            else
            {
                $end_date = Carbon::now('UTC')->addYear($repetition)->timestamp;
                $price = $package->yearly_price*$repetition;
            }
        }

        $packageLinkedFeatures = $package->linkedFeatures->pluck('count','feature_id')->toArray();

        $package_description = $package->description;
        $package_title = $package->title;

        \Log::info('Session Language Checkout', array(
            'response' => session('lang')
        ));

        if(!empty(session('lang')) && session('lang') != 'en')
        {
            $package_title = translation($package->id,2,session('lang'),'title',$package->title);
            $package_description = translation($package->id,2,session('lang'),'description',$package->description);
        }

        $packageSubscription = PackageSubscription::create([
            'user_id'       =>  $user_id,
            'package_id'    =>  $package_id,
            'price'         =>  $price,
            'features'      =>  empty($package->linkedFeatures) ? '' : json_encode($packageLinkedFeatures),
            'description'   =>  $package_description,
            'type'          =>  $type,
            'start_date'    =>  Carbon::now('UTC')->timestamp,
            'end_date'      =>  ($package->id == 2) ? Null : $end_date,
            'repetition'    =>  $repetition,
            'payment_option'=>  $payment_option,
            'is_active'     =>  1
        ]);

        if($package->id == 2) // Free Package
        {
            $packageSubscription->update([
                'payment_option' => 1
            ]);

            $user->update([
                'is_expired'               => 0,
                'on_hold_package_id'       => NULL,
                'package_id'               => $package_id,
                'package_subscription_id'  => $packageSubscription->id,
                'on_trial'                 => 0,
                'package_recurring_flag'   => 0,
                'switch_to_paid_package'   => 0,
                'package_updated_by_admin' => ($paid_by == 'Admin') ? 1 : 0,
                'unpaid_package_email_by_admin' => 0,
                'expired_package_disclaimer' => 0,
                'last_quota_revised'       => NULL,
                'dynamic_qr_codes' => array_key_exists(1, $packageLinkedFeatures) ? $packageLinkedFeatures[1] : null,
                'static_qr_codes' => array_key_exists(2, $packageLinkedFeatures) ? $packageLinkedFeatures[2] : null,
                'qr_code_scans' => array_key_exists(3, $packageLinkedFeatures) ? $packageLinkedFeatures[3] : null,
                'bulk_import_limit' => array_key_exists(13, $packageLinkedFeatures) ? $packageLinkedFeatures[13] : null
            ]);

            if(!empty($user->mollie_customer_id) && !empty($current_subscription->payment))
            {
                try {
                    $customer = $mollie->customers->get($user->mollie_customer_id);
                    $response = $customer->cancelSubscription($current_subscription->payment->profile_id);
                } catch (\Mollie\Api\Exceptions\ApiException $e) {

                }
            }

            if($paid_by == 'Admin')
            {
                $user->update([
                    'last_quota_revised' => date("Y-m-d H:i:s")
                ]);

                sendEmail($email, $subject, $content);
            }

            return response()->json([
                'status' => 1,
                'message' => __('Your package has been updated successfully.'),
            ]);
        }

        // ************************** //
        //     Create Data Payload    //
        // ************************** //

        $data = self::createDataPayload($package,$type,$repetition);
        $data['invoice_id'] = uniqid();

        // ************************** //
        //        Calculate Vat       //
        // ************************** //

        $amount = $data['total'];
        $vat_percentage = settingValue('vat');
        $vat_country_code = 'de';

        if(!empty($user->country_id) && $user->country->apply_default_vat == 0 && $user->country->status == 1)
        {
            $vat_percentage =  $user->country->vat;
            $vat_country_code = $user->country->code;
        }

        $vat_amount = ($amount * $vat_percentage) / 100;

        $voucher = '';
        $discount_percentage = $discount_amount = 0;

        if($request->has('voucher') && !empty($request->voucher))
        {
            $voucher = $request->voucher;
            $discount_percentage = $request->discount_percentage;
            $discount_amount = $request->discount_amount;
        }

        $total_amount = $amount + $vat_amount - $discount_amount;
        $total_amount = number_format($total_amount, 2, '.', '');

        // ************************** //
        //        Add New Payment     //
        // ************************** //

        $payment = Payment::create([
            'user_id'                   =>  $user_id,
            'subscription_id'           =>  $packageSubscription->id,
            'item'                      =>  $package_title,
            'payment_method'            =>  $payment_method,
            'amount'                    =>  $amount,
            'vat_percentage'            =>  $vat_percentage,
            'vat_amount'                =>  $vat_amount,
            'vat_country_code'          =>  strtolower($vat_country_code),
            'voucher'                   =>  $voucher,
            'discount_percentage'       =>  $discount_percentage,
            'discount_amount'           =>  $discount_amount,
            'total_amount'              =>  $total_amount,
            'payload'                   =>  json_encode($data),
            'lang'                      =>  !empty(session('lang')) && session('lang') != 'en' ? session('lang') : 'en'
        ]);


        if($paid_by == 'Admin')
        {
            $user->update([
                'is_expired'                => 0,
                'on_hold_package_id'        => NULL,
                'payment_method'            => Config::get('constants.payment_methods')['ADMIN'],
                'package_id'                => $packageSubscription->package_id,
                'package_subscription_id'   => $packageSubscription->id,
                'payment_id'                => $payment->id,
                'package_recurring_flag'    => 0,
                'on_trial'                  => 0,
                'switch_to_paid_package'    => 1,
                'package_updated_by_admin'  => 1,
                'unpaid_package_email_by_admin' => 0,
                'expired_package_disclaimer' => 0,
                'last_quota_revised'        => date("Y-m-d H:i:s"),
                'dynamic_qr_codes' => array_key_exists(1, $packageLinkedFeatures) ? $packageLinkedFeatures[1] : null,
                'static_qr_codes' => array_key_exists(2, $packageLinkedFeatures) ? $packageLinkedFeatures[2] : null,
                'qr_code_scans' => array_key_exists(3, $packageLinkedFeatures) ? $packageLinkedFeatures[3] : null,
                'bulk_import_limit' => array_key_exists(13, $packageLinkedFeatures) ? $packageLinkedFeatures[13] : null
            ]);

            if(!empty($user->mollie_customer_id) && !empty($current_subscription->payment))
            {
                try {
                    $customer = $mollie->customers->get($user->mollie_customer_id);
                    $response = $customer->cancelSubscription($current_subscription->payment->profile_id);
                } catch (\Mollie\Api\Exceptions\ApiException $e) {

                }
            }

            $payment->update([
                'timestamp'           =>  Carbon::now('UTC')->timestamp,
                'discount_percentage' =>  100,
                'discount_amount'     =>  $total_amount,
                'total_amount'        =>  0,
                'status'              => 1
            ]);

            // ****************************************************** //
            //   Send Invoice With Email Of Package Updated By Admin  //
            // ****************************************************** //

            $invoive_data = array();
            $invoive_data= self::generatePaymentInvoice($payment);

            // Get only package price without voucher and discount
            if($type == 1)
            {
                $invoive_data['package_price'] = $package->monthly_price;
            }
            else
            {
                $invoive_data['package_price'] = $package->yearly_price;
            }
            // Fetch Repetition
            $invoive_data['repetition'] = $repetition;

            $invoive_data['global_font_family'] = in_array($lang, ['ru','ja','zh']) ? 'chinesefont' : 'arial';
            $invoive_data['payment_font_family'] = in_array($payment->lang, ['ru','ja','zh']) ? 'chinesefont' : 'arial';
            $pdf = LaravelPDF::loadView('emails.invoice', $invoive_data);

            sendEmail($email, $subject, $content, $pdf, "invoice.pdf", $lang);
            return;
        }

        $payment_success = false;
        $response_array = array();

        if($payment_method == Config::get('constants.payment_methods')['PAYPAL'])
        {
            $response_array = self::payByPaypalCreditCard($user,$total_amount);

            if(isset($response_array['ACK']) && strtolower($response_array['ACK']) == 'success')
            {
                $payment->update([
                    'token'             =>  $response_array['CORRELATIONID'],
                    'txn_id'            =>  $response_array['TRANSACTIONID'],
                    'data'              =>  json_encode($response_array),
                    'timestamp'         =>  Carbon::now('UTC')->timestamp
                ]);

                $payment_success = true;
            }
        }
        else if($payment_method == Config::get('constants.payment_methods')['MOLLIE'])
        {
            $customer = null;
            $customerExist = false;

            /*
            *Check Customer Existance
            */

            if(!empty($user->mollie_customer_id))
            {
                try {
                    $customer = $mollie->customers->get($user->mollie_customer_id);
                    $customerExist = true;
                } catch (\Mollie\Api\Exceptions\ApiException $e) {
                    $customerExist = false;
                }
            }

            if(!$customerExist)
            {
                /*
                *Create a new customer
                */

                $customer = $mollie->customers->create([
                    'name'  => $user->name,
                    'email' => $user->email,
                ]);
            }

            /*
            *Initial payment
            */

            $response = $mollie->payments->create([
                "amount" => [
                    "currency" =>  strtoupper(Config::get('constants.currency')['code']),
                    "value" => $total_amount // You must send the correct number of decimals, thus we enforce the use of strings
                ],
                'customerId'   => $customer->id,
                'sequenceType' => 'first',
                "description" => $data['subscription_desc'],
                "redirectUrl" => url("/mollie-confirmation?order_id=".Hashids::encode($packageSubscription->id)),
                "webhookUrl"  => url("/mollie/callback"),
                "metadata" => [
                    "order_id" => $packageSubscription->id,
                    "language" => session('lang')
                ],
            ]);
            $redirectUrl = $response->getCheckoutUrl();
            $response = (array)$response;

            $payment->update([
                'data'      =>  json_encode($response),
                'timestamp' =>  Carbon::now('UTC')->timestamp,
                'txn_id'    => $response['id'],
                'status'    => 2
            ]);

            $user->update([
                'mollie_customer_id' => $customer->id,
            ]);

            return response()->json([
                'redirect_url' => $redirectUrl,
                'status' => 1,
                'message' => 'Mollie Payment Gateway Link'
            ]);
        }

        if($payment_success)
        {
            $user->update([
                'is_expired'                => 0,
                'on_hold_package_id'        => NULL,
                'payment_method'            => $payment_method,
                'package_id'                => $package_id,
                'package_subscription_id'   => $payment->subscription_id,
                'payment_id'                => $payment->id,
                'package_recurring_flag'    => 1,
                'on_trial'                  => 0,
                'switch_to_paid_package'    => 1,
                'package_updated_by_admin'  => 0,
                'unpaid_package_email_by_admin' => 0,
                'expired_package_disclaimer'=> 0,
                'last_quota_revised'        => $type == 2 ? date("Y-m-d H:i:s") : NULL,
                'dynamic_qr_codes' => array_key_exists(1, $packageLinkedFeatures) ? $packageLinkedFeatures[1] : null,
                'static_qr_codes' => array_key_exists(2, $packageLinkedFeatures) ? $packageLinkedFeatures[2] : null,
                'qr_code_scans' => array_key_exists(3, $packageLinkedFeatures) ? $packageLinkedFeatures[3] : null,
                'bulk_import_limit' => array_key_exists(13, $packageLinkedFeatures) ? $packageLinkedFeatures[13] : null
            ]);

            self::sendInvoiceEmail($payment);
            return response()->json(['status' => 1, 'message' => 'Your transaction is complete and payment has been successfully processed.']);
        }
        else
        {
            self::deleteRecords($payment);
            return response()->json([
                'data' => $response_array,
                'status' => 0,
                'message' => 'Oops something went wrong, selected package is failed to update. Please confirm your credit card details.'
            ]);
        }
	}

    public static function payByPaypalCreditCard($user,$amount)
    {
        $accountSettings = $user->accountSettings;
        $nameArr = explode(' ', $accountSettings->card_holder_name);

        $paymentGatewaySettings = PaymentGatewaySetting::first();
        $paypal_api_secret = $paypal_api_password = $paypal_api_username = $paypal_api_base_url = '';

        if($paymentGatewaySettings->paypal_mode == 'sandbox')
        {
            $paypal_api_secret = $paymentGatewaySettings->paypal_sandbox_api_secret;
            $paypal_api_password = $paymentGatewaySettings->paypal_sandbox_api_password;
            $paypal_api_username = $paymentGatewaySettings->paypal_sandbox_api_username;
            $paypal_api_base_url = $paymentGatewaySettings->paypal_sandbox_api_base_url;
        }
        else if($paymentGatewaySettings->paypal_mode == 'live')
        {
            $paypal_api_secret = $paymentGatewaySettings->paypal_live_api_secret;
            $paypal_api_password = $paymentGatewaySettings->paypal_live_api_password;
            $paypal_api_username = $paymentGatewaySettings->paypal_live_api_username;
            $paypal_api_base_url = $paymentGatewaySettings->paypal_live_api_base_url;
        }

        $request_params = array(
            'VERSION' => '56.0',
            'SIGNATURE' => $paypal_api_secret,
            'PWD' => $paypal_api_password,
            'USER' => $paypal_api_username,
            'METHOD' => 'DoDirectPayment',
            'PAYMENTACTION' => 'Sale',
            'IPADDRESS' => $_SERVER['REMOTE_ADDR'],
            'AMT' => $amount,
            'CREDITCARDTYPE' => $accountSettings->card_brand,
            'ACCT' => $accountSettings->card_number,
            'EXPDATE' => $accountSettings->expire_month.$accountSettings->expire_year,
            'CVV2' => $accountSettings->cvc,
            'FIRSTNAME' => $nameArr[0],
            'LASTNAME' => isset($nameArr[1]) ? $nameArr[1] : "",
            'STREET' => '',
            'CITY' => '',
            'ZIP' => '',
            'COUNTRYCODE' => 'DE',
            'CURRENCYCODE' => strtoupper(Config::get('constants.currency')['code'])
        );

        $nvp_string = http_build_query($request_params);
        $api_endpoint = $paypal_api_base_url.'/nvp';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_URL, $api_endpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $nvp_string);

        $result = curl_exec($curl);

        $response_array = array();
        foreach (explode('&', $result) as $chunk) {
            $param = explode("=", $chunk);
            if ($param && isset($param[0]) && isset($param[1])) {
                $response_array[$param[0]] = urldecode($param[1]);
            }
        }

        return $response_array;
    }

    public static function checkoutCancel($payment_id)
    {
        $payment = Payment::find($payment_id);
        self::deleteRecords($payment);
    }

    public static function deleteRecords($payment)
    {
        $subscription = $payment->subscription;
//        $payment->update(['status'=> 6]);
        $subscription->delete();
    }

    public static function generatePaymentInvoice($payment)
    {
//        $subscription = $payment->subscription;
        $subscription = PackageSubscription::find($payment->subscription_id);

        $item = array();
        $item['name'] = $payment->item;
        $item['amount'] = $subscription->price;
        $item['type'] = $subscription->type;
        $item['start_date'] = $subscription->start_date;
        $item['end_date'] = $subscription->end_date;
        $item['vat_percentage'] = $payment->vat_percentage;
        $item['vat_amount'] = $payment->vat_amount;
        $item['reseller'] = $payment->reseller;
        $item['voucher'] = $payment->voucher;
        $item['discount_percentage'] = $payment->discount_percentage;
        $item['discount_amount'] = $payment->discount_amount;
        $item['total_amount'] = $payment->total_amount;
        $item['payment_method'] = $payment->payment_method;
        $item['description'] = $subscription->description;

        $data['item'] = $item;
        $data['user'] = $payment->user;
        $data['payment'] = $payment;

        return $data;
    }

    public static function sendInvoiceEmail($payment)
    {
        $lang = $payment->lang;
        \App::setLocale($lang);

        $user = $payment->user;
        $name = $user->name;
        $email = $user->email;

        $email_template = EmailTemplate::where('type','payment_success')->first();
        $email_template = transformEmailTemplateModel($email_template,$lang);

        $subject = $email_template['subject'];
        $content = $email_template['content'];

        $search = array("{{name}}","{{app_name}}");
        $replace = array($name,env('APP_NAME'));
        $content  = str_replace($search,$replace,$content);

        $data = array();
        $data= self::generatePaymentInvoice($payment);

        // Get only package price without voucher and discount
        if($payment->subscription->type == 1)
        {
            $data['package_price'] = $payment->subscription->package->monthly_price;
        }
        // Fetch Repetition
        if($payment->subscription->repetition > 1){
            $data['repetition'] = $payment->subscription->repetition;
        }

        $data['global_font_family'] = in_array($lang, ['ja','zh']) ? 'chinesefont' : 'arial';
        $data['payment_font_family'] = in_array($lang, ['ja','zh']) ? 'chinesefont' : 'arial';

        $pdf = LaravelPDF::loadView('emails.invoice', $data);

        sendEmail($email, $subject, $content, $pdf, "invoice.pdf", $lang);
    }
}

?>
