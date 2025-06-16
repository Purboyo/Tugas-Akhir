<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'id' => 2,
            'name' => 'Purboyo Broto Umbaran',
            'email' => 'brotoumbaranp@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'teknisi',
        ]);
        User::create([
            'id' => 3,
            'name' => 'Tegar Panggalih',
            'email' => 'Tegar@example.com',
            'password' => Hash::make('password'),
            'role' => 'teknisi',
        ]);
        User::create([
            'id' => 4,
            'name' => 'Kepala_lab',
            'email' => 'kepalalab@example.com',
            'password' => Hash::make('password'),
            'role' => 'kepala_lab',
        ]);
        User::create([
            'id' => 5,
            'name' => 'Jurusan',
            'email' => 'jurusan@example.com',
            'password' => Hash::make('password'),
            'role' => 'jurusan',
        ]);
    }
}
