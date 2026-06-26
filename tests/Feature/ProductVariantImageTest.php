<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductVariantImageTest extends TestCase
{
    use RefreshDatabase;

    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = Category::create([
            'name' => 'Baju Anak',
            'slug' => 'baju-anak',
        ]);
        Storage::fake('public');
    }

    public function test_admin_can_create_product_with_variant_images()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        
        $variantImage = UploadedFile::fake()->image('variant_s_red.jpg');

        $postData = [
            'category_id' => $this->category->id,
            'name' => 'Kaos Premium',
            'sku' => 'KP-002',
            'price' => 120000,
            'status' => 'ready',
            'variants' => [
                [
                    'size' => 'S',
                    'color' => 'Merah',
                    'stock' => 10,
                    'price' => 125000,
                    'image' => $variantImage,
                ]
            ],
        ];

        $response = $this->actingAs($admin)
            ->post(route('admin.products.store'), $postData);

        $response->assertRedirect(route('admin.products.index'));

        $product = Product::where('sku', 'KP-002')->firstOrFail();
        $variant = $product->variants()->firstOrFail();

        $this->assertNotNull($variant->image_path);
        Storage::disk('public')->assertExists($variant->image_path);
    }

    public function test_admin_can_update_product_retaining_existing_variant_images()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Kaos Premium',
            'slug' => 'kaos-premium',
            'sku' => 'KP-002',
            'price' => 120000,
            'status' => 'ready',
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'size' => 'S',
            'color' => 'Merah',
            'stock' => 10,
            'price' => 125000,
            'image_path' => 'products/dummy_image.jpg',
        ]);

        $updateData = [
            'category_id' => $this->category->id,
            'name' => 'Kaos Premium Updated',
            'sku' => 'KP-002',
            'price' => 120000,
            'status' => 'ready',
            'variants' => [
                [
                    'size' => 'S',
                    'color' => 'Merah',
                    'stock' => 15,
                    'price' => 125000,
                    'image_path' => 'products/dummy_image.jpg', // Retained existing path
                ]
            ],
        ];

        $response = $this->actingAs($admin)
            ->put(route('admin.products.update', $product->id), $updateData);

        $response->assertRedirect(route('admin.products.index'));

        $product->refresh();
        $variant = $product->variants()->firstOrFail();
        $this->assertEquals('products/dummy_image.jpg', $variant->image_path);
        $this->assertEquals(15, $variant->stock);
    }

    public function test_admin_can_update_product_uploading_new_variant_images()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Kaos Premium',
            'slug' => 'kaos-premium',
            'sku' => 'KP-002',
            'price' => 120000,
            'status' => 'ready',
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'size' => 'S',
            'color' => 'Merah',
            'stock' => 10,
            'price' => 125000,
            'image_path' => 'products/old_image.jpg',
        ]);

        $newVariantImage = UploadedFile::fake()->image('new_variant_s_red.jpg');

        $updateData = [
            'category_id' => $this->category->id,
            'name' => 'Kaos Premium Updated',
            'sku' => 'KP-002',
            'price' => 120000,
            'status' => 'ready',
            'variants' => [
                [
                    'size' => 'S',
                    'color' => 'Merah',
                    'stock' => 10,
                    'price' => 125000,
                    'image' => $newVariantImage, // Uploaded new image
                    'image_path' => 'products/old_image.jpg', // Should be overridden by new image
                ]
            ],
        ];

        $response = $this->actingAs($admin)
            ->put(route('admin.products.update', $product->id), $updateData);

        $response->assertRedirect(route('admin.products.index'));

        $product->refresh();
        $variant = $product->variants()->firstOrFail();
        $this->assertNotEquals('products/old_image.jpg', $variant->image_path);
        $this->assertNotNull($variant->image_path);
        Storage::disk('public')->assertExists($variant->image_path);
    }

    public function test_admin_product_show_redirects_to_index_with_edit_parameter()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'sku' => 'TP-001',
            'price' => 10000,
            'status' => 'ready',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.products.show', $product->id));

        $response->assertRedirect(route('admin.products.index', ['edit' => $product->id]));
    }
}
