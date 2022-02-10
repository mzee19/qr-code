<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class DomainVerifyStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Domain verify status';

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
        $users = User::where(['is_approved' => 1, 'status' => 1])->get();

        foreach ($users as $user) {

            $data['user'] = $user;
            $data['action'] = 'listing';

            $userDomain = $user->userDomain;
            if (isset($userDomain->domain)) {
                if (!$userDomain->is_verified) {
                    $response = getNedLinkDomainName($data);
                    foreach ($response['domains'] as $domain) {
                        if ($domain['isVerified']) {
                            if ($domain['name'] == $userDomain->domain) {
                                $data['user']->userDomain->update(['is_verified' => true]);
                            }
                        }
                    }
                }
            }
        }
        echo 'Verify domain';

    }
}
