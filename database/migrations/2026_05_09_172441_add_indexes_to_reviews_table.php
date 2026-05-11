<?php
// database/migrations/2026_05_09_173100_add_indexes_to_reviews_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Vérifier si l'index n'existe pas avant de l'ajouter
            if (!Schema::hasIndex('reviews', ['reviewable_type', 'reviewable_id'])) {
                $table->index(['reviewable_type', 'reviewable_id']);
            }
            if (!Schema::hasIndex('reviews', ['status'])) {
                $table->index('status');
            }
            if (!Schema::hasIndex('reviews', ['rating'])) {
                $table->index('rating');
            }
            if (!Schema::hasIndex('reviews', ['company_id'])) {
                $table->index('company_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['reviewable_type', 'reviewable_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['rating']);
            $table->dropIndex(['company_id']);
        });
    }
};