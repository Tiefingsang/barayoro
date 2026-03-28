<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('logo')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('registration_number')->nullable();

            // Abonnement annuel
            $table->enum('subscription_status', ['active', 'expired', 'suspended', 'pending'])->default('pending');
            $table->timestamp('subscription_started_at')->nullable();
            $table->timestamp('subscription_expires_at')->nullable();
            $table->timestamp('subscription_renewal_at')->nullable();
            $table->decimal('subscription_price', 10, 2)->nullable();
            $table->string('subscription_invoice_id')->nullable();

            // Limites
            $table->integer('max_users')->default(10);
            $table->integer('max_storage_mb')->default(1024);
            $table->boolean('unlimited_users')->default(false);

            // Statut
            $table->boolean('is_active')->default(true);
            $table->boolean('is_trial')->default(false);
            $table->timestamp('trial_ends_at')->nullable();

            // Configuration
            $table->json('settings')->nullable();
            $table->json('offline_settings')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('slug');
            $table->index('subscription_status');
            $table->index('subscription_expires_at');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
