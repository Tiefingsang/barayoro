<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer l'entreprise
        $company = Company::create([
            'name' => 'Demo Company',
            'slug' => 'demo-company',
            'email' => 'contact@demo.com',
            'is_active' => true,
        ]);

        // Créer les rôles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);

        // Créer l'utilisateur admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@barayoro.com',
            'password' => bcrypt('password'),
            'company_id' => $company->id,
        ]);
        $admin->assignRole('admin');

        $this->command->info('✓ Installation terminée !');
        $this->command->info('Email: admin@barayoro.com');
        $this->command->info('Mot de passe: password');
    }
}
