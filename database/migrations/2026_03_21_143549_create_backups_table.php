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
        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Relations
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->foreignId('restored_by')->nullable()->references('id')->on('users')->nullOnDelete();

            // Informations de base
            $table->string('name');
            $table->string('description')->nullable();
            $table->enum('type', [
                'full',         // Sauvegarde complète
                'database',     // Base de données uniquement
                'files',        // Fichiers uniquement
                'incremental',  // Sauvegarde incrémentale
                'differential'  // Sauvegarde différentielle
            ])->default('full');

            // Fichier
            $table->string('filename');
            $table->string('path');
            $table->string('disk')->default('local');
            $table->string('mime_type')->nullable();
            $table->integer('size')->nullable();
            $table->string('hash')->nullable();
            $table->string('checksum')->nullable(); // MD5, SHA256 pour vérification

            // Compression
            $table->boolean('is_compressed')->default(true);
            $table->string('compression_type')->default('zip'); // zip, gzip, tar, etc.
            $table->integer('compression_level')->default(6);

            // Chiffrement
            $table->boolean('is_encrypted')->default(false);
            $table->string('encryption_method')->nullable(); // aes-256-cbc, etc.

            // Contenu
            $table->json('tables')->nullable(); // Tables incluses
            $table->json('directories')->nullable(); // Répertoires inclus
            $table->json('excluded_tables')->nullable(); // Tables exclues
            $table->json('excluded_directories')->nullable(); // Répertoires exclus

            // Statut
            $table->enum('status', [
                'pending',      // En attente
                'processing',   // En cours
                'completed',    // Terminé
                'failed',       // Échec
                'partial',      // Partiel
                'cancelled'     // Annulé
            ])->default('pending');

            $table->enum('retention_policy', [
                'daily',        // 1 jour
                'weekly',       // 7 jours
                'monthly',      // 30 jours
                'quarterly',    // 90 jours
                'yearly',       // 365 jours
                'forever'       // Illimité
            ])->default('weekly');

            // Dates
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // Date d'expiration
            $table->timestamp('restored_at')->nullable(); // Date de restauration

            // Performance
            $table->integer('duration_seconds')->nullable(); // Durée en secondes
            $table->integer('memory_usage')->nullable(); // Mémoire utilisée en MB
            $table->integer('cpu_usage')->nullable(); // CPU utilisé en pourcentage

            // Résultats
            $table->text('error_message')->nullable();
            $table->json('error_details')->nullable();
            $table->json('summary')->nullable(); // Résumé de la sauvegarde
            $table->json('logs')->nullable(); // Logs de la sauvegarde

            // Notifications
            $table->boolean('notify_on_success')->default(true);
            $table->boolean('notify_on_failure')->default(true);
            $table->json('notification_recipients')->nullable(); // Emails pour notifications

            // Planification
            $table->boolean('is_scheduled')->default(false);
            $table->string('schedule_cron')->nullable(); // Expression CRON
            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('next_run_at')->nullable();

            // Métadonnées
            $table->json('metadata')->nullable();
            $table->json('environment')->nullable(); // Environnement au moment de la sauvegarde

            // Versioning
            $table->string('app_version')->nullable(); // Version de l'application
            $table->string('database_version')->nullable(); // Version de la base de données
            $table->string('php_version')->nullable(); // Version PHP

            $table->timestamps();
            $table->softDeletes();

            // Index pour optimiser les recherches
            $table->index('company_id');
            $table->index('created_by');
            $table->index('type');
            $table->index('status');
            $table->index('retention_policy');
            $table->index('started_at');
            $table->index('completed_at');
            $table->index('expires_at');
            $table->index('is_scheduled');
            $table->index('next_run_at');
            $table->index('size');
            $table->index('created_at');

            // Index composite
            $table->index(['company_id', 'status', 'type']);
            $table->index(['company_id', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backups');
    }
};
