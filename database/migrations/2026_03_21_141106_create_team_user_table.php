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
        Schema::create('team_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->enum('role', ['member', 'leader', 'manager', 'observer'])->default('member');
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');

            $table->timestamp('joined_at')->useCurrent();
            $table->timestamp('left_at')->nullable();

            $table->json('permissions')->nullable(); // Permissions spécifiques au sein de l'équipe
            $table->json('metadata')->nullable();

            $table->timestamps();

            // Empêcher les doublons
            $table->unique(['team_id', 'user_id']);

            // Index pour optimiser
            $table->index('team_id');
            $table->index('user_id');
            $table->index('role');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_user');
    }
};
