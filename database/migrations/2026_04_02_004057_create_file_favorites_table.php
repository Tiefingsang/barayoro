<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('file_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Empêcher les doublons
            $table->unique(['user_id', 'file_id']);

            // Index pour optimiser les requêtes
            $table->index('user_id');
            $table->index('file_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_favorites');
    }
};
