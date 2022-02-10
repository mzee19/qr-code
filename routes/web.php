<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ******************** //
//     Admin Routes
// ******************** //

Route::get('admin/login', 'Auth\Admin\LoginController@login')->name('admin.auth.login');
Route::post('admin/login', 'Auth\Admin\LoginController@loginAdmin')->name('admin.auth.loginAdmin');
Route::any('admin/logout', 'Auth\Admin\LoginController@logout')->name('admin.auth.logout');

Route::get('admin/forgot-password', 'Auth\Admin\ForgotPasswordController@forgotPasswordForm')->name('admin.auth.forgot-password');
Route::post('admin/send-reset-link-email', 'Auth\Admin\ForgotPasswordController@sendResetLinkEmail')->name('admin.auth.send-reset-link-email');
Route::get('admin/reset-password/{token}', 'Auth\Admin\ForgotPasswordController@resetPasswordForm');
Route::post('admin/reset-password', 'Auth\Admin\ForgotPasswordController@resetPassword')->name('admin.auth.reset-password');

Route::group(['namespace' => 'Admin', 'as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'admin.check.status']], function () {
    Route::get('/', 'DashboardController@dashboard')->name('dashboard');
    Route::get('dashboard', 'DashboardController@dashboard')->name('dashboard');

    Route::post('ajax-received-notification', 'DashboardController@ajaxReceivedNotification');

    Route::get('settings', 'SettingController@index')->name('settings');
    Route::post('settings', 'SettingController@updateSettings')->name('update-settings');

    Route::get('profile', 'AdminController@profile')->name('profile');
    Route::post('profile', 'AdminController@updateProfile')->name('update-profile');

    Route::resource('admins', 'AdminController');

    Route::get('users/subscriptions/{id}', 'UserController@subscriptions');
    Route::get('users/payments/{id}', 'UserController@payments');
    Route::get('users/send-password/{id}', 'UserController@sendPassword');
    Route::get('users/packages/{id}', 'UserController@packages');
    Route::post('users/update-package', 'UserController@updatePackage');
    Route::resource('users', 'UserController');

    Route::resource('roles', 'RoleController');

    Route::resource('package-features', 'PackageFeatureController');
    Route::get('packages/subscriptions/{id}', 'PackageController@subscriptions');
    Route::get('packages/clone/{id}', 'PackageController@clone');
    Route::resource('packages', 'PackageController');

    Route::resource('faqs', 'FaqController');

    Route::resource('countries', 'CountryController');
    Route::resource('languages', 'LanguageController');
    Route::resource('label-translations', 'LabelTranslationController');
    Route::resource('text-translations', 'TextTranslationController');
    Route::resource('cms-pages', 'CmsPagesController');
    Route::resource('cms-page-labels', 'CmsPageLabelController');
    Route::resource('email-templates', 'EmailTemplateController');
    Route::resource('email-template-labels', 'EmailTemplateLabelController');
    Route::resource('contact-us-queries', 'ContactUsQueryController');
    Route::resource('features', 'FeaturesController');

    Route::resource('qr-code-templates', 'QrCodeTemplatesController');
    Route::get('users/{id}/qr-codes', 'QrCodeController@index')->name('users.qr.codes');
    Route::get('qr-codes/{id}/view', 'QrCodeController@show')->name('users.qr.code.show');
    Route::get('qr-codes/{id}/statistic', 'QrCodeController@statistic')->name('users.qr.code.statistic');
    Route::resource('shapes', 'ShapeController');
    Route::resource('logos', 'LogoController');
    Route::resource('guest-qr-code', 'GuestQrCodeController');

    Route::get('language-translations/partial-translate', 'LanguageTranslationController@partialTranslate');
    Route::post('language-translations/partial-translate', 'LanguageTranslationController@addPartialTranslate');
    Route::resource('language-translations', 'LanguageTranslationController');

    Route::resource('language-modules', 'LanguageModuleController');

    Route::get('payment-gateway-settings', 'PaymentGatewaySettingController@index');
    Route::post('payment-gateway-settings', 'PaymentGatewaySettingController@update');

    Route::get('lawful-interception', 'LawfulInterceptionController@index');
    Route::get('lawful-interception/user-details-pdf/{id}', 'LawfulInterceptionController@userDetailsPdf');
    Route::get('lawful-interception/user-qr-codes-pdf/{id}', 'LawfulInterceptionController@userQrCodesPdf');
    Route::get('lawful-interception/user-payments-pdf/{id}', 'LawfulInterceptionController@userPaymentsPdf');
    Route::get('lawful-interception/user-subscriptions-pdf/{id}', 'LawfulInterceptionController@userSubscriptionsPdf');
    Route::get('lawful-interception/archive-user-data/{id}', 'LawfulInterceptionController@archiveUserData');
    Route::get('lawful-interception/user-files-download/{id}', 'LawfulInterceptionController@userFilesDownload');
    Route::get('lawful-interception/download-all-data/{id}', 'LawfulInterceptionController@downloadAllData');
    Route::get('lawful-interception/check-user-temp-file/{id}', 'LawfulInterceptionController@checkUserTempFile');

    Route::get('subscribers', 'SubscriberController@index');
});

// ******************* //
//     Frontend Routes
// ******************* //

// Check User Exist
Route::post('api/check-user', 'Frontend\HomeController@checkUser');

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('forgot-password', 'Auth\ForgotPasswordController@forgotPasswordForm')->name('auth.forgot-password');
Route::get('resend-email', 'Auth\LoginController@resendEmail')->name('auth.resend-email');
Route::post('send-reset-link-email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('auth.send-reset-link-email');
Route::get('reset-password/{token}', 'Auth\ForgotPasswordController@resetPasswordForm');
Route::post('reset-password', 'Auth\ForgotPasswordController@resetPassword')->name('auth.reset-password');

Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/download-qr-code', 'HomeController@downloadQrCode')->name('download.qr.code');
    Route::get('/verify-account/{id}', 'HomeController@verifyLogin');

    Route::get('/contact-us', 'PageController@contact')->name('contact');
    Route::post('/contact-us', 'ContactUsQueryController@store');
    Route::get('/pages/{slug}', 'PageController@show');
    Route::get('/admin-qr-code','HomeController@adminQrCodeData')->name('admin.qr.code.data');

    Route::post('/qr-code-generator', 'QRCodeGeneratorController@generateQrCode')->name('qr.code.generator');
    Route::get('qr-code/{id}', 'Dashboard\GenerateQrCodeController@oldQrCodeData')->name('qr-codes.get.qr.data');
    Route::get('q/{id}', 'Dashboard\GenerateQrCodeController@getQrCodeData')->name('updated.qr-codes.get.qr.data');

    Route::post('/mollie/callback','Dashboard\SubscriptionController@mollieCallback');
    Route::post('/mollie/subscriptions/webhook', 'Dashboard\SubscriptionController@mollieSubscriptionWebhook');

    Route::post('otp-auth/verify-two-factor-authentication', 'Dashboard\OtpAuthController@verifyTwoFactorAuthentication');
    Route::get('otp-auth/reset-two-factor-authentication', 'Dashboard\OtpAuthController@resetTwoFactorAuthentication');

    Route::get('lang/{locale}', 'LocalizationController@lang');

    Route::get('/subscriptions/download-payment-invoice/{id}', 'Dashboard\SubscriptionController@downloadPaymentInvoice')->name('download.invoice');

    Route::post('/subscriber', 'HomeController@subscribe');

    // *************************** //
    //     User Dashboard Routes
    // *************************** //

    Route::group(['namespace' => 'Dashboard', 'as' => 'user.', 'middleware'=> ['auth','user.check.status']], function () {
        Route::get('/mollie/waiting','SubscriptionController@mollieWaiting')->name('mollie.waiting');
        Route::get('/mollie-confirmation','SubscriptionController@mollieConfirmation')->name('mollie.confirmation');
        Route::get('/cancel-current-package','SubscriptionController@cancelCurrentPackage')->name('cancel.current.package');

        Route::group(['middleware'=> ['check.package.subscribe.expire']],function () {
            Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
            Route::get('/updated-user-package-detail', 'DashboardController@updatedUserPackageDetail')->name('updated.user.package.detail');

            Route::get('qr-codes/{id}/statistics', 'GenerateQrCodeController@statistics')->name('qr-codes.statistics');
            Route::get('qr-codes/{id}/all-statistics', 'GenerateQrCodeController@allStatistics')->name('qr-codes.all.statistics');
            Route::get('qr-codes/template-config-data', 'GenerateQrCodeController@templateConfigData')->name('qr-codes.template.config.data');
            Route::get('qr-codes/{id}/clone', 'GenerateQrCodeController@clone')->name('qr-codes.clone');
            Route::post('qr-codes/transparent-image', 'GenerateQrCodeController@transparentImage')->name('qr-codes.transparent');
            Route::get('qr-codes/{id}/archive', 'GenerateQrCodeController@archive')->name('qr-codes.archive');
            Route::get('qr-codes/{id}/download', 'GenerateQrCodeController@download')->name('qr-codes.download');
            Route::get('qr-codes/select', 'GenerateQrCodeController@selectContentType')->name('qr-codes.select.content.type');
            Route::resource('qr-codes', 'GenerateQrCodeController');
            Route::post('/change-url', 'DomainChangeController@changeUrl')->name('qr-codes.change.url');

            Route::get('bulk-import/select', 'BulkImportController@selectContentType')->name('bulk-import.select.content.type');
            Route::get('bulk-import/index', 'BulkImportController@index')->name('bulk-import.index');
            Route::post('bulk-import/csv', 'BulkImportController@importCsv')->name('bulk-import.csv');
            Route::post('bulk-import/excel', 'BulkImportController@importExcel')->name('bulk-import.excel');
            Route::post('bulk-import/store', 'BulkImportController@store')->name('bulk-import.store');

            Route::get('qr-codes/{id}/restore', 'ArchiveController@restore')->name('qr-codes.restore');
            Route::get('archive/restore', 'ArchiveController@restoreAll')->name('archive.restore.all');
            Route::get('archive/clean', 'ArchiveController@clean')->name('archive.clean');
            Route::get('archive', 'ArchiveController@index')->name('archive.index');

            Route::resource('campaigns','CampaignController')->except('update');
            Route::get('campaigns/destroy/{id}','CampaignController@destroy')->name('campaign.destroy');
            Route::get('/supports', 'DashboardController@support')->name('support');

            Route::get('/account', 'AccountController@account')->name('account');
            Route::get('/setting', 'AccountController@setting')->name('setting');
            Route::get('/subscriptions', 'SubscriptionController@history')->name('subscriptions');
            Route::get('/invoices', 'SubscriptionController@invoices')->name('invoices');
            Route::post('/setting', 'AccountController@updateSetting')->name('update.setting');
            Route::post('/domain', 'DomainChangeController@store')->name('store.domain');
            Route::get('/domain', 'DomainChangeController@destroy')->name('delete.domain');
            Route::get('/domain/list', 'DomainChangeController@index')->name('listing.domain');
            Route::post('/domain/status', 'DomainChangeController@changeDomainStatus')->name('status.domain');

            Route::get('otp-auth/setup-two-factor-authentication', 'OtpAuthController@setupTwoFactorAuthentication');
            Route::post('otp-auth/enable-two-factor-authentication', 'OtpAuthController@enableTwoFactorAuthentication');
            Route::get('otp-auth/disable-two-factor-authentication', 'OtpAuthController@disableTwoFactorAuthentication');

            Route::get('/upgrade-package', 'SubscriptionController@upgradePackage')->name('upgrade.package');
            Route::any('/subscribe', 'SubscriptionController@subscribe')->name('subscribe');
            Route::post('/checkout', 'SubscriptionController@checkout')->name('checkout');

            Route::post('/validate-voucher', 'SubscriptionController@validateVoucher')->name('validate-voucher');
            Route::get('/expired-package-disclaimer-flag', 'SubscriptionController@expiredPackageDisclaimerFlag');
            Route::get('/update-package-by-admin-flag', 'SubscriptionController@updatePackageByAdminFlag');
            Route::get('/unpaid-package-email-by-admin-flag', 'SubscriptionController@unpaidPackageEmailByAdminFlag');
        });
    });
});

// ******************* //
//     Test Routes
// ******************* //

//Route::get('test', 'TestController@index');
//Route::get('clean-qr-code-temp', 'TestController@cleanQrCodeTemp');
//Route::get('activate-free-package', 'TestController@activateFreePackage');
//Route::get('transparent-qr-code', 'TestController@transparentQrCode');
//Route::get('image-cropper', 'TestController@imageCropper');
//Route::post('image-crop', 'TestController@imageCrop');
//Route::get('dummy-qr-code', 'TestController@qrCode');
//Route::get('dummy-qr-code/{id}/edit', 'TestController@qrCodeEdit');
//Route::get('design-package', 'TestController@designPackage');
