<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Test User 1',
            'email' => 'test1@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);
        User::create([
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);
        User::create([
            'name' => 'Test User 3',
            'email' => 'test3@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);
        User::create([
            'name' => 'Test User 4',
            'email' => 'test4@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        $this->command->info('Users created successfully!');
    }
}
