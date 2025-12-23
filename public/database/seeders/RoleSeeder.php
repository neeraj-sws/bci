<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'admin' => [
                'dashboard view',

                'park view',
                'park create',
                'park edit',
                'park delete',
                'zone view',
                'zone create',
                'zone edit',
                'zone delete',
                'gates view',
                'gates create',
                'gates edit',
                'gates delete',
                'taxi view',
                'taxi create',
                'taxi edit',
                'taxi delete',
                'resort view',
                'resort create',
                'resort edit',
                'resort delete',
                'item view',
                'item create',
                'item edit',
                'item delete',
                'tour view',
                'tour create',
                'tour edit',
                'tour delete',

                'clients view',
                'clients create',
                'clients edit',
                'clients delete',
                'clients view_detail',
                'expense view',
                'expense create',
                'expense edit',
                'expense delete',
                'calculator view',
                'calculator use',
                'tax view',
                'tax create',
                'tax edit',
                'tax delete',
                'organization view',
                'organization edit',
                'preferences view',
                'preferences edit',

                'estimates view',
                'estimates create',
                'estimates edit',
                'estimates delete',
                'estimates view_detail',
                'invoices view',
                'invoices create',
                'invoices edit',
                'invoices delete',
                'invoices view_detail',
                'invoices convert',

                'leads view',
                'leads create',
                'leads edit',
                'leads delete',
                'leads view_detail',
                'pipeline view',
                'pipeline manage',
                'leads_status view',
                'leads_status manage',
                'leads_stages view',
                'leads_stages manage',
                'leads_source view',
                'leads_source manage',

                'users view',
                'users create',
                'users edit',
                'users delete',
                'emails view',
                'emails manage',
                'vendors view',
                'vendors manage',
                'vehicles view',
                'vehicles manage',
                
                'tag manage','tag view'
            ],

            // OLD FROM THIS WE CANT EDIT THIS PERMISION 
            // 'sales' => [
            //     'dashboard view',
            //     'clients view', 'clients create', 'clients edit', 'clients view_detail',
            //     'leads view', 'leads create', 'leads edit', 'leads view_detail',
            //     'estimates view', 'estimates create', 'estimates edit', 'estimates view_detail',
            //     'invoices view', 'invoices create', 'invoices edit', 'invoices view_detail',
            // ],

            // 'marketing' => [
            //     'dashboard view',
            //     'leads view', 'leads create', 'leads view_detail',
            //     'clients view', 'clients view_detail',
            // ]
            'sales' => [],
            'marketing' => [],
        ];
        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web'
            ]);
            $role->syncPermissions($permissions);
        }

        $this->command->info('Roles created with permissions assigned successfully!');
    }
}
