<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductVariantPriceTest extends TestCase
{
    use RefreshDatabase;

    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed category for product creation
        $this->category = Category::create([
            'name' => 'Baju Anak',
            'slug' => 'baju-anak',
        ]);
    }

    public function test_admin_can_create_product_with_variant_custom_prices()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $postData = [
            'category_id' => $this->category->id,
            'name' => 'Kaos Polo Premium',
            'sku' => 'KPP-001',
            'price' => 150000,
            'status' => 'ready',
            'description' => 'Kaos polo anak berkualitas tinggi.',
            'variants' => [
                [
                    'size' => 'S',
                    'color' => 'Merah',
                    'stock' => 10,
                    'price' => 160000, // Custom Price
                ],
                [
                    'size' => 'M',
                    'color' => 'Biru',
                    'stock' => 15,
                    'price' => null,  // Revert to base price
                ]
            ],
        ];

        $response = $this->actingAs($admin)
            ->post(route('admin.products.store'), $postData);

        $response->assertRedirect(route('admin.products.index'));

        // Assert database records
        $product = Product::where('sku', 'KPP-001')->firstOrFail();
        $this->assertCount(2, $product->variants);

        $v1 = $product->variants()->where('size', 'S')->first();
        $this->assertEquals(160000, $v1->price);

        $v2 = $product->variants()->where('size', 'M')->first();
        $this->assertNull($v2->price);
    }

    public function test_admin_can_update_product_with_variant_custom_prices()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Kaos Polo Premium',
            'slug' => 'kaos-polo-premium',
            'sku' => 'KPP-001',
            'price' => 150000,
            'status' => 'ready',
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'size' => 'S',
            'color' => 'Merah',
            'stock' => 10,
            'price' => 155000,
        ]);

        $updateData = [
            'category_id' => $this->category->id,
            'name' => 'Kaos Polo Premium Updated',
            'sku' => 'KPP-001',
            'price' => 150000,
            'status' => 'ready',
            'variants' => [
                [
                    'size' => 'S',
                    'color' => 'Merah',
                    'stock' => 5,
                    'price' => 170000, // Updated custom price
                ],
                [
                    'size' => 'L',
                    'color' => 'Hijau',
                    'stock' => 20,
                    'price' => null, // Default
                ]
            ],
        ];

        $response = $this->actingAs($admin)
            ->put(route('admin.products.update', $product->id), $updateData);

        $response->assertRedirect(route('admin.products.index'));

        $product->refresh();
        $this->assertCount(2, $product->variants);

        $v1 = $product->variants()->where('size', 'S')->first();
        $this->assertEquals(170000, $v1->price);
        $this->assertEquals(5, $v1->stock);

        $v2 = $product->variants()->where('size', 'L')->first();
        $this->assertNull($v2->price);
        $this->assertEquals(20, $v2->stock);
    }

    public function test_admin_can_duplicate_product_retaining_variant_prices()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Kaos Polo Premium',
            'slug' => 'kaos-polo-premium',
            'sku' => 'KPP-001',
            'price' => 150000,
            'status' => 'ready',
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'size' => 'S',
            'color' => 'Merah',
            'stock' => 10,
            'price' => 165000,
        ]);

        // Duplicate request postData
        $duplicateData = [
            'category_id' => $product->category_id,
            'name' => $product->name . ' (Copy)',
            'price' => $product->price,
            'status' => $product->status,
            'variants' => [
                [
                    'size' => 'S',
                    'color' => 'Merah',
                    'stock' => 10,
                    'price' => 165000,
                ]
            ],
        ];

        $response = $this->actingAs($admin)
            ->post(route('admin.products.store'), $duplicateData);

        $response->assertRedirect(route('admin.products.index'));

        $duplicate = Product::where('name', 'Kaos Polo Premium (Copy)')->firstOrFail();
        $v1 = $duplicate->variants()->where('size', 'S')->firstOrFail();
        $this->assertEquals(165000, $v1->price);
    }
}
