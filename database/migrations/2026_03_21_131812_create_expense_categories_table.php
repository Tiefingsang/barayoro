<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->references('id')->on('expense_categories')->nullOnDelete();

            $table->string('name');
            $table->string('code')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color')->default('#6B7280');
            $table->string('icon')->nullable();

            $table->enum('type', ['operational', 'administrative', 'marketing', 'salary', 'tax', 'other'])->default('operational');
            $table->boolean('is_taxable')->default(true);
            $table->boolean('is_billable')->default(true);
            $table->boolean('is_active')->default(true);

            $table->decimal('budget_limit', 12, 2)->nullable();
            $table->string('budget_period')->nullable(); // monthly, quarterly, yearly

            $table->integer('sort_order')->default(0);
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('parent_id');
            $table->index('code');
            $table->index('slug');
            $table->index('type');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_categories');
    }
};
