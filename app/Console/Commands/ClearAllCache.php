<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearAllCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears all caches and temporary files including Laravel logs';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Clear application cache
        $this->call('cache:clear');
        $this->info('Application cache cleared.');

        // Clear config cache
        $this->call('config:clear');
        $this->info('Configuration cache cleared.');

        // Clear route cache
        $this->call('route:clear');
        $this->info('Route cache cleared.');

        // Clear compiled views
        $this->call('view:clear');
        $this->info('Compiled views cleared.');

        // Clear Composer cache
        exec('composer clear-cache', $output, $returnVar);
        if ($returnVar === 0) {
            $this->info('Composer cache cleared.');
        } else {
            $this->error('Failed to clear Composer cache.');
            foreach ($output as $line) {
                $this->line($line);
            }
        }

        // Clear session files
        exec('rm -rf storage/framework/sessions/*');
        $this->info('Session files cleared.');

        // Clear temporary files
        exec('rm -rf storage/framework/cache/data/*');
        $this->info('Laravel temporary files cleared.');

        // Clear log files
        exec('rm -rf storage/logs/*');
        $this->info('Log files cleared.');

        return 0;
    }
}
