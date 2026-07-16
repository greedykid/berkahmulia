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
            ->assertSee('products-skeleton')
            ->assertSee('load-more-btn')
            ->assertSee('Lihat Lebih Banyak');
    }

    public function test_ajax_batch_returns_only_card_html_and_flags()
    {
        $this->makeProducts(20);

        $response = $this->getJson(route('catalog.index', ['offset' => 10, 'limit' => 10]), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['html', 'hasMore', 'total']);

        // Last batch of a 20-product result set
        $this->assertEquals(10, substr_count($response->json('html'), 'product-card-appear'));
        $this->assertFalse($response->json('hasMore'));
        $this->assertEquals(20, $response->json('total'));

        // The partial must not carry the full page layout
        $this->assertStringNotContainsString('<html', $response->json('html'));
    }

    public function test_auto_scroll_batch_of_ten_reaches_the_twenty_limit()
    {
        $this->makeProducts(50);

        // Initial render is 10, so one auto batch of 10 lands exactly on 20
        $page1 = $this->get(route('catalog.index'));
        $this->assertEquals(10, substr_count($page1->getContent(), 'product-card-appear'));

        $batch = $this->getJson(route('catalog.index', ['offset' => 10, 'limit' => 10]), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);
        $this->assertEquals(10, substr_count($batch->json('html'), 'product-card-appear'));
        $this->assertTrue($batch->json('hasMore'));
    }

    public function test_load_more_button_batch_returns_thirty_products()
    {
        $this->makeProducts(100);

        // What a button click sends once auto-scroll has stopped at 20
        $batch = $this->getJson(route('catalog.index', ['offset' => 20, 'limit' => 30]), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $this->assertEquals(30, substr_count($batch->json('html'), 'product-card-appear'));
        $this->assertTrue($batch->json('hasMore'));
        $this->assertEquals(100, $batch->json('total'));
    }

    public function test_batches_do_not_overlap_and_limit_is_clamped()
    {
        $this->makeProducts(50);

        $first = $this->getJson(route('catalog.index', ['offset' => 0, 'limit' => 10]), [
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('html');

        $second = $this->getJson(route('catalog.index', ['offset' => 10, 'limit' => 10]), [
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('html');

        // A product from the first batch must not reappear in the second
        preg_match('#/katalog/(produk-\d+)"#', $first, $m);
        $this->assertNotEmpty($m);
        $this->assertStringNotContainsString('/katalog/' . $m[1] . '"', $second);

        // An absurd limit is clamped rather than dumping the whole catalog
        $huge = $this->getJson(route('catalog.index', ['offset' => 0, 'limit' => 9999]), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);
        $this->assertEquals(50, substr_count($huge->json('html'), 'product-card-appear'));
    }

    public function test_last_batch_reports_no_more_results()
    {
        $this->makeProducts(25);

        $batch = $this->getJson(route('catalog.index', ['offset' => 20, 'limit' => 30]), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $this->assertEquals(5, substr_count($batch->json('html'), 'product-card-appear'));
        $this->assertFalse($batch->json('hasMore'));
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

        // Scrolling for more batches must not add visits
        $this->getJson(route('catalog.index', ['offset' => 10, 'limit' => 10]), [
            'X-Requested-With' => 'XMLHttpRequest',
        ])->assertStatus(200);

        $this->assertEquals($before, \App\Models\Visit::count());
    }
}
