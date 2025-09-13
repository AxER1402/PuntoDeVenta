<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = [
            [
                'name' => 'Juan Pérez',
                'email' => 'juan.perez@email.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'María García',
                'email' => 'maria.garcia@email.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Carlos López',
                'email' => 'carlos.lopez@email.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ana Martínez',
                'email' => 'ana.martinez@email.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Luis Rodríguez',
                'email' => 'luis.rodriguez@email.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Carmen Sánchez',
                'email' => 'carmen.sanchez@email.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Pedro González',
                'email' => 'pedro.gonzalez@email.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Laura Fernández',
                'email' => 'laura.fernandez@email.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($clientes as $cliente) {
            User::create($cliente);
        }
    }
}