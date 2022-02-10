<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Srmklive\PayPal\Services\ExpressCheckout;
use App\User;
use App\Models\Package;
use App\Models\Payment;
use App\Models\PaymentGatewaySetting;
use App\Models\EmailTemplate;
use App\Classes\PaymentHandler;
use App\Http\Resources\PaymentResource;
use Carbon\Carbon;
use PDF;
use Config;
use Hashids;
use LaravelPDF;

class SubscriptionController extends Controller
{
    /**
     * Create a new SubscriptionController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['downloadPaymentInvoice','molliePayment','mollieSubscriptionWebhook']]);
    }

    /**
     * Paypal Express Checkout
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function paymentCheckout(Request $request)
    {
        $return_url = $cancel_url = '';
        session(['lang' => $request->lang]);

        \Log::info('Session Language Payment Checkout', array(
            'response' => session('lang')
        ));

        // if($request->payment_method == Config::get('constants.payment_methods')['Paypal'])
        // {
        //     $return_url = '/paypal/success';
        //     $cancel_url = '/paypal/cancel';
        // }

        return PaymentHandler::checkout($request->package_id,$request->type,auth()->user()->id,$request->payment_method,$return_url,$cancel_url);
    }

    /**
     * Mollie payment gateway Verify user payment status after doing payment
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function molliePaymentVerify(Request $request)
    {
        $order_id = Hashids::decode($request->order_id)[0];
        $payment = Payment::where('subscription_id',$order_id)->first();

        $lang = $request->has('lang') && $request->lang != 'en' ? $request->lang : 'en';
        $lang_file = public_path('i18n/translations/'.$lang.'.json');
        $lang_arr = json_decode(file_get_contents($lang_file),true);

        return response()->json([
            'status' => $payment->status,
            'message' => $lang_arr['alert_messages']['mollie_verify_order_success']
        ]);
    }

    /**
     * Mollie payment gateway upadate payment status and user package / Callback
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function molliePayment(Request $request)
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
        $paymentArr = (array) $payment;

        \Log::info('Mollie payment response', array(
            'response' => (array) $payment, 
            'isPaid' => $payment->isPaid(),
            'hasRefunds' => $payment->hasRefunds(),
            'hasChargebacks' => $payment->hasChargebacks()
        ));
        
        $status = 3;
        session(['lang' => $payment->metadata->language]);

        $paymentModel = Payment::where(['txn_id' => $request->id])->first();
        $new_subscription = $paymentModel->subscription;
        
        if ($payment->isPaid() && !$payment->hasRefunds() && !$payment->hasChargebacks() && empty($paymentModel->profile_id) && empty($paymentModel->profile_data)) 
        {
            /*
            * The payment is paid and isn't refunded or charged back. At this point you'd  probably want to start the process of delivering the product to the customer.
            */
            $status = 1;
        
            $package = Package::find($new_subscription->package_id);
            $packageLinkedFeatures = $package->linkedFeatures->pluck('count','feature_id')->toArray();

            $user = User::find($new_subscription->user_id);
            $current_subscription = $user->subscription;

            $customer = $mollie->customers->get($user->mollie_customer_id);

            /*
            *Cancel Previous Subscription
            */

            if(!empty($current_subscription->payment) && $user->package_recurring_flag)
            {
                try {
                    $response = $customer->cancelSubscription($current_subscription->payment->profile_id);

                    \Log::info('Mollie Cancel Previous Subscription Response Success', array(
                        'response' => (array) $response
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

            if($new_subscription->package_id == 14)
            {
                $response = $customer->createSubscription([
                    "amount" => [
                         "currency" => strtoupper(Config::get('constants.currency')['code']),
                         "value" => (string) number_format($paymentModel->total_amount,2)
                     ],
                    "interval" =>  "1 day",
                    "startDate" => Carbon::now('UTC')->addDays(1)->format('Y-m-d'),
                    "description" => $paymentModel->item.' '.uniqid(),
                    "webhookUrl" => url("/api/mollie/subscriptions/webhook"),
                 ]);
            }
            else
            {
                $response = $customer->createSubscription([
                    "amount" => [
                         "currency" =>  strtoupper(Config::get('constants.currency')['code']),
                         "value" => (string) number_format($paymentModel->total_amount,2)
                     ],
                    "interval" => ($new_subscription->type == 1) ? "1 month" : "12 months",
                    "startDate" => ($new_subscription->type == 1) ? Carbon::now('UTC')->addMonth()->format('Y-m-d'): Carbon::now('UTC')->addYear()->format('Y-m-d'),
                    "description" => $paymentModel->item.' '.uniqid(),
                    "webhookUrl" => url("/api/mollie/subscriptions/webhook"),
                 ]);
            }

            $response = (array) $response;

            \Log::info('Mollie Subscription Response', array(
                'response' => $response
            ));

            /*
            *Update User
            */

            $user->update([
                'package_id'                => $new_subscription->package_id,
                'prev_package_subscription_id' => $current_subscription->id,
                'package_subscription_id'   => $new_subscription->id,
                'payment_id'                => $paymentModel->id,
                'payment_method'            => Config::get('constants.payment_methods')['MOLLIE'],
                'package_recurring_flag'    => 1,
                'on_trial'                  => 0,
                'on_hold_package_id'        => NULL,
                'is_expired'                => 0,
                'total_allocated_space'     => $packageLinkedFeatures[1],
                'remaining_allocated_space' => $packageLinkedFeatures[1] * 1073741824, // Multiply With 1 GB
                'max_file_size'             => $packageLinkedFeatures[2],
                'switch_to_paid_package'    => 1,
                'package_updated_by_admin'  => 0,
                'unpaid_package_email_by_admin' => 0
            ]);

            Payment::where('txn_id',$request->id)->update([
                'profile_id' => $response['id'],
                'profile_data' => $response
            ]);

            PaymentHandler::sendInvoiceEmail($paymentModel);
        }
        elseif ($payment->isOpen())
        {
            $status = 2;
        }
        elseif ($payment->isPending())
        {
            $status = 3;
        }
        elseif ($payment->isFailed())
        {
            $status = 4;
        }
        elseif ($payment->isExpired())
        {
            $status = 5;
        }
        elseif ($payment->isCanceled())
        {
            $status = 6;
            PaymentHandler::deleteRecords($paymentModel);
        }
        elseif ($payment->hasRefunds())
        {
            /*
             * The payment has been (partially) refunded.
             * The status of the payment is still "paid"
            */
            $status = 7;
        }
        elseif ($payment->hasChargebacks())
        {
            /*
            *The payment has been (partially) charged back. The status of the payment is still paid
            */
            $status = 8;
        }

        Payment::where('txn_id',$request->id)->update([
            'status' => $status,
            'data' => (array) $payment
        ]);
    }

    public function mollieSubscriptionWebhook(Request $request)
    {
        \Log::info('Mollie Subscription Webhook Response', array(
            'response' => $request->all()
        ));

        if (! $request->has('id')) {
            return;
        }

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

        \Log::info('Mollie Subscription Payment Response', array(
            'response' => (array) $payment
        ));

        if ($payment->isPaid()) 
        {
            $paymentModel = Payment::where(['profile_id' => $payment->subscriptionId])->orderBy('id','DESC')->first();
            $subscription = $paymentModel->subscription;
            $user = $paymentModel->user;
            $features = json_decode($subscription->features,true);

            /*
            *Create new subscription
            */

            $newSubscription = $subscription->replicate();
            $newSubscription->start_date = Carbon::now('UTC')->timestamp;
            if($subscription->package_id == 14)
            {
                $newSubscription->end_date = Carbon::now('UTC')->addDays(1)->timestamp;
            }
            else
            {
                $newSubscription->end_date = ($subscription->type == 1) ? Carbon::now('UTC')->addMonth()->timestamp: Carbon::now('UTC')->addYear()->timestamp;
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
            $newPaymentModel->save();

            /*
            *Update user
            */

            $user->update([
                'prev_package_subscription_id' => $subscription->id,
                'package_subscription_id'   => $newSubscription->id,
                'payment_id'                => $newPaymentModel->id,
                'payment_method'            => Config::get('constants.payment_methods')['MOLLIE'],
                'package_recurring_flag'    => 1,
                'is_expired'                => 0,
                'total_allocated_space'     => $features[1],
                'remaining_allocated_space' => $features[1] * 1073741824, // Multiply With 1 GB
                'max_file_size'             => $features[2],
                'package_updated_by_admin'  => 0,
                'unpaid_package_email_by_admin' => 0
            ]);

            PaymentHandler::sendInvoiceEmail($newPaymentModel);
        }
    }

    /**
     * Get Current Package Detail
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function getCurrentPackage(Request $request)
    {
        $user = auth()->user();

        return response()->json([
            'data' => [
                'package_subscription' => $user->subscription->setAppends(['package_image','package_title','linked_features']),
                'package_recurring_flag' => $user->package_recurring_flag,
                'package_title' => translation($user->subscription->package_id,2,$request->lang,'title',$user->subscription->package->title)
            ],
            'status' => 1,
            'message' => 'User active subscription'
        ]);
    }

    /**
     * Cancel Current Package
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function cancelCurrentPackage(Request $request)
    {
        $lang = $request->has('lang') && $request->lang != 'en' ? $request->lang : 'en';
        $lang_file = public_path('i18n/translations/'.$lang.'.json');
        $lang_arr = json_decode(file_get_contents($lang_file),true);
        
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

        return response()->json([
            'data' => auth()->user()->subscription->setAppends(['package_title','linked_features'])->makeHidden(['package']),
            'status' => 1,
            'message' => $lang_arr['alert_messages']['unsubscribe_package_success']
        ]);
    }

    /**
     * Check subscription status
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function checkStatus(Request $request)
    {
        $user = auth()->user();

        $subscription = $user->subscription;
        $currentTimestamp = Carbon::now('UTC')->timestamp;

        if(!empty($subscription->end_date) && $subscription->end_date < $currentTimestamp)
        {
            //************************//
            // Subscribe Free Package //
            //************************//

            $user->update([
                'on_hold_package_id' =>  $subscription->package_id,
                'is_expired' => 1
            ]);

            $user->update([
                'prev_package_subscription_id' => $subscription->id,
            ]);

            // $package = Package::find(2);
            // activatePackage($user->id,$package);
        }

        $response['is_expired'] = $user->is_expired;
        $response['user_status'] = $user->status;
        $response['package_updated_by_admin'] = $user->package_updated_by_admin;
        $response['unpaid_package_email_by_admin'] = $user->unpaid_package_email_by_admin;

        return response()->json([
            'data' => $response,
            'status' => 1,
            'message' => 'User subscription status'
        ]);
    }

    /**
     * remove the package updated by admin flag
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
    */

    public function updatePackageByAdminFlag()
    {
        $user = auth()->user();
        $user->update([
            'package_updated_by_admin' => 0
        ]);
        
        return response()->json([
            'status' => 1,
            'message' => 'Update package by admin flag is updated.'
        ]);
    }

    /**
     * remove the unpaid package email by admin flag
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
    */

    public function unpaidPackageEmailByAdminFlag()
    {
        $user = auth()->user();
        $user->update([
            'unpaid_package_email_by_admin' => 0
        ]);
        
        return response()->json([
            'status' => 1,
            'message' => 'Unpaid package email by admin flag is updated.'
        ]);
    }


    /**
     * Display a listing of subscriptions history.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function payments(Request $request)
    {
        $payments = Payment::where('user_id',auth()->user()->id)->whereNotNull('timestamp');

        if(!empty($request->item))
        {
            $payments = $payments->where('item', 'LIKE', '%' . $request->item . '%');
        }

        if(!empty($request->amount))
        {
            $payments = $payments->where(function($q) use ($request) {
                        $q->where('amount', 'LIKE', '%' . $request->amount . '%')
                        ->orWhere('vat_amount', 'LIKE', '%' . $request->amount . '%')
                        ->orWhere('total_amount', 'LIKE', '%' . $request->amount . '%');
                    });
        }

        $start_date = $request->start_date.' 00:00:00';
        $end_date = $request->end_date.' 23:59:59';

        if($request->has('start_date') && $request->has('end_date')){
            $payments = $payments->whereBetween('created_at', [$start_date, $end_date]);
        }

        $payments = $payments->orderBy('created_at','DESC')->paginate(10);

        return PaymentResource::collection($payments)
                ->additional([
                    'message' => 'Subscriptions history',
                    'status' => 1
        ]);
    }

    /**
     * Download payment invoice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function downloadPaymentInvoice($id, Request $request)
    {
        $lang = $request->has('lang') && $request->lang != 'en' ? $request->lang : 'en';
        $lang_file = public_path('i18n/translations/'.$lang.'.json');
        $lang_arr = json_decode(file_get_contents($lang_file),true);

        $payment = Payment::find(\Hashids::decode($id)[0]);
        $data = array();
        $data = PaymentHandler::generatePaymentInvoice($payment);
        $data['lang_arr'] = $lang_arr;
        $data['global_font_family'] = in_array($lang, ['ru','ja','zh']) ? 'chinesefont' : 'arial';
        $data['payment_font_family'] = in_array($payment->lang, ['ru','ja','zh']) ? 'chinesefont' : 'arial';
        
        $pdf = LaravelPDF::loadView('emails.invoice', $data);
        //return $pdf->stream();
        return $pdf->download('invoice '.Carbon::now('UTC')->tz($payment->user->timezone)->format('Y-m-d H.i.s').'.pdf');
    }

    public function updatetransmissionFeatures(Request $request)
    {
        $lang = $request->has('lang') && $request->lang != 'en' ? $request->lang : 'en';
        $lang_file = public_path('i18n/translations/'.$lang.'.json');
        $lang_arr = json_decode(file_get_contents($lang_file),true);

        $user = auth()->user();
        $packageFeatures = json_decode($user->subscription->features,true);
        $packageFeatures[4] = $request->transfer_expire_days;
        $packageFeatures[5] = $request->file_delete_days;

        $user->subscription->update([
            'features' => json_encode($packageFeatures)
        ]);

        return response()->json([
            'status' => 1,
            'message' => $lang_arr['alert_messages']['subscription_update_transmission_features_success']
        ]);
    }
}
