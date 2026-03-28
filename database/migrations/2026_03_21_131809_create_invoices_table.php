<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->nullOnDelete();

            $table->string('invoice_number')->unique();
            $table->date('issue_date');
            $table->date('due_date');
            $table->date('paid_date')->nullable();

            $table->enum('status', ['draft', 'sent', 'pending', 'paid', 'overdue', 'cancelled'])->default('draft');
            $table->enum('type', ['invoice', 'credit_note', 'debit_note'])->default('invoice');

            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->decimal('paid', 12, 2)->default(0);
            $table->decimal('balance', 12, 2);

            $table->string('currency')->default('XOF');
            $table->decimal('exchange_rate', 10, 4)->default(1);

            $table->text('notes')->nullable();
            $table->text('terms')->nullable();

            $table->json('items')->nullable();
            $table->json('tax_details')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('client_id');
            $table->index('status');
            $table->index('due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
