<?php

namespace App\Console\Commands;

use App\Models\PlanSubscription;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateVendorPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:vendor-plan';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::today()->toDateString();

        $expiredSubscriptions = PlanSubscription::
        whereDate('to', '<', $today)
            ->whereIn('status', [1,0])
            ->get();

        foreach ($expiredSubscriptions as $subscription) {
            $subscription->update(['status' => 3]);
            Vendor::where('id', $subscription->vendor_id)
                ->update(['plan_id' => 1]);
        }

        $this->info('Vendor plans updated successfully.');
    }
}
