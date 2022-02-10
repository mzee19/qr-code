<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\User;
use App\Models\Payment;
use App\Models\PackageSubscription;
use App\Models\GenerateQrCode;
use DataTables;
use Session;
use DB;
use File;
use Storage;
use LaravelPDF;
use Carbon\Carbon;
use ZipArchive;
use App\Classes\ExtendedZip;

class ArchiveUserAllDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_id;
    public $timeout = 21600; // timeout for job in seconds
    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->user_id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $id = $this->user_id;
        $user = User::find($id);

        $data['user'] = $user;
        $data['subscriptions'] = PackageSubscription::where('user_id',$id)->orderBy('created_at','DESC')->get();
        $data['payments'] = Payment::where('user_id',$id)->whereNotNull('timestamp')->orderBy('timestamp','DESC')->get();
        $data['qr_codes'] = GenerateQrCode::where(['user_id'=>$id,'template'=>0])->get();
        
        $files = \DB::table('generate_qr_codes')->where('user_id',$id)->pluck('image')->toArray();

        //MAKE DIRECTORY
        $upload_path = 'public/temp/user-data-'.$user->email;
        if (!File::exists(public_path() . '/storage/temp/user-data-'.$user->email)) 
        {
            Storage::makeDirectory($upload_path);
            if (File::exists(public_path() . '/storage/temp/user-data-'.$user->email))
            {
                chmod(public_path() . '/storage/temp/user-data-'.$user->email, 0777);
            } 
        }

        # Save PDFs to storage/temp/user-data subscription
        $pdf = LaravelPDF::loadView('admin.lawful-interception.user_details_pdf', $data)->save(public_path('/storage/temp/user-data-'.$user->email.'/'.$user->email.'-details.pdf'));

        if(count($data['subscriptions']) > 0 )
        {
            $pdf = LaravelPDF::loadView('admin.lawful-interception.user_subscriptions_pdf', $data)->save(public_path('/storage/temp/user-data-'.$user->email.'/'.$user->email.'-subscriptions.pdf'));
        }

        if(count($data['payments']) > 0 )
        {
            $pdf = LaravelPDF::loadView('admin.lawful-interception.user_payments_pdf', $data)->save(public_path('/storage/temp/user-data-'.$user->email.'/'.$user->email.'-payments.pdf'));
        }

        if(count($data['qr_codes']) > 0 )
        {
            $pdf = LaravelPDF::loadView('admin.lawful-interception.user_qr_codes_pdf', $data)->save(public_path('/storage/temp/user-data-'.$user->email.'/'.$user->email.'-qr-codes.pdf'));
        }

        # Save user's qr codes
        if(count($files) > 0 )
        {
            # loop through each file
            foreach ($files as $file) {
                try {
                    Storage::put('public/temp/user-data-'.$user->email.'/qr-codes'.'/'.$file, file_get_contents(public_path('storage/users/' . $id.'/qr-codes/'.$file)));
                } catch (\Exception $e) {

                }
            }
            if (File::exists(public_path() . '/storage/temp/user-data-'.$user->email.'/files'))
            {
                chmod(public_path() . '/storage/temp/user-data-'.$user->email.'/files', 0777);
            }
        }

        # zip files recursivley folder/sub folder
        ExtendedZip::zipTree(public_path() . '/storage/temp/user-data-'.$user->email, $user, ZipArchive::CREATE);
    }
}
