<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'uuid')) {
                $table->uuid('uuid')->unique()->after('id');
            }
            if (!Schema::hasColumn('products', 'sync_status')) {
                $table->string('sync_status')->default('synced')->after('is_active');
            }
            if (!Schema::hasColumn('products', 'synced_at')) {
                $table->timestamp('synced_at')->nullable()->after('sync_status');
            }
            if (!Schema::hasColumn('products', 'local_updated_at')) {
                $table->timestamp('local_updated_at')->nullable()->after('synced_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['uuid', 'sync_status', 'synced_at', 'local_updated_at']);
        });
    }
};
