<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AdminPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // Dashboard
            'dashboard view',
            
            // Park Module
            'park view', 'park create', 'park edit', 'park delete',
            
            // Zone Module
            'zone view', 'zone create', 'zone edit', 'zone delete',
            
            // Gates Module
            'gates view', 'gates create', 'gates edit', 'gates delete',
            
            // Taxi Module
            'taxi view', 'taxi create', 'taxi edit', 'taxi delete',
            
            // Resort Module
            'resort view', 'resort create', 'resort edit', 'resort delete',
            
            // Item Module
            'item view', 'item create', 'item edit', 'item delete',
            
            // Tour Module
            'tour view', 'tour create', 'tour edit', 'tour delete',
            
            // Clients Module
            'clients view', 'clients create', 'clients edit', 'clients delete', 'clients view_detail',
            
            // Expense Module
            'expense view', 'expense create', 'expense edit', 'expense delete',
            
            // Calculator Module
            'calculator view', 'calculator use',
            
            // Tax Module
            'tax view', 'tax create', 'tax edit', 'tax delete',
            
            // Organization Module
            'organization view', 'organization edit',
            
            // Preferences Module
            'preferences view', 'preferences edit',
            
            // Estimates Module
            'estimates view', 'estimates create', 'estimates edit', 'estimates delete', 'estimates view_detail',
            
            // Invoices Module
            'invoices view', 'invoices create', 'invoices edit', 'invoices delete', 'invoices view_detail', 'invoices convert',
            
            // Leads Module
            'leads view', 'leads create', 'leads edit', 'leads delete', 'leads view_detail',
            
            // Leads Pipeline
            'pipeline view', 'pipeline manage',
            
            // Leads Status
            'leads_status view', 'leads_status manage',
            
            // Leads Stages
            'leads_stages view', 'leads_stages manage',
            
            // Leads Source
            'leads_source view', 'leads_source manage',
            
            // Users Module
            'users view', 'users create', 'users edit', 'users delete',
            
            // Email Module
            'emails view', 'emails manage',
            
            'tag manage','tag view'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        $this->command->info('All permissions created successfully for web guard!');
    }
}