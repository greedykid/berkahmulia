<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductBulkActionTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Category $baju;
    protected Category $celana;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->baju = Category::create(['name' => 'Baju', 'slug' => 'baju']);
        $this->celana = Category::create(['name' => 'Celana', 'slug' => 'celana']);
    }

    private function makeProduct(string $name, Category $category, string $status = 'ready'): Product
    {
        return Product::create([
            'category_id' => $category->id,
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
            'price' => 10000,
            'status' => $status,
        ]);
    }

    public function test_bulk_delete_select_all_only_deletes_products_matching_active_category_filter()
    {
        $bajuA = $this->makeProduct('Baju A', $this->baju);
        $bajuB = $this->makeProduct('Baju B', $this->baju);
        $celanaA = $this->makeProduct('Celana A', $this->celana);

        $this->actingAs($this->admin)
            ->post(route('admin.products.bulkDelete'), [
                'select_all' => '1',
                'filter_category' => $this->baju->id,
            ])
            ->assertRedirect(route('admin.products.index'));

        // Only the "Baju" products should be soft-deleted.
        $this->assertSoftDeleted('products', ['id' => $bajuA->id]);
        $this->assertSoftDeleted('products', ['id' => $bajuB->id]);
        $this->assertNotSoftDeleted('products', ['id' => $celanaA->id]);
    }

    public function test_bulk_delete_select_all_without_filters_deletes_everything()
    {
        $a = $this->makeProduct('A', $this->baju);
        $b = $this->makeProduct('B', $this->celana);

        $this->actingAs($this->admin)
            ->post(route('admin.products.bulkDelete'), ['select_all' => '1'])
            ->assertRedirect(route('admin.products.index'));

        $this->assertSoftDeleted('products', ['id' => $a->id]);
        $this->assertSoftDeleted('products', ['id' => $b->id]);
    }

    public function test_bulk_status_select_all_only_updates_products_matching_active_status_filter()
    {
        $ready = $this->makeProduct('Ready Prod', $this->baju, 'ready');
        $po = $this->makeProduct('PO Prod', $this->baju, 'po');

        $this->actingAs($this->admin)
            ->post(route('admin.products.bulkStatus'), [
                'select_all' => '1',
                'status' => 'sold_out',      // target status to apply
                'filter_status' => 'ready',  // active listing filter
            ])
            ->assertRedirect(route('admin.products.index'));

        $this->assertEquals('sold_out', $ready->fresh()->status);
        // The PO product did not match the filter, so it stays untouched.
        $this->assertEquals('po', $po->fresh()->status);
    }

    public function test_bulk_delete_by_ids_still_works()
    {
        $a = $this->makeProduct('A', $this->baju);
        $b = $this->makeProduct('B', $this->baju);

        $this->actingAs($this->admin)
            ->post(route('admin.products.bulkDelete'), ['ids' => [$a->id]])
            ->assertRedirect(route('admin.products.index'));

        $this->assertSoftDeleted('products', ['id' => $a->id]);
        $this->assertNotSoftDeleted('products', ['id' => $b->id]);
    }
}
