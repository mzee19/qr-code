<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\User;
use App\Models\Package;
use Illuminate\Support\Facades\Storage;
use DB;

class MonthlyQuotaRevision extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthly_quota:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'In case a user selects Annual subscription, his assets will be renewed each month.';

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
        $current_date = Carbon::now('UTC')->subMonths(1)->format('Y-m-d');
        $users = User::whereNotNull('last_quota_revised')->whereRaw('DATE(last_quota_revised) = ?',$current_date)->get();

        if(!$users->isEmpty())
        {
            foreach ($users as $user)
            { 
                $package = Package::find($user->package_id);
                $packageLinkedFeatures = $package->linkedFeatures->pluck('count','feature_id')->toArray();

                $user->update([
                    'dynamic_qr_codes' => array_key_exists(1, $packageLinkedFeatures) ? $packageLinkedFeatures[1] : null,
                'static_qr_codes' => array_key_exists(2, $packageLinkedFeatures) ? $packageLinkedFeatures[2] : null,
                'qr_code_scans' => array_key_exists(3, $packageLinkedFeatures) ? $packageLinkedFeatures[3] : null,
                'bulk_import_limit' => array_key_exists(13, $packageLinkedFeatures) ? $packageLinkedFeatures[13] : null,
                    'last_quota_revised' => date("Y-m-d H:i:s")
                ]);
    
            }
        }
    }
}
