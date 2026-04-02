<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer les permissions
        $permissions = [
            // Utilisateurs
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',

            // Tâches
            'view_tasks',
            'create_tasks',
            'edit_tasks',
            'delete_tasks',
            'assign_tasks',

            // Projets
            'view_projects',
            'create_projects',
            'edit_projects',
            'delete_projects',

            // Clients
            'view_clients',
            'create_clients',
            'edit_clients',
            'delete_clients',

            // Dans le tableau $permissions, ajoutez :
'view_orders',
'create_orders',
'edit_orders',
'delete_orders',
'manage_orders_status',

            // Factures
            'view_invoices',
            'create_invoices',
            'edit_invoices',
            'delete_invoices',
            'pay_invoices',

            // Rapports
            'view_reports',
            'export_reports',

            // Paramètres
            'manage_settings',
            'manage_company',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Créer les rôles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);

        // Admin : toutes les permissions
        $adminRole->syncPermissions(Permission::all());

        // Manager : permissions spécifiques
        $managerRole->syncPermissions([
            'view_users', 'create_users', 'edit_users',
            'view_tasks', 'create_tasks', 'edit_tasks', 'assign_tasks',
            'view_projects', 'create_projects', 'edit_projects',
            'view_clients', 'create_clients', 'edit_clients',
            'view_invoices', 'create_invoices', 'edit_invoices',
            'view_reports', 'export_reports',
        ]);

        // Employee : permissions limitées
        $employeeRole->syncPermissions([
            'view_tasks', 'edit_tasks',
            'view_projects',
            'view_clients',
        ]);

        // Vérifier que l'utilisateur admin existe et a le rôle admin
        $adminUser = User::where('email', 'admin@barayoro.com')->first();

        if ($adminUser) {
            $adminUser->syncRoles(['admin']);
            $this->command->info('✓ Admin user updated with admin role');
        } else {
            $this->command->warn('Admin user not found. Please create one.');
        }

        $this->command->info('✓ Rôles et permissions créés avec succès !');
        $this->command->info('  - admin: toutes les permissions');
        $this->command->info('  - manager: permissions de gestion');
        $this->command->info('  - employee: accès limité');
    }
}
