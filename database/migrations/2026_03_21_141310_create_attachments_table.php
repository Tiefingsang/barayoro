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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Relations
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Polymorphic relation
            $table->nullableMorphs('attachable');

            // Informations du fichier
            $table->string('name');
            $table->string('filename');
            $table->string('path');
            $table->string('disk')->default('public');
            $table->string('mime_type')->nullable();
            $table->string('extension')->nullable();
            $table->integer('size');
            $table->string('hash')->nullable();

            // Métadonnées
            $table->enum('visibility', ['public', 'private'])->default('private');
            $table->boolean('is_image')->default(false);
            $table->boolean('is_thumbnail')->default(false);
            $table->boolean('is_compressed')->default(false);

            // Dimensions pour les images
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();

            // Informations supplémentaires
            $table->string('thumbnail_path')->nullable();
            $table->string('original_name')->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->json('exif_data')->nullable(); // Données EXIF pour les images

            // Sécurité
            $table->timestamp('expires_at')->nullable();
            $table->string('access_token')->nullable()->unique();
            $table->integer('download_count')->default(0);

            // Statut
            $table->enum('status', ['pending', 'processing', 'ready', 'failed', 'deleted'])->default('ready');
            $table->timestamp('processed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index pour optimiser les recherches
            $table->index('company_id');
            $table->index('user_id');
            //$table->index(['attachable_type', 'attachable_id']);
            $table->index('status');
            $table->index('visibility');
            $table->index('mime_type');
            $table->index('size');
            $table->index('created_at');
            $table->index('expires_at');
            $table->index('access_token');
            $table->index('hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
