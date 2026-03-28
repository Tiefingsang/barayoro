<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->foreignId('parent_task_id')->nullable()->references('id')->on('tasks')->nullOnDelete();

            $table->string('code')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'review', 'completed', 'cancelled'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');

            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('completed_at')->nullable();

            $table->integer('estimated_hours')->nullable();
            $table->integer('actual_hours')->nullable();
            $table->integer('progress')->default(0);

            // Offline sync
            $table->string('sync_status')->default('synced');
            $table->json('pending_changes')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamp('local_updated_at')->nullable();

            $table->json('attachments')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('assigned_to');
            $table->index('status');
            $table->index('due_date');
            $table->index('sync_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
