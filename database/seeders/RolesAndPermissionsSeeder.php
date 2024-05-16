<?php
// database/seeders/RolesAndPermissionsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Crear roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'barra']);
        Role::create(['name' => 'cocina']);
        Role::create(['name' => 'camarero']);
        
        // Si necesitas permisos específicos, puedes crearlos y asignarlos aquí
        // Permission::create(['name' => 'manage orders']);
        // $role->givePermissionTo('manage orders');
    }
}
