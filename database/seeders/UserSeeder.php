<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Import model User
use Illuminate\Support\Facades\Hash; // Import Hash


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'username' => '1',
            'password' => Hash::make('1'),
            'role' => 'admin',
        ]);

        User::create([
            'username' => 'user123',
            'password' => Hash::make('userpassword'),
            'role' => 'user',
        ]);
        
    }
}
