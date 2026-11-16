<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ganti password ini setelah login pertama.
        User::updateOrCreate(
            ['email' => 'admin@brilink.test'],
            ['name' => 'Administrator', 'password' => 'admin12345', 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'operator@brilink.test'],
            ['name' => 'Operator Agen', 'password' => 'operator12345', 'role' => 'operator']
        );
    }
}
