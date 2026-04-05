<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            if (!Schema::hasColumn('departments', 'uuid')) {
                $table->uuid('uuid')->unique()->after('id');
            }
            if (!Schema::hasColumn('departments', 'sync_status')) {
                $table->string('sync_status')->default('synced')->after('is_active');
            }
            if (!Schema::hasColumn('departments', 'synced_at')) {
                $table->timestamp('synced_at')->nullable()->after('sync_status');
            }
            if (!Schema::hasColumn('departments', 'local_updated_at')) {
                $table->timestamp('local_updated_at')->nullable()->after('synced_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn(['uuid', 'sync_status', 'synced_at', 'local_updated_at']);
        });
    }
};
