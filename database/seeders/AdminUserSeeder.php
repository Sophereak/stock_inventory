<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (!User::where('username', 'admin')->exists()) {
            User::create([
                'name' => 'System Administrator',
                'username' => 'admin',
                'password' => Hash::make('10082022'), // change later!
            ]);
        }
    }
}
