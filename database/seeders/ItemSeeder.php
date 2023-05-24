<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::create([
            "id" => "d7dd7818-f510-45e1-92e9-1759c9650d0a",
            "nama" => "Kaos Polo",
            "gambar" => "kaos.jpg",
            "harga" => 100000,
            "stok" => 10,
        ]);

        Item::create([
            "id" => "0c8d87ee-bc10-45c8-9eb1-9da3659a84eb",
            "nama" => "Kemeja Pria",
            "gambar" => "kemeja.jpg",
            "harga" => 150000,
            "stok" => 20,
        ]);

        Item::create([
            "id" => "711de800-005d-4d74-a6ee-9ae520dd6e4e",
            "nama" => "Celana Jeans",
            "gambar" => "jeans.jpg",
            "harga" => 300000,
            "stok" => 30,
        ]);
    }
}
