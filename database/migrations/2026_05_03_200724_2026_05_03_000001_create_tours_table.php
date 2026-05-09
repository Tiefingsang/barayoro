<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 36)->unique();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('category')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->integer('duration_days')->default(1);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('max_participants')->default(10);
            $table->string('location');
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('status')->default('active');
            $table->integer('views')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('is_active');
            $table->index('start_date');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};