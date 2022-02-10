<?php

namespace App\Console\Commands;

use App\Models\EmailTemplate;
use App\Models\Package;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PackageSubscriptionExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package_subscription_expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Package Subscription Expired';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::where('package_id', '!=', 2)->where('package_recurring_flag', '!=' , 1)->get();

        foreach ($users as $user) {
            $subscription = $user->subscription;
            $currentTimestamp = Carbon::now('UTC')->timestamp;

            if (!empty($subscription->end_date) && $subscription->end_date < $currentTimestamp) {

                //************************//
                // Subscribe Free Package //
                //************************//

                $user->update([
                    'is_expired' => 0,
                    'on_hold_package_id' => $subscription->package_id,
                    'package_recurring_flag' => 0,
                    'switch_to_paid_package' => 0,
                    'package_updated_by_admin' => 0,
                    'unpaid_package_email_by_admin' => 0,
                    'expired_package_disclaimer' => 1,
                    'last_quota_revised' => NULL,
                ]);

                $package = Package::find(2);
                activatePackage($user->id, $package);

                // ****************************************************//
                // Send Email About Package downgraded to free package  //
                // *************************************************** //

                $email_template = EmailTemplate::where('type', 'package_downgrade_after_subscription_expired')->first();
                $name = $user->name;
                $email = $user->email;
                $upgrade_link = url('/upgrade-package');
                $contact_link = url('/contact-us');
                $subject = $email_template->subject;
                $content = $email_template->content;

                $search = array("{{name}}", "{{from}}", "{{to}}", "{{upgrade_link}}", "{{contact_link}}", "{{app_name}}");
                $replace = array($name, $subscription->package_title, $package->title, $upgrade_link, $contact_link, env('APP_NAME'));
                $content = str_replace($search, $replace, $content);

                sendEmail($email, $subject, $content);
            }
        }
    }
}
