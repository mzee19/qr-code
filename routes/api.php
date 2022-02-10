<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'Api'], function ()
{
	Route::post('auth/register', 'AuthController@register');
	Route::post('auth/login', 'AuthController@login');
	Route::post('auth/logout', 'AuthController@logout');
	Route::get('auth/profile', 'AuthController@profile');
	Route::post('auth/update-profile', 'AuthController@updateProfile');
	Route::get('auth/account-settings', 'AuthController@accountSettings');
	Route::post('auth/update-account-settings', 'AuthController@updateAccountSettings');
	Route::get('auth/verify-account/{id}', 'AuthController@verifyAccount');
	Route::post('auth/resend-verification-email', 'AuthController@resendVerificationEmail');

	Route::post('auth/password/send-reset-link', 'PasswordResetController@sendResetLink');
	Route::get('auth/password/validate-reset-token/{token}', 'PasswordResetController@validateResetToken');
	Route::post('auth/password/reset', 'PasswordResetController@reset');

	Route::get('otp-auth/setup-two-factor-authentication', 'OtpAuthController@setupTwoFactorAuthentication');
	Route::get('otp-auth/enable-two-factor-authentication', 'OtpAuthController@enableTwoFactorAuthentication');
	Route::get('otp-auth/disable-two-factor-authentication', 'OtpAuthController@disableTwoFactorAuthentication');
	Route::get('otp-auth/get-two-factor-authentication-data', 'OtpAuthController@getTwoFactorAuthenticationData');
	Route::post('otp-auth/verify-two-factor-authentication', 'OtpAuthController@verifyTwoFactorAuthentication');
	Route::get('otp-auth/reset-two-factor-authentication', 'OtpAuthController@resetTwoFactorAuthentication');

	Route::get('faqs', 'ListingController@faqs');
	Route::get('languages', 'ListingController@languages');
	Route::get('timezones', 'ListingController@timezones');
	Route::get('settings', 'ListingController@settings');
	Route::get('countries', 'ListingController@countries');
	Route::get('get-country-vat', 'ListingController@getCountryVat');
	Route::get('packages', 'ListingController@packages');
	Route::get('package-detail', 'ListingController@packageDetail');
	Route::get('payment-gateway-settings', 'ListingController@paymentGatewaySetting');
	Route::get('features', 'ListingController@features');
	Route::get('services', 'ListingController@services');
	Route::get('home-contents', 'ListingController@homeContents');
	Route::get('get-geo-location', 'ListingController@getGeoLocation');

	Route::post('subscription/payment-checkout', 'SubscriptionController@paymentCheckout');
	Route::post('subscription/paypal-checkout-success', 'SubscriptionController@paypalCheckoutSuccess');
	Route::post('subscription/payment-checkout-cancel', 'SubscriptionController@paymentCheckoutCancel');
	Route::post('subscription/update-transmission-features', 'SubscriptionController@updatetransmissionFeatures');

	Route::get('subscription/get-current-package', 'SubscriptionController@getCurrentPackage');
	Route::get('subscription/cancel-current-package', 'SubscriptionController@cancelCurrentPackage');
	Route::get('subscription/check-status', 'SubscriptionController@checkStatus');
	Route::get('subscription/update-package-by-admin-flag', 'SubscriptionController@updatePackageByAdminFlag');
	Route::get('subscription/unpaid-package-email-by-admin-flag', 'SubscriptionController@unpaidPackageEmailByAdminFlag');
	Route::get('subscription/payments', 'SubscriptionController@payments');
	Route::get('subscription/download-payment-invoice/{id}', 'SubscriptionController@downloadPaymentInvoice');

	Route::get('cms-pages', 'CmsPageController@index');
	Route::get('cms-pages/detail', 'CmsPageController@detail');

	Route::post('contact-us-query/store','ContactUsQueryController@store');

	Route::post('mollie/callback', 'SubscriptionController@molliePayment');
	Route::post('mollie/verify-order', 'SubscriptionController@molliePaymentVerify');
	Route::post('mollie/subscriptions/webhook', 'SubscriptionController@mollieSubscriptionWebhook');
});
