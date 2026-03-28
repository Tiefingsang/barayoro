<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->after('id');
            $table->foreignId('company_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->after('company_id')->constrained()->nullOnDelete();
            $table->foreignId('manager_id')->nullable()->after('department_id')->references('id')->on('users')->nullOnDelete();

            // Informations personnelles
            $table->string('avatar')->nullable();
            $table->string('phone')->nullable();
            $table->string('position')->nullable();
            $table->string('employee_id')->nullable();
            $table->date('hire_date')->nullable();
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'intern'])->default('full_time');

            // Configuration
            $table->json('preferences')->nullable();
            $table->json('offline_data')->nullable();
            $table->string('timezone')->default('UTC');
            $table->string('language')->default('fr');
            $table->string('theme')->default('light');

            // Statut
            $table->boolean('is_active')->default(true);
            //$table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamp('last_sync_at')->nullable();
            $table->string('last_ip')->nullable();
            $table->text('last_user_agent')->nullable();

            // Sécurité
            $table->timestamp('password_changed_at')->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('two_factor_secret')->nullable();
            $table->json('two_factor_recovery_codes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id', 'department_id', 'manager_id']);
            $table->dropColumn([
                'uuid', 'company_id', 'department_id', 'manager_id', 'avatar', 'phone',
                'position', 'employee_id', 'hire_date', 'employment_type', 'preferences',
                'offline_data', 'timezone', 'language', 'theme', 'is_active',
                'last_login_at', 'last_activity_at', 'last_sync_at', 'last_ip',
                'last_user_agent', 'password_changed_at', 'two_factor_enabled',
                'two_factor_secret', 'two_factor_recovery_codes'
            ]);
        });
    }
};
