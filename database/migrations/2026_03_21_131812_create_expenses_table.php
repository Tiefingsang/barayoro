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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Relations
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('expense_category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->foreignId('paid_by')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->foreignId('vendor_id')->nullable()->references('id')->on('clients')->nullOnDelete();

            // Informations de base
            $table->string('expense_number')->unique();
            $table->date('expense_date');
            $table->date('due_date')->nullable();
            $table->date('paid_date')->nullable();

            // Montants
            $table->decimal('amount', 12, 2);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->string('currency')->default('XOF');
            $table->decimal('exchange_rate', 10, 4)->default(1);

            // Description
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();

            // Statuts
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'paid', 'cancelled'])->default('draft');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'check', 'credit_card', 'mobile_money', 'other'])->nullable();
            $table->enum('recurrence', ['none', 'daily', 'weekly', 'monthly', 'yearly'])->default('none');

            // Informations de paiement
            $table->string('payment_reference')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('check_number')->nullable();

            // Documents
            $table->string('receipt_path')->nullable();
            $table->string('invoice_path')->nullable();
            $table->json('attachments')->nullable();

            // Approbation
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('paid_at')->nullable();

            // Métadonnées
            $table->json('metadata')->nullable();
            $table->json('tax_details')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index pour optimiser les recherches
            $table->index('company_id');
            $table->index('expense_category_id');
            $table->index('project_id');
            $table->index('created_by');
            $table->index('approved_by');
            $table->index('vendor_id');
            $table->index('expense_number');
            $table->index('expense_date');
            $table->index('due_date');
            $table->index('paid_date');
            $table->index('status');
            $table->index('payment_method');
            $table->index('recurrence');
            $table->index('created_at');
            $table->index('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
