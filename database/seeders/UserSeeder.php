<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "first_name" => "Holako",
            "last_name" => "Noob",
            "username" => "HolakoNoob",
            "email" => "holako@p.com",
            "password" => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password

        ])->assignRole('Owner');
        User::create([
            "first_name" => "admin",
            "last_name" => "",
            "username" => "admin",
            "email" => "admin@p.com",
            "password" => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password

        ])->assignRole('Admin');
        User::create([
            "first_name" => "Test",
            "last_name" => "User",
            "username" => "user",
            "email" => "user@p.com",
            "password" => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password

        ])->assignRole('User');
    }
}
