<?php
// database/migrations/2026_05_09_000003_add_referral_code_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'referral_code')) {
                $table->string('referral_code', 50)->unique()->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'credits')) {
                $table->decimal('credits', 15, 2)->default(0)->after('referral_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['referral_code', 'credits']);
        });
    }
};