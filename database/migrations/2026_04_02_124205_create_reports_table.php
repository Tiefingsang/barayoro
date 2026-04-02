<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['daily', 'weekly', 'monthly', 'quarterly', 'annual', 'custom']);
            $table->enum('format', ['pdf', 'word', 'excel']);
            $table->json('filters')->nullable();
            $table->json('data')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('size')->nullable();
            $table->integer('download_count')->default(0);
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'type', 'generated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
