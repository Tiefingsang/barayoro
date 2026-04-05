<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;

class CheckExpiredSubscriptions extends Command
{
    protected $signature = 'subscriptions:check-expired';
    protected $description = 'Check and update expired subscriptions';

    public function handle()
    {
        // Expirer les essais
        Company::where('subscription_status', 'trial')
               ->where('trial_ends_at', '<', now())
               ->update(['subscription_status' => 'expired']);

        // Expirer les abonnements
        Company::where('subscription_status', 'active')
               ->where('subscription_ends_at', '<', now())
               ->update(['subscription_status' => 'expired']);

        $this->info('Expired subscriptions updated successfully.');
    }
}
