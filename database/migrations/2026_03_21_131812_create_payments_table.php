<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Relations
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('received_by')->nullable()->references('id')->on('users')->nullOnDelete();
            //$table->foreignId('bank_account_id')->nullable()->references('id')->on('bank_accounts')->nullOnDelete();

            // Informations de paiement
            $table->string('payment_number')->unique();
            $table->date('payment_date');
            $table->date('deposit_date')->nullable();
            $table->time('payment_time')->nullable();

            // Montants
            $table->decimal('amount', 12, 2);
            $table->decimal('fee_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2);
            $table->decimal('exchange_rate', 10, 4)->default(1);
            $table->string('currency', 3)->default('XOF');
            $table->string('received_currency', 3)->nullable();

            // Méthode de paiement
            $table->enum('method', [
                'cash',
                'bank_transfer',
                'check',
                'credit_card',
                'debit_card',
                'mobile_money',
                'paypal',
                'stripe',
                'flutterwave',
                'orange_money',
                'wave',
                'other'
            ])->default('cash');

            // Références
            $table->string('reference')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('check_number')->nullable();
            $table->string('card_last4')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('mobile_operator')->nullable();

            // Informations bancaires
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_swift')->nullable();
            $table->string('sender_name')->nullable();
            $table->string('sender_account')->nullable();

            // Statut
            $table->enum('status', [
                'pending',
                'processing',
                'completed',
                'failed',
                'refunded',
                'cancelled',
                'on_hold'
            ])->default('pending');

            $table->enum('confirmation_status', [
                'pending',
                'verified',
                'confirmed',
                'rejected'
            ])->default('pending');

            // Dates de traitement
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            // Notes
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('failure_reason')->nullable();

            // Reçus et documents
            $table->string('receipt_path')->nullable();
            $table->string('proof_path')->nullable();
            $table->json('attachments')->nullable();

            // Métadonnées
            $table->json('metadata')->nullable();
            $table->json('payment_details')->nullable();
            $table->json('webhook_data')->nullable();

            // Sécurité
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index pour optimiser les recherches
            $table->index('company_id');
            $table->index('invoice_id');
            $table->index('client_id');
            $table->index('received_by');
            //$table->index('bank_account_id');
            $table->index('payment_number');
            $table->index('payment_date');
            $table->index('method');
            $table->index('status');
            $table->index('confirmation_status');
            $table->index('transaction_id');
            $table->index('reference');
            $table->index('amount');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
