<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_shares', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('file_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('token', 64)->unique();
            $table->string('password')->nullable();
            $table->string('email')->nullable(); // Email de la personne avec qui on partage
            $table->enum('permission', ['view', 'download', 'edit'])->default('view');
            $table->integer('max_downloads')->nullable(); // Nombre maximum de téléchargements
            $table->integer('download_count')->default(0); // Nombre de téléchargements effectués
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamps();

            // Index pour optimiser les recherches
            $table->index('token');
            $table->index(['file_id', 'user_id']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_shares');
    }
};
