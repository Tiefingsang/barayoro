<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['member', 'manager', 'viewer'])->default('member');
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->integer('allocated_hours')->nullable();
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('removed_at')->nullable();
            $table->timestamps();

            $table->unique(['project_id', 'user_id']);
            $table->index('project_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_user');
    }
};
