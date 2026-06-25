<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FooterAddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_footer_displays_default_address_when_not_set()
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Jl. Poin Mas 40, Sawangan , Kota Depok, Jawa Barat');
    }

    public function test_footer_displays_updated_address_when_set()
    {
        // Set dynamic address
        Setting::set('store_address', 'Jl. Berkah Raya No. 456, Bandung, Jawa Barat');

        // Check homepage footer
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Jl. Berkah Raya No. 456, Bandung, Jawa Barat');
        $response->assertDontSee('Jl. Raya Berkah Mulia No. 123');

        // Check catalog page footer
        $response = $this->get(route('catalog.index'));
        $response->assertStatus(200);
        $response->assertSee('Jl. Berkah Raya No. 456, Bandung, Jawa Barat');
        $response->assertDontSee('Jl. Raya Berkah Mulia No. 123');
    }

    public function test_footer_displays_tiktok_link_when_set()
    {
        // Set dynamic TikTok URL
        Setting::set('tiktok_url', 'https://www.tiktok.com/@berkahmulia');

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        // Should display the parsed username and TikTok icon container/link
        $response->assertSee('@berkahmulia');
        $response->assertSee('fa-tiktok');

        $response = $this->get(route('catalog.index'));
        $response->assertStatus(200);
        $response->assertSee('@berkahmulia');
        $response->assertSee('fa-tiktok');
    }

    public function test_footer_hides_tiktok_link_when_empty()
    {
        // Set empty TikTok URL
        Setting::set('tiktok_url', '');

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertDontSee('fa-tiktok');

        $response = $this->get(route('catalog.index'));
        $response->assertStatus(200);
        $response->assertDontSee('fa-tiktok');
    }

    public function test_navbar_displays_tiktok_link_when_set_and_enabled()
    {
        Setting::set('tiktok_url', 'https://www.tiktok.com/@berkahmulia');
        Setting::set('show_tiktok_nav', true);

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        // Header navigation aria-label check or icon check
        $response->assertSee('TikTok Berkah Mulia');

        $response = $this->get(route('catalog.index'));
        $response->assertStatus(200);
        $response->assertSee('TikTok Berkah Mulia');
    }

    public function test_navbar_hides_tiktok_link_when_disabled()
    {
        Setting::set('tiktok_url', 'https://www.tiktok.com/@berkahmulia');
        Setting::set('show_tiktok_nav', false);

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        // Should not see the navbar button, but should still see it in footer
        // Let's verify that the navbar button specifically is hidden.
        // The navbar button has `aria-label="TikTok Berkah Mulia"`.
        $response->assertDontSee('TikTok Berkah Mulia');

        $response = $this->get(route('catalog.index'));
        $response->assertStatus(200);
        $response->assertDontSee('TikTok Berkah Mulia');
    }

    public function test_footer_displays_tiktok_custom_name_when_set()
    {
        Setting::set('tiktok_url', 'https://www.tiktok.com/@berkahmulia');
        Setting::set('tiktok_name', 'Berkah Mulia Shop');

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Berkah Mulia Shop');

        $response = $this->get(route('catalog.index'));
        $response->assertStatus(200);
        $response->assertSee('Berkah Mulia Shop');
    }
}
