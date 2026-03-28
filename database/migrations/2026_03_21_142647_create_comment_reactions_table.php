<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comment_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('reaction', 50); // 👍, ❤️, 😂, 😮, 😢, 😡, etc.
            $table->timestamps();

            $table->unique(['comment_id', 'user_id', 'reaction']);
            $table->index('reaction');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_reactions');
    }
};
