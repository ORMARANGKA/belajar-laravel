<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        foreach ([
            [
                'nama' => 'Kemeja Santai',
                'kategori' => 'Pakaian',
                'harga' => 125000,
                'stok' => 12,
            ],
            [
                'nama' => 'Tas Kanvas',
                'kategori' => 'Aksesori',
                'harga' => 89000,
                'stok' => 0,
            ],
            [
                'nama' => 'Botol Minum',
                'kategori' => 'Perlengkapan',
                'harga' => 45000,
                'stok' => 24,
            ],
        ] as $produk) {
            Product::firstOrCreate(['nama' => $produk['nama']], $produk);
        }
    }
}
