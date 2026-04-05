<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modifier l'enum pour inclure 'trial'
        DB::statement("ALTER TABLE companies MODIFY COLUMN subscription_status ENUM('trial', 'active', 'expired', 'suspended', 'cancelled', 'pending') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE companies MODIFY COLUMN subscription_status ENUM('active', 'expired', 'suspended', 'pending') DEFAULT 'pending'");
    }
};
