<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear roles
        $roleRoot = Role::firstOrCreate(['name' => 'root']);
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleCliente = Role::firstOrCreate(['name' => 'cliente']);

        // Crear usuario root
        $userRoot = User::firstOrCreate(
            ['email' => 'root@ModuStackHome.com'],
            [
                'name' => 'Root User',
                'password' => Hash::make('0382646740Ju*'),
            ]
        );
        $userRoot->assignRole($roleRoot);

        // Crear usuario admin
        $userAdmin = User::firstOrCreate(
            ['email' => 'admin@ModuStackHome.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('0382646740Ju*'),
            ]
        );
        $userAdmin->assignRole($roleAdmin);

        // Crear cliente 1: Juan Carlos Diaz Lara
        $userCliente1 = User::firstOrCreate(
            ['email' => 'rulos26@gmail.com'],
            [
                'name' => 'Juan Carlos Diaz Lara',
                'password' => Hash::make('0382646740Ju*'),
            ]
        );
        $userCliente1->assignRole($roleCliente);

        // Crear cliente 2: Maria Carolina Leon
        $userCliente2 = User::firstOrCreate(
            ['email' => 'mariacarolinaleon0820@gmail.com'],
            [
                'name' => 'Maria Carolina Leon',
                'password' => Hash::make('Carolina0820*'),
            ]
        );
        $userCliente2->assignRole($roleCliente);

        $this->command->info('Roles y usuarios creados exitosamente!');
        $this->command->info('- Rol: root -> Usuario: root@ModuStackHome.com');
        $this->command->info('- Rol: admin -> Usuario: admin@ModuStackHome.com');
        $this->command->info('- Rol: cliente -> Usuario: rulos26@gmail.com (Juan Carlos Diaz Lara)');
        $this->command->info('- Rol: cliente -> Usuario: mariacarolinaleon0820@gmail.com (Maria Carolina Leon)');
    }
}

