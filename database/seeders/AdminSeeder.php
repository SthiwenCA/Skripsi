<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Memanggil model database User
use Illuminate\Support\Facades\Hash; // Memanggil alat enkripsi password

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat akun Admin baru
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // Patokan: Cari email ini
            [
                'name' => 'Administrator',
                'password' => Hash::make('rahasia123'), 
            ]
        );
    }
}