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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Relations
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('tax_id')->nullable()->references('id')->on('taxes')->nullOnDelete();

            // Informations de base
            $table->string('code')->nullable();
            $table->string('description');
            $table->text('notes')->nullable();

            // Quantité et prix
            $table->decimal('quantity', 12, 2)->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->string('unit')->default('piece'); // piece, hour, kg, m, etc.

            // Remises
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');

            // Taxes
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->boolean('is_taxable')->default(true);

            // Sous-totaux
            $table->decimal('subtotal', 12, 2);
            $table->decimal('total', 12, 2);

            // Ordre d'affichage
            $table->integer('sort_order')->default(0);

            // Métadonnées
            $table->json('metadata')->nullable();
            $table->json('custom_fields')->nullable();

            $table->timestamps();

            // Index pour optimiser les recherches
            $table->index('invoice_id');
            $table->index('product_id');
            $table->index('tax_id');
            $table->index('code');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
