<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CopyS3Files extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:copy-s3-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
     public function handle()
    {
        $sourceDisk = Storage::disk('s3_source');
        $destinationDisk = Storage::disk('s3');

        // List all files in the source bucket
        $files = $sourceDisk->allFiles();

        foreach ($files as $file) {
            // Read file from source bucket
            $fileContent = $sourceDisk->get($file);

            // Write file to destination bucket
            $destinationDisk->put($file, $fileContent);

            $this->info("Copied: {$file}");
        }

        $this->info('All files copied successfully!');
    }
}
