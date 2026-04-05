<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;

class FixCompanySubscriptions extends Command
{
    protected $signature = 'subscriptions:fix';
    protected $description = 'Fix company subscription statuses';

    public function handle()
    {
        $companies = Company::all();

        foreach ($companies as $company) {
            // Si l'entreprise a une date d'expiration d'essai mais status incorrect
            if ($company->trial_ends_at && $company->subscription_status !== 'trial') {
                $company->update(['subscription_status' => 'trial']);
                $this->line("Fixed: {$company->name} -> trial");
            }

            // Si l'entreprise a un abonnement actif
            if ($company->subscription_expires_at && $company->subscription_expires_at->isFuture()) {
                if ($company->subscription_status !== 'active') {
                    $company->update(['subscription_status' => 'active']);
                    $this->line("Fixed: {$company->name} -> active");
                }
            }

            // Si l'entreprise est en essai mais sans date
            if ($company->subscription_status === 'trial' && !$company->trial_ends_at) {
                $company->update([
                    'trial_ends_at' => now()->addDays(30),
                    'subscription_status' => 'trial',
                ]);
                $this->line("Fixed: {$company->name} -> added trial end date");
            }
        }

        $this->info('All subscriptions fixed!');
    }
}
