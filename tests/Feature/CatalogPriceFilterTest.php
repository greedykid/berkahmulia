<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogPriceFilterTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = Category::create(['name' => 'Baju', 'slug' => 'baju']);
    }

    private function makeProduct(string $name, float $price): Product
    {
        return Product::create([
            'category_id' => $this->category->id,
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
            'price' => $price,
            'status' => 'ready',
        ]);
    }

    public function test_price_filter_uses_variant_price_when_product_price_is_zero()
    {
        $cheap = $this->makeProduct('ZebraShirt', 50000);   // effective 50k
        $variantPriced = $this->makeProduct('YakBlouse', 0); // effective 150k via variant
        ProductVariant::create([
            'product_id' => $variantPriced->id,
            'size' => 'M', 'color' => 'Biru', 'stock' => 5, 'price' => 150000,
        ]);
        $expensive = $this->makeProduct('XenonJacket', 200000); // effective 200k

        $response = $this->get(route('catalog.index', ['price_min' => 100000, 'price_max' => 180000]));

        $response->assertStatus(200);
        $response->assertSee('YakBlouse');       // variant-priced product is included
        $response->assertDontSee('ZebraShirt');   // below range
        $response->assertDontSee('XenonJacket');  // above range
    }

    public function test_price_ascending_sort_orders_by_effective_price()
    {
        $this->makeProduct('XenonJacket', 200000);
        $variantPriced = $this->makeProduct('YakBlouse', 0);
        ProductVariant::create([
            'product_id' => $variantPriced->id,
            'size' => 'M', 'color' => 'Biru', 'stock' => 5, 'price' => 150000,
        ]);
        $this->makeProduct('ZebraShirt', 50000);

        $content = $this->get(route('catalog.index', ['sort' => 'price_asc']))
            ->assertStatus(200)
            ->getContent();

        $posZebra = strpos($content, 'ZebraShirt');   // 50k
        $posYak = strpos($content, 'YakBlouse');       // 150k (variant)
        $posXenon = strpos($content, 'XenonJacket');   // 200k

        $this->assertNotFalse($posZebra);
        $this->assertNotFalse($posYak);
        $this->assertNotFalse($posXenon);
        $this->assertTrue($posZebra < $posYak, 'Cheapest should appear first');
        $this->assertTrue($posYak < $posXenon, 'Variant-priced should sort by its variant price');
    }
}
