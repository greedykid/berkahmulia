<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitStatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_real_visit_records_a_timestamped_visit_row()
    {
        $this->get(route('home'))->assertStatus(200);

        $this->assertEquals(1, Visit::count());
    }

    public function test_refresh_in_same_session_does_not_add_visit_rows()
    {
        $this->get(route('home'))->assertStatus(200);
        $this->get(route('catalog.index'))->assertStatus(200);

        $this->assertEquals(1, Visit::count());
    }

    public function test_bot_visit_does_not_record_a_visit_row()
    {
        $this->withHeaders(['User-Agent' => 'Googlebot/2.1'])
            ->get(route('home'))
            ->assertStatus(200);

        $this->assertEquals(0, Visit::count());
    }

    public function test_visit_stats_requires_admin_auth()
    {
        $this->get(route('admin.visits.stats'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_visit_stats_returns_bucketed_counts_per_range()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        Visit::create(['visited_at' => now()]);
        Visit::create(['visited_at' => now()->subMinutes(10)]);

        // 1 day -> 24 hourly buckets
        $day = $this->actingAs($admin)->getJson(route('admin.visits.stats', ['range' => '1d']));
        $day->assertStatus(200)
            ->assertJsonStructure(['range', 'labels', 'data', 'total']);
        $this->assertCount(24, $day->json('data'));
        $this->assertCount(24, $day->json('labels'));
        $this->assertEquals(2, $day->json('total'));

        // 4 hours -> 8 half-hour buckets
        $fourH = $this->actingAs($admin)->getJson(route('admin.visits.stats', ['range' => '4h']));
        $this->assertCount(8, $fourH->json('data'));

        // 1 week -> 7 daily buckets
        $week = $this->actingAs($admin)->getJson(route('admin.visits.stats', ['range' => '1w']));
        $this->assertCount(7, $week->json('data'));
        $this->assertEquals(2, $week->json('total'));
    }

    public function test_visit_stats_invalid_range_falls_back_to_one_day()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->getJson(route('admin.visits.stats', ['range' => 'bogus']));

        $response->assertStatus(200);
        $this->assertEquals('1d', $response->json('range'));
        $this->assertCount(24, $response->json('data'));
    }

    public function test_visits_outside_window_are_excluded()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        Visit::create(['visited_at' => now()]);              // inside
        Visit::create(['visited_at' => now()->subDays(3)]);  // outside the 4h/1d window

        $day = $this->actingAs($admin)->getJson(route('admin.visits.stats', ['range' => '1d']));
        $this->assertEquals(1, $day->json('total'));
    }
}
