<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingKontakTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_kontak_page_requires_admin_auth()
    {
        $response = $this->get(route('admin.settings.kontak'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_settings_kontak_page()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)
            ->get(route('admin.settings.kontak'));

        $response->assertStatus(200);
        $response->assertSee('Kontak Toko');
        $response->assertSee('Nomor WhatsApp');
    }

    public function test_admin_can_save_valid_whatsapp_number_with_suffix_only()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)
            ->post(route('admin.settings.updateKontak'), [
                'whatsapp_number' => '82112619691', // suffix only
                'admin_email' => 'testadmin@bmberkahmulia.com',
            ]);

        $response->assertRedirect(route('admin.settings.kontak'));
        $response->assertSessionHas('success');

        // Check if DB setting is updated with country code prepended
        $this->assertEquals('6282112619691', \App\Models\Setting::get('whatsapp_number'));
        $this->assertEquals('testadmin@bmberkahmulia.com', \App\Models\Setting::get('admin_email'));
    }

    public function test_admin_saving_whatsapp_number_with_redundant_prefixes_is_normalized()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        // Test with 08... prefix
        $response = $this->actingAs($admin)
            ->post(route('admin.settings.updateKontak'), [
                'whatsapp_number' => '0821-1261-9691', // zero prefix & dashes
                'admin_email' => 'testadmin@bmberkahmulia.com',
            ]);

        $response->assertRedirect(route('admin.settings.kontak'));
        
        // Ensure DB setting has normalized value starting with 628
        $this->assertEquals('6282112619691', \App\Models\Setting::get('whatsapp_number'));
        $this->assertEquals('testadmin@bmberkahmulia.com', \App\Models\Setting::get('admin_email'));

        // Test with 628... prefix
        $response = $this->actingAs($admin)
            ->post(route('admin.settings.updateKontak'), [
                'whatsapp_number' => '+62 821 1261 9691', // country code prefix, spaces & plus
                'admin_email' => 'testadmin@bmberkahmulia.com',
            ]);

        $response->assertRedirect(route('admin.settings.kontak'));
        
        // Ensure DB setting has normalized value starting with 628
        $this->assertEquals('6282112619691', \App\Models\Setting::get('whatsapp_number'));
        $this->assertEquals('testadmin@bmberkahmulia.com', \App\Models\Setting::get('admin_email'));
    }

    public function test_validation_fails_for_invalid_whatsapp_numbers()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        // Invalid: does not start with 8 (starts with 7)
        $response = $this->actingAs($admin)
            ->post(route('admin.settings.updateKontak'), [
                'whatsapp_number' => '72112619691',
                'admin_email' => 'testadmin@bmberkahmulia.com',
            ]);

        $response->assertSessionHasErrors(['whatsapp_number']);

        // Invalid: too short
        $response = $this->actingAs($admin)
            ->post(route('admin.settings.updateKontak'), [
                'whatsapp_number' => '821126',
                'admin_email' => 'testadmin@bmberkahmulia.com',
            ]);

        $response->assertSessionHasErrors(['whatsapp_number']);

        // Invalid: too long
        $response = $this->actingAs($admin)
            ->post(route('admin.settings.updateKontak'), [
                'whatsapp_number' => '821126196912345',
                'admin_email' => 'testadmin@bmberkahmulia.com',
            ]);

        $response->assertSessionHasErrors(['whatsapp_number']);
    }

    public function test_admin_can_save_valid_tiktok_url()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)
            ->post(route('admin.settings.updateKontak'), [
                'whatsapp_number' => '82112619691',
                'admin_email' => 'testadmin@bmberkahmulia.com',
                'tiktok_url' => 'https://www.tiktok.com/@berkahmulia',
                'tiktok_name' => 'Berkah Mulia Shop',
                'show_tiktok_nav' => '1',
            ]);

        $response->assertRedirect(route('admin.settings.kontak'));
        $response->assertSessionHas('success');

        $this->assertEquals('https://www.tiktok.com/@berkahmulia', \App\Models\Setting::get('tiktok_url'));
        $this->assertEquals('Berkah Mulia Shop', \App\Models\Setting::get('tiktok_name'));
        $this->assertTrue(\App\Models\Setting::get('show_tiktok_nav'));
    }

    public function test_validation_fails_for_invalid_tiktok_url()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)
            ->post(route('admin.settings.updateKontak'), [
                'whatsapp_number' => '82112619691',
                'admin_email' => 'testadmin@bmberkahmulia.com',
                'tiktok_url' => 'not-a-valid-url',
            ]);

        $response->assertSessionHasErrors(['tiktok_url']);
    }
}
