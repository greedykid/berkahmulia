<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteVisitsTest extends TestCase
{
    use RefreshDatabase;

    public function test_first_visit_from_new_user_increments_counter()
    {
        $this->assertEquals(0, (int) Setting::get('site_visits', 0));

        $this->get(route('home'))->assertStatus(200);

        $this->assertEquals(1, (int) Setting::get('site_visits', 0));
    }

    public function test_refreshing_within_same_session_does_not_increment_again()
    {
        // First visit counts.
        $this->get(route('home'))->assertStatus(200);
        // Subsequent requests in the same session should be ignored.
        $this->get(route('catalog.index'))->assertStatus(200);
        $this->get(route('home'))->assertStatus(200);

        $this->assertEquals(1, (int) Setting::get('site_visits', 0));
    }

    public function test_bot_visits_are_ignored()
    {
        $this->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'])
            ->get(route('home'))
            ->assertStatus(200);

        $this->assertEquals(0, (int) Setting::get('site_visits', 0));
    }

    public function test_dashboard_displays_visit_count()
    {
        Setting::set('site_visits', 42);
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Total Kunjungan');
        $response->assertSee('42');
    }
}
