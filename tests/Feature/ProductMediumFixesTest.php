<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductMediumFixesTest extends TestCase
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

    // #4 — image files are cleaned up on delete (including soft delete + variants)
    public function test_deleting_product_removes_image_files_from_disk()
    {
        Storage::fake('public');
        Storage::disk('public')->put('products/prod.jpg', 'x');
        Storage::disk('public')->put('products/var.jpg', 'x');

        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Kaos',
            'slug' => 'kaos',
            'price' => 10000,
            'status' => 'ready',
        ]);
        ProductImage::create(['product_id' => $product->id, 'image_path' => 'products/prod.jpg']);
        ProductVariant::create([
            'product_id' => $product->id,
            'size' => 'S', 'color' => 'Merah', 'stock' => 3,
            'image_path' => 'products/var.jpg',
        ]);

        // Soft delete via the admin endpoint
        $this->actingAs($this->admin)
            ->delete(route('admin.products.destroy', $product->id))
            ->assertRedirect();

        $this->assertSoftDeleted('products', ['id' => $product->id]);
        Storage::disk('public')->assertMissing('products/prod.jpg');
        Storage::disk('public')->assertMissing('products/var.jpg');
    }

    // #5 — open redirect protection
    public function test_update_ignores_external_redirect_url()
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Kaos',
            'slug' => 'kaos',
            'price' => 10000,
            'status' => 'ready',
        ]);

        $response = $this->actingAs($this->admin)->put(
            route('admin.products.update', $product->id),
            [
                'category_id' => $this->category->id,
                'name' => 'Kaos Updated',
                'price' => 12000,
                'status' => 'ready',
                'redirect_url' => 'http://evil.example.com/phish',
            ]
        );

        // Falls back to the products index instead of the external host
        $response->assertRedirect(route('admin.products.index'));
    }

    public function test_update_honors_same_host_redirect_url()
    {
        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Kaos',
            'slug' => 'kaos',
            'price' => 10000,
            'status' => 'ready',
        ]);

        $target = route('admin.products.index', ['status' => 'ready']);

        $response = $this->actingAs($this->admin)->put(
            route('admin.products.update', $product->id),
            [
                'category_id' => $this->category->id,
                'name' => 'Kaos Updated',
                'price' => 12000,
                'status' => 'ready',
                'redirect_url' => $target,
            ]
        );

        $response->assertRedirect($target);
    }

    // #6 — duplicate variants within one submission are skipped
    public function test_store_deduplicates_identical_variants()
    {
        $postData = [
            'category_id' => $this->category->id,
            'name' => 'Kaos',
            'price' => 10000,
            'status' => 'ready',
            'variants' => [
                ['size' => 'S', 'color' => 'Merah', 'stock' => 5],
                ['size' => 's', 'color' => ' Merah ', 'stock' => 8], // same combo, different casing/spacing
                ['size' => 'M', 'color' => 'Merah', 'stock' => 2],
            ],
        ];

        $this->actingAs($this->admin)
            ->post(route('admin.products.store'), $postData)
            ->assertRedirect(route('admin.products.index'));

        $product = Product::where('name', 'Kaos')->firstOrFail();

        // S/Merah collapsed to one, plus M/Merah = 2 variants total
        $this->assertEquals(2, $product->variants()->count());
        $this->assertEquals(1, $product->variants()->where('size', 'S')->count());
    }
}
