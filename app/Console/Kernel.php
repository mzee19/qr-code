<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\DisableUsers::class,
        Commands\SoftDeleteUsers::class,
        Commands\DeleteUsers::class,
        Commands\MonthlyQuotaRevision::class,
        Commands\SubscriptionExpiredEmailNotifications::class,
        Commands\AccountInactivityEmailNotifications::class,
//        Commands\CleanTempFolder::class,
        Commands\PackageSubscriptionExpired::class,
        Commands\DomainVerifyStatus::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('disable:users')->daily();
        $schedule->command('soft_delete:users')->daily();
        $schedule->command('delete:users')->daily();
        $schedule->command('monthly_quota:users')->daily();
        $schedule->command('subscription_expired:notifications')->daily();
        $schedule->command('account_inactivity:notifications')->daily();
//        $schedule->command('clean_temp_folder')->daily();
        $schedule->command('package_subscription_expired')->daily();
        $schedule->command('domain:verify')->everyThirtyMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
