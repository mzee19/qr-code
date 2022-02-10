<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Storage;
use DB;

class SoftDeleteUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soft_delete:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Soft delete users after specific number of days.';

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
        $account_soft_delete_time_limit_days =  settingValue('account_soft_delete_time_limit');

        if(isset($account_soft_delete_time_limit_days))
        {
            $users = User::whereNotNull('disabled_at')->get();

            if(!$users->isEmpty())
            {
                foreach ($users as $user)
                {
                    $user_soft_deleted_date = Carbon::createFromTimeStamp(strtotime($user->disabled_at), "UTC")->addDays($account_soft_delete_time_limit_days);
                    $current_date = Carbon::now('UTC');

                    if($current_date->gt($user_soft_deleted_date))
                    {
                        $user->update([
                            'status' => 3,
                            'deleted_at' => date("Y-m-d H:i:s")
                        ]);
                    }
                }
            }
        }
    }
}
