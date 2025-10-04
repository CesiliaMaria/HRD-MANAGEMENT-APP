<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles first
        $this->call(RoleSeeder::class);
        
        // Check if admin user already exists
        if (!User::where('email', 'admin@hrd.com')->exists()) {
            User::factory()->create([
                'name' => 'Admin HRD',
                'email' => 'admin@hrd.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
            ]);
        }

        // Check if we have any employees, if not create some
        if (User::where('role_id', 3)->count() === 0) {
            User::factory(5)->create([
                'role_id' => 3,
            ]);
        }
    }
}