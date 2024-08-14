<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Hossein',
            'email' => 'user@user.com',
            'password' => Hash::make('123456'),
        ]);
        Admin::create([
            'name' => 'Hossein',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
