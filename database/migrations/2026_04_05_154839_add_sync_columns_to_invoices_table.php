<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'uuid')) {
                $table->uuid('uuid')->unique()->after('id');
            }
            if (!Schema::hasColumn('invoices', 'sync_status')) {
                $table->string('sync_status')->default('synced')->after('status');
            }
            if (!Schema::hasColumn('invoices', 'synced_at')) {
                $table->timestamp('synced_at')->nullable()->after('sync_status');
            }
            if (!Schema::hasColumn('invoices', 'local_updated_at')) {
                $table->timestamp('local_updated_at')->nullable()->after('synced_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['uuid', 'sync_status', 'synced_at', 'local_updated_at']);
        });
    }
};
