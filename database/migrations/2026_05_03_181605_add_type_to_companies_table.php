<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Vérifier si la colonne n'existe pas avant de l'ajouter
            if (!Schema::hasColumn('companies', 'type')) {
                $table->string('type')->nullable()->after('name');
            }
            
            if (!Schema::hasColumn('companies', 'type_slug')) {
                $table->string('type_slug')->nullable()->after('type');
            }
            
            if (!Schema::hasColumn('companies', 'logo')) {
                $table->string('logo')->nullable()->after('type_slug');
            }
            
            if (!Schema::hasColumn('companies', 'siret')) {
                $table->string('siret')->nullable()->after('logo');
            }
            
            if (!Schema::hasColumn('companies', 'country')) {
                $table->string('country')->nullable()->after('siret');
            }
            
            if (!Schema::hasColumn('companies', 'settings')) {
                $table->json('settings')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'type', 'type_slug', 'logo', 'siret', 'country', 'settings'
            ]);
        });
    }
};