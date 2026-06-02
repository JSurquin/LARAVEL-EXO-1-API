<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

// Seeder admin — crée un compte administrateur de test
// Commande : php artisan db:seed --class=AdminSeeder
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@example.com',
            'password' => 'password', // Hashé automatiquement via cast 'hashed'
            'role'     => 'admin',    // Rôle admin pour tester PostPolicy
        ]);
    }
}
