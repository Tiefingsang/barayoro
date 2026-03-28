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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Relations
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->nullableMorphs('commentable');
            $table->foreignId('parent_id')->nullable()->references('id')->on('comments')->nullOnDelete();
            $table->foreignId('edited_by')->nullable()->references('id')->on('users')->nullOnDelete();

            // Contenu
            $table->text('content');
            $table->text('raw_content')->nullable(); // Contenu brut (markdown, HTML)
            $table->string('content_type')->default('text'); // text, markdown, html

            // Mentions et tags
            $table->json('mentions')->nullable(); // Utilisateurs mentionnés (@username)
            $table->json('tags')->nullable(); // Tags (#tag)
            $table->json('attachments')->nullable(); // Fichiers joints

            // Émotions et réactions
            $table->json('reactions')->nullable(); // [{user_id: 1, reaction: '👍'}]
            $table->integer('likes_count')->default(0);
            $table->integer('replies_count')->default(0);

            // Statut et modération
            $table->enum('status', ['published', 'pending', 'hidden', 'deleted', 'spam'])->default('published');
            $table->boolean('is_edited')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_resolved')->default(false);
            $table->boolean('is_private')->default(false); // Commentaire privé (équipe seulement)

            // Modération
            $table->foreignId('moderated_by')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->text('moderation_reason')->nullable();
            $table->timestamp('moderated_at')->nullable();

            // Dates
            $table->timestamp('edited_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('pinned_at')->nullable();

            // Métadonnées
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index pour optimiser les recherches
            $table->index('company_id');
            $table->index('user_id');
            //$table->index(['commentable_type', 'commentable_id']);
            $table->index('parent_id');
            $table->index('status');
            $table->index('is_pinned');
            $table->index('is_resolved');
            $table->index('created_at');
            $table->index('published_at');
            $table->index('likes_count');
            $table->index('replies_count');
            $table->fullText('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
