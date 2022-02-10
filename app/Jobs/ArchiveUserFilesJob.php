<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\User;
use Session;
use DB;
use File;
use Storage;
use LaravelPDF;
use Carbon\Carbon;
use ZipArchive;
use App\Classes\ExtendedZip;

class ArchiveUserFilesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_id;
    protected $files;
    protected $source;
    public $timeout = 21600; // timeout for job in seconds
    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $files, $source)
    {
        $this->user_id = $id;
        $this->files = $files;
        $this->source = $source;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $id = $this->user_id; // user id
        $files = $this->files; // The files to download
        $source = $this->source; // Source i.e Lawful user files or User Dashboard files
        $user = User::find($id);
        
        # create new zip object
        $zip = new ZipArchive();

        //MAKE DIRECTORY
        $upload_path = 'public/temp';
        if (!File::exists(public_path() . '/storage/temp')) 
        {
            Storage::makeDirectory($upload_path);
        }

        # create a temp file & open it
        $tmp_file = tempnam(public_path().'/storage/temp/', '');
        $zip->open($tmp_file, ZipArchive::CREATE);

        # loop through each file
        foreach ($files as $file) {
            try {
                #add it to the zip
                $zip->addFromString($file, file_get_contents(public_path('storage/users/' . $id.'/qr-codes/'.$file)));
            } catch (\Exception $e) {

            }
        }
    
        # close zip
        $zip->close();

        # getting name of temp file
        $filename = basename($tmp_file);

        # Save temp file name in db
        $user->update([
            'temp_zip_file' => $filename
        ]);
    }
}
