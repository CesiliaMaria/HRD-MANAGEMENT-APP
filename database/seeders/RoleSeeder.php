<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Menambahkan data default untuk roles
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Administrator dengan akses penuh'
            ],
            [
                'name' => 'manager',
                'description' => 'Manager dengan akses terbatas'
            ],
            [
                'name' => 'employee',
                'description' => 'Karyawan dengan akses dasar'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}