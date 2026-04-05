<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            
            $table->date('last_payment_date')->nullable()->after('subscription_price');
            $table->date('next_payment_date')->nullable()->after('last_payment_date');
            $table->string('payment_method_id')->nullable()->after('next_payment_date');
            $table->string('stripe_customer_id')->nullable()->after('payment_method_id');
            $table->string('stripe_subscription_id')->nullable()->after('stripe_customer_id');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_status', 'trial_ends_at', 'subscription_ends_at',
                'subscription_started_at', 'subscription_price', 'last_payment_date',
                'next_payment_date', 'payment_method_id', 'stripe_customer_id',
                'stripe_subscription_id'
            ]);
        });
    }
};
