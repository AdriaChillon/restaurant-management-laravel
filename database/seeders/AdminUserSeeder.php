<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear el rol admin si no existe
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Crear el usuario admin si no existe
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'admin',
                'password' => bcrypt('admin1234'),
            ]
        );

        // Asignar el rol admin al usuario
        $adminUser->assignRole($adminRole);
    }
}
