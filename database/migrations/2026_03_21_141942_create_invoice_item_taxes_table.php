<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_item_taxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tax_id')->constrained()->cascadeOnDelete();
            $table->decimal('rate', 5, 2);
            $table->decimal('amount', 12, 2);
            $table->timestamps();

            $table->unique(['invoice_item_id', 'tax_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_item_taxes');
    }
};
