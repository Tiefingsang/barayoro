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
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->string('registration_ip')->nullable()->after('password');
            $table->integer('login_count')->default(0)->after('last_login_ip');
           // $table->timestamp('password_changed_at')->nullable()->after('remember_token');
            $table->string('password_changed_ip')->nullable()->after('password_changed_at');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
