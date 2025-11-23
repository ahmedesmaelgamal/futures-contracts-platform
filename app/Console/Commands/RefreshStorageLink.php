<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RefreshStorageLink extends Command
{

    protected $signature = 'refresh:storage-link';
    protected $description = 'Delete public/storage and contents of storage/app/public, then create new storage link.';



    public function handle()
    {
        $this->info('Refreshing public storage link...');

        $publicStoragePath = public_path('storage');
        if (is_link($publicStoragePath) || file_exists($publicStoragePath)) {
            unlink($publicStoragePath);
            $this->info('Deleted old public/storage link.');
        }

        $storagePath = storage_path('app/public');
        $files = \File::allFiles($storagePath);
        $directories = \File::directories($storagePath);

        foreach ($files as $file) {
            \File::delete($file);
        }
        foreach ($directories as $dir) {
            \File::deleteDirectory($dir);
        }
        $this->info('Cleared files and folders inside storage/app/public.');

        Artisan::call('storage:link');
        $this->info('New storage link created successfully.');
    }

}
