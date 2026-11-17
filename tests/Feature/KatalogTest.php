<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_sapa_route_returns_the_expected_greeting(): void
    {
        $this->get('/sapa')
            ->assertOk()
            ->assertSeeText('Halo, kelompok 1!');
    }

    public function test_products_can_be_created_viewed_updated_and_deleted(): void
    {
        $this->get(route('katalog.index'))
            ->assertOk()
            ->assertSeeText('Belum ada produk');

        $this->get(route('katalog.create'))
            ->assertOk()
            ->assertSeeText('Tambah Produk');

        $this->post(route('katalog.store'), [
            'nama' => 'Kemeja Santai',
            'kategori' => 'Pakaian',
            'harga' => 125000,
            'stok' => 12,
        ])->assertSessionHasNoErrors();

        $produk = Product::query()->where('nama', 'Kemeja Santai')->firstOrFail();

        $this->assertDatabaseHas('products', [
            'id' => $produk->id,
            'harga' => 125000,
            'stok' => 12,
        ]);

        $this->get(route('katalog.index'))
            ->assertOk()
            ->assertSeeText('Kemeja Santai')
            ->assertSeeText('Pakaian')
            ->assertSeeText('Rp 125.000')
            ->assertSeeText('Tersedia: 12');

        $this->get(route('katalog.show', $produk))
            ->assertOk()
            ->assertSeeText('Detail Produk')
            ->assertSeeText('Kemeja Santai');

        $this->get(route('katalog.edit', $produk))
            ->assertOk()
            ->assertSeeText('Edit Produk');

        $this->put(route('katalog.update', $produk), [
            'nama' => 'Kemeja Baru',
            'kategori' => 'Pakaian',
            'harga' => 135000,
            'stok' => 8,
        ])->assertRedirect(route('katalog.show', $produk));

        $this->assertDatabaseHas('products', [
            'id' => $produk->id,
            'nama' => 'Kemeja Baru',
            'harga' => 135000,
            'stok' => 8,
        ]);

        $this->delete(route('katalog.destroy', $produk))
            ->assertRedirect(route('katalog.index'));

        $this->assertDatabaseMissing('products', ['id' => $produk->id]);
    }

    public function test_product_fields_are_validated(): void
    {
        $this->from(route('katalog.create'))
            ->post(route('katalog.store'), [
                'nama' => '',
                'kategori' => '',
                'harga' => -1,
                'stok' => -1,
            ])
            ->assertRedirect(route('katalog.create'))
            ->assertSessionHasErrors(['nama', 'kategori', 'harga', 'stok']);
    }
}
