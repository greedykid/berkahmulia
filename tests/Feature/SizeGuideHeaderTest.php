<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SizeGuideHeaderTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_size_guide_page_requires_admin_auth()
    {
        $response = $this->get(route('admin.settings.panduanUkuran'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_size_guide_settings_page()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)
            ->get(route('admin.settings.panduanUkuran'));

        $response->assertStatus(200);
        $response->assertSee('Tabel Panduan Ukuran');
        $response->assertSee('Daftar Kolom Tabel');
    }

    public function test_admin_can_save_dynamic_size_guide_columns_and_rows()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $postData = [
            'columns' => ['Ukuran Pakaian', 'Lebar Dada', 'Panjang Depan'],
            'rows' => [
                ['S', '24 cm', '32 cm'],
                ['M', '26 cm', '35 cm']
            ],
            'note' => 'Catatan ukuran dinamis',
        ];

        $response = $this->actingAs($admin)
            ->post(route('admin.settings.updatePanduanUkuran'), $postData);

        $response->assertRedirect(route('admin.settings.panduanUkuran'));
        $response->assertSessionHas('success');

        // Check if database settings are updated
        $this->assertEquals(['Ukuran Pakaian', 'Lebar Dada', 'Panjang Depan'], Setting::get('size_guide_columns'));
        $this->assertEquals([
            ['S', '24 cm', '32 cm'],
            ['M', '26 cm', '35 cm']
        ], Setting::get('size_guide'));
        $this->assertEquals('Catatan ukuran dinamis', Setting::get('size_guide_note'));
    }

    public function test_validation_fails_for_invalid_custom_size_guide_data()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        // 1. Missing columns
        $response = $this->actingAs($admin)
            ->post(route('admin.settings.updatePanduanUkuran'), [
                'columns' => [], // empty
                'rows' => [
                    ['S', '24 cm']
                ],
                'note' => 'Catatan',
            ]);

        $response->assertSessionHasErrors(['columns']);

        // 2. Empty string element inside columns array
        $response = $this->actingAs($admin)
            ->post(route('admin.settings.updatePanduanUkuran'), [
                'columns' => ['Ukuran', ''],
                'rows' => [
                    ['S', '24 cm']
                ],
                'note' => 'Catatan',
            ]);

        $response->assertSessionHasErrors(['columns.1']);

        // 3. Too long column title (> 50 chars)
        $longTitle = str_repeat('A', 51);
        $response = $this->actingAs($admin)
            ->post(route('admin.settings.updatePanduanUkuran'), [
                'columns' => ['Ukuran', $longTitle],
                'rows' => [
                    ['S', '24 cm']
                ],
                'note' => 'Catatan',
            ]);

        $response->assertSessionHasErrors(['columns.1']);
    }

    public function test_public_product_detail_page_renders_dynamic_columns_and_rows()
    {
        // Set custom columns and rows first
        Setting::set('size_guide_columns', ['Size Baju', 'Lebar Badan', 'Berat Bayi']);
        Setting::set('size_guide', [
            ['Mini', '20 cm', '2-4 kg'],
            ['Medium', '24 cm', '4-6 kg']
        ]);

        // Create product category and product
        $category = Category::create([
            'name' => 'Baju Anak',
            'slug' => 'baju-anak',
        ]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Setelan Piyama Bayi',
            'slug' => 'setelan-piyama-bayi',
            'sku' => 'PYM-001',
            'price' => 75000,
            'status' => 'ready',
        ]);

        $response = $this->get(route('catalog.show', ['slug' => $product->slug]));
        $response->assertStatus(200);

        // Assert column names are visible in the size guide table on public page
        $response->assertSee('Size Baju');
        $response->assertSee('Lebar Badan');
        $response->assertSee('Berat Bayi');

        // Assert cell values are visible in the size guide table
        $response->assertSee('Mini');
        $response->assertSee('20 cm');
        $response->assertSee('2-4 kg');
        $response->assertSee('Medium');
        $response->assertSee('24 cm');
        $response->assertSee('4-6 kg');
    }
}
