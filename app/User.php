<?php

namespace App;

use App\Models\UserDomain;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\PaymentGatewaySetting;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'original_password', 'username', 'status', 'language', 'timezone', 'profile_image', 'payment_method', 'package_subscription_id', 'package_recurring_flag', 'payment_id', 'last_login', 'login_location', 'street', 'city', 'postcode', 'country_id', 'company_name', 'company_website', 'ip_address', 'on_trial', 'package_id', 'on_hold_package_id', 'is_expired', 'switch_to_paid_package', 'otp_auth_status', 'otp_auth_secret_key', 'otp_auth_qr_image', 'mollie_customer_id', 'package_updated_by_admin', 'unpaid_package_email_by_admin', 'is_approved', 'temp_zip_file', 'platform', 'dynamic_qr_codes', 'static_qr_codes', 'qr_code_scans', 'bulk_import_limit', 'expired_package_disclaimer', 'last_quota_revised', 'is_secondary_accounts_created', 'last_active_at', 'disabled_at', 'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {

            /*
            ** Delete user's files
            */

            $path = 'storage/users/' . $model->id;
            if (\File::exists(public_path() . '/' . $path)) {
                \File::deleteDirectory(public_path() . '/' . $path);
            }

            /*
            ** Cancel subscription from mollie
            */

            $current_subscription = $model->subscription;
            $paymentGatewaySettings = PaymentGatewaySetting::first();
            if ($paymentGatewaySettings->mollie_mode == 'sandbox') {
                $mollie_api_key = $paymentGatewaySettings->mollie_sandbox_api_key;
            } else if ($paymentGatewaySettings->mollie_mode == 'live') {
                $mollie_api_key = $paymentGatewaySettings->mollie_live_api_key;
            }

            $mollie = new \Mollie\Api\MollieApiClient();
            $mollie->setApiKey($mollie_api_key);

            if (!empty($model->mollie_customer_id) && !empty($current_subscription->payment)) {
                try {
                    $customer = $mollie->customers->get($model->mollie_customer_id);
                    $response = $customer->cancelSubscription($current_subscription->payment->profile_id);
                } catch (\Mollie\Api\Exceptions\ApiException $e) {

                }
            }
        });
    }

    // ************************** //
    //        Relationships       //
    // ************************** //

    public function package()
    {
        return $this->belongsTo(Models\Package::class, 'package_id');
    }

    public function onHoldPackage()
    {
        return $this->belongsTo(Models\Package::class, 'on_hold_package_id');
    }

    public function subscription()
    {
        return $this->belongsTo(Models\PackageSubscription::class, 'package_subscription_id');
    }

    public function payment()
    {
        return $this->belongsTo(Models\Payment::class, 'payment_id');
    }

    public function allSubscriptions()
    {
        return $this->hasMany(Models\PackageSubscription::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany('App\Models\Notification', 'user_id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    public function accountSettings()
    {
        return $this->hasOne('App\Models\AccountSetting', 'user_id');
    }

    public function userDomain()
    {
        return $this->hasOne(UserDomain::class, 'user_id','id');
    }

    // ************************** //
    //  Append Extra Attributes   //
    // ************************** //

    protected $appends = ['profile_image_path', 'hash_id', 'country_name', 'vat_rate'];

    public function getProfileImagePathAttribute()
    {
        return $this->attributes['profile_image_path'] = checkImage(asset('storage/users/' . $this->id . '/profile-image/' . $this->profile_image), 'default-avatar-1.svg', $this->profile_image);
    }

    public function getHashIdAttribute()
    {
        return $this->attributes['hash_id'] = \Hashids::encode($this->id);
    }

    public function getCountryNameAttribute()
    {
        return $this->attributes['country_name'] = !empty($this->country_id) ? $this->country->name : '';
    }

    public function getVatRateAttribute()
    {
        return $this->attributes['vat_rate'] = !empty($this->country_id) && $this->country->apply_default_vat == 0 && $this->country->status == 1 ? $this->country->vat : settingValue('vat');
    }
}
