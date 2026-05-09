<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 36)->unique();
            $table->unsignedBigInteger('tour_id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('participants')->default(1);
            $table->text('special_requests')->nullable();
            $table->date('booking_date');
            $table->decimal('total_amount', 15, 2);
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Ajouter la clé étrangère après la création de la table
            $table->foreign('tour_id')->references('id')->on('tours')->onDelete('cascade');
            
            $table->index('status');
            $table->index('booking_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_bookings');
    }
};