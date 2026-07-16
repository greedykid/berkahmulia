<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogInfiniteScrollTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = Category::create(['name' => 'Baju', 'slug' => 'baju']);
    }

    private function makeProducts(int $count): void
    {
        for ($i = 1; $i <= $count; $i++) {
            Product::create([
                'category_id' => $this->category->id,
                'name' => 'Produk ' . $i,
                'slug' => 'produk-' . $i,
                'price' => 10000 + $i,
                'status' => 'ready',
            ]);
        }
    }

    public function test_normal_request_renders_full_page_with_grid_and_sentinel()
    {
        $this->makeProducts(20);

        $response = $this->get(route('catalog.index'));

        $response->assertStatus(200)
            ->assertSee('products-grid')
            ->assertSee('scroll-sentinel')
            ->assertSee('products-skeleton');
    }

    public function test_ajax_request_returns_only_card_html_and_paging_flags()
    {
        $this->makeProducts(20);

        $response = $this->getJson(route('catalog.index', ['page' => 2]), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['html', 'hasMore', 'nextPage']);

        // 20 products, 12 per page -> page 2 holds the remaining 8
        $this->assertEquals(8, substr_count($response->json('html'), 'product-card-appear'));
        $this->assertFalse($response->json('hasMore'));

        // The partial must not carry the full page layout
        $this->assertStringNotContainsString('<html', $response->json('html'));
    }

    public function test_ajax_first_page_reports_more_pages_available()
    {
        $this->makeProducts(20);

        $response = $this->getJson(route('catalog.index'), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $this->assertTrue($response->json('hasMore'));
        $this->assertEquals(2, $response->json('nextPage'));
        $this->assertEquals(12, substr_count($response->json('html'), 'product-card-appear'));
    }

    public function test_ajax_respects_active_filters()
    {
        $this->makeProducts(20);
        // A product that must not match the search
        Product::create([
            'category_id' => $this->category->id,
            'name' => 'Sepatu Bayi',
            'slug' => 'sepatu-bayi',
            'price' => 50000,
            'status' => 'ready',
        ]);

        $response = $this->getJson(route('catalog.index', ['search' => 'Sepatu']), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $this->assertStringContainsString('Sepatu Bayi', $response->json('html'));
        $this->assertStringNotContainsString('Produk 1<', $response->json('html'));
        $this->assertFalse($response->json('hasMore'));
    }

    public function test_ajax_scroll_request_does_not_inflate_visit_counter()
    {
        $this->makeProducts(20);

        // A real page view counts once
        $this->get(route('catalog.index'))->assertStatus(200);
        $before = \App\Models\Visit::count();

        // Scrolling for more pages must not add visits
        $this->getJson(route('catalog.index', ['page' => 2]), [
            'X-Requested-With' => 'XMLHttpRequest',
        ])->assertStatus(200);

        $this->assertEquals($before, \App\Models\Visit::count());
    }
}
