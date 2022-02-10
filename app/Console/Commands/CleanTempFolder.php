<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CleanTempFolder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean_temp_folder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean Temp Folder';

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
        $path = 'storage/temp';

        if (\File::exists(public_path() . '/' . $path)) {
            $file = new Filesystem;
            $file->cleanDirectory($path);
        }
    }
}
