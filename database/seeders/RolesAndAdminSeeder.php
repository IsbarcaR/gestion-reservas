<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Service;
use App\Models\Professional;

class RolesAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleClient = Role::create(['name' => 'cliente']);

        // Crear usuario Admin
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $adminUser->assignRole($roleAdmin);

        // Crear usuario Cliente de prueba
        $clientUser = User::create([
            'name' => 'Cliente de Prueba',
            'email' => 'cliente@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $clientUser->assignRole($roleClient);

        // Crear algunos servicios de ejemplo
        Service::create(['name' => 'Corte de Pelo', 'duration_minutes' => 30, 'cost' => 15.00]);
        Service::create(['name' => 'Consulta Médica', 'duration_minutes' => 45, 'cost' => 50.00]);
        Service::create(['name' => 'Clase de Yoga', 'duration_minutes' => 60, 'cost' => 20.00]);

        // Crear algunos profesionales de ejemplo
        Professional::create(['name' => 'Juan Pérez', 'email' => 'juan.perez@example.com']);
        Professional::create(['name' => 'Ana López', 'email' => 'ana.lopez@example.com']);
    }
}