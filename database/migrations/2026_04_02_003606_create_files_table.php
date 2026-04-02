<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['folder', 'file']);
            $table->foreignId('parent_id')->nullable()->constrained('files')->onDelete('cascade');
            $table->string('mime_type')->nullable();
            $table->bigInteger('size')->default(0);
            $table->string('extension', 10)->nullable();
            $table->string('path')->nullable();
            $table->string('url')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['company_id', 'parent_id']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
