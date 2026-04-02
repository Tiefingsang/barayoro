<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('activitable_id')->nullable();
            $table->string('activitable_type')->nullable();
            $table->string('action');
            $table->string('description');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // Index personnalisés
            $table->index(['activitable_type', 'activitable_id'], 'activities_morph_index');
            $table->index(['company_id', 'created_at'], 'activities_company_created_index');
            $table->index('user_id', 'activities_user_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
