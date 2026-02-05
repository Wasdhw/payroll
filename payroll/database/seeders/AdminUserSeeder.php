<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@sdsc.edu.ph'], // admin email
            [
                'name' => 'SDSC Admin',
                'password' => Hash::make('admin123'), // preferred password
            ]
        );
    }
}