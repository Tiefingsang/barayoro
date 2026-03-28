<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();

            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();

            $table->enum('type', ['product', 'service'])->default('product');
            $table->string('unit')->default('piece');

            $table->decimal('purchase_price', 12, 2)->nullable();
            $table->decimal('selling_price', 12, 2);
            $table->decimal('tax_rate', 5, 2)->default(0);

            $table->integer('stock_quantity')->default(0);
            $table->integer('min_stock_quantity')->default(0);
            $table->integer('max_stock_quantity')->nullable();

            $table->string('sku')->unique()->nullable();
            $table->string('barcode')->nullable();

            $table->json('images')->nullable();
            $table->json('attributes')->nullable();
            $table->json('metadata')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_taxable')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('code');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
