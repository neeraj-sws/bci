<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('is_admin', 1)->first(); // Adjust email

        if ($user) {
            $user->assignRole('admin');
            $this->command->info("Admin role assigned to {$user->email}");
        } else {
            $this->command->warn("No user with email admin@example.com found.");
        }
    }
}
