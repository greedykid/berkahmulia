<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogSlugTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->category = Category::create(['name' => 'Baju', 'slug' => 'baju']);
    }

    private function makeProduct(string $name, string $slug): Product
    {
        return Product::create([
            'category_id' => $this->category->id,
            'name' => $name,
            'slug' => $slug,
            'price' => 10000,
            'status' => 'ready',
        ]);
    }

    public function test_updating_a_product_keeps_its_slug_stable()
    {
        $product = $this->makeProduct('Topi Bayi Rajut', 'topi-bayi-rajut');

        $this->actingAs($this->admin)->put(route('admin.products.update', $product->id), [
            'category_id' => $this->category->id,
            'name' => 'Topi Bayi Rajut Lucu Double Pompon',
            'price' => 15000,
            'status' => 'ready',
        ])->assertRedirect();

        $product->refresh();

        // Name changed, but the public URL must not
        $this->assertEquals('Topi Bayi Rajut Lucu Double Pompon', $product->name);
        $this->assertEquals('topi-bayi-rajut', $product->slug);

        // The original URL still resolves
        $this->get(route('catalog.show', 'topi-bayi-rajut'))->assertStatus(200);
    }

    public function test_deleted_product_url_returns_410_gone()
    {
        $product = $this->makeProduct('Rok Plisket', 'rok-plisket-anak');

        $this->actingAs($this->admin)
            ->delete(route('admin.products.destroy', $product->id))
            ->assertRedirect();

        $this->get(route('catalog.show', 'rok-plisket-anak'))
            ->assertStatus(410)
            ->assertSee('Produk Sudah Tidak Tersedia');
    }

    public function test_unknown_slug_still_returns_404()
    {
        $this->get(route('catalog.show', 'slug-yang-tidak-pernah-ada'))
            ->assertStatus(404);
    }

    public function test_existing_product_url_returns_200()
    {
        $this->makeProduct('Celana Anak', 'celana-anak');

        $this->get(route('catalog.show', 'celana-anak'))->assertStatus(200);
    }
}
