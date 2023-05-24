<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "id" => "1ac1454d-bba6-482d-b576-034ce16f1c50",
            "nama" => "Sallie Mansurina",
            "email" => "sallieeky@gmail.com",
            "password" => bcrypt("12345678"),
            "telepon" => "081243942304",
            "alamat" => "Jl. A. Yani, Bontang",
        ]);
    }
}
