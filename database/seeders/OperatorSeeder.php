<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OperatorSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'operator'],
            [
                'name' => 'Operator Akademik',
                'email' => 'operator@undipa.ac.id',
                'password' => Hash::make('password123'),
                'role' => UserRole::OPERATOR,
                'is_active' => true,
            ],
        );
    }
}