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
        Schema::create('sync_queue', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Relations
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Entité concernée
            $table->string('entity_type'); // task, user, project, invoice, etc.
            $table->string('entity_uuid'); // UUID de l'entité
            $table->unsignedBigInteger('entity_id')->nullable(); // ID de l'entité

            // Opération
            $table->enum('operation', ['create', 'update', 'delete', 'restore', 'sync'])->default('sync');
            $table->enum('direction', ['upload', 'download', 'both'])->default('upload');

            // Données
            $table->json('data')->nullable(); // Données à synchroniser
            $table->json('old_data')->nullable(); // Anciennes données (pour update/delete)
            $table->json('changes')->nullable(); // Différences
            $table->json('conflict_data')->nullable(); // Données en conflit

            // Statut et traitement
            $table->enum('status', [
                'pending',      // En attente de synchronisation
                'processing',   // En cours de traitement
                'completed',    // Synchronisé avec succès
                'failed',       // Échec de synchronisation
                'conflict',     // Conflit détecté
                'skipped',      // Ignoré
                'retry'         // En attente de réessai
            ])->default('pending');

            $table->integer('attempts')->default(0);
            $table->integer('max_attempts')->default(5);
            $table->timestamp('next_attempt_at')->nullable();
            $table->text('error_message')->nullable();
            $table->text('error_trace')->nullable();

            // Priorité
            $table->integer('priority')->default(0); // Plus haut = plus prioritaire
            $table->enum('priority_level', ['low', 'normal', 'high', 'urgent'])->default('normal');

            // Dates
            $table->timestamp('queued_at')->useCurrent();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Métadonnées
            $table->string('batch_id')->nullable(); // Identifiant de lot
            $table->string('correlation_id')->nullable(); // ID de corrélation
            $table->json('metadata')->nullable();
            $table->json('response')->nullable(); // Réponse du serveur

            // Client
            $table->string('client_version')->nullable(); // Version du client
            $table->string('device_id')->nullable(); // ID du périphérique
            $table->string('ip_address', 45)->nullable();

            $table->timestamps();

            // Index pour optimiser les recherches
            $table->index('company_id');
            $table->index('user_id');
            $table->index('entity_type');
            $table->index('entity_uuid');
            $table->index('entity_id');
            $table->index('operation');
            $table->index('status');
            $table->index('priority');
            $table->index('priority_level');
            $table->index('attempts');
            $table->index('queued_at');
            $table->index('next_attempt_at');
            $table->index('batch_id');
            $table->index('correlation_id');
            $table->index('created_at');

            // Index composite pour les requêtes fréquentes
            $table->index(['status', 'priority', 'queued_at']);
            $table->index(['company_id', 'status']);
            $table->index(['entity_type', 'entity_uuid', 'operation']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_queue');
    }
};
