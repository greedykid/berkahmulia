<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackSiteVisits
{
    /**
     * How many days of per-visit rows to keep for the trend chart.
     */
    private const RETENTION_DAYS = 30;

    /**
     * Bot/crawler signatures to ignore when counting visits.
     */
    private const BOT_SIGNATURES = [
        'bot', 'crawl', 'spider', 'slurp', 'mediapartners',
        'googlebot', 'bingbot', 'yandex', 'baiduspider', 'duckduckbot',
        'facebookexternalhit', 'ia_archiver', 'semrush', 'ahrefs',
        'python-requests', 'curl', 'wget', 'headless',
    ];

    /**
     * Count one visit per browser session, ignoring bots and AJAX/non-GET requests.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->shouldCount($request)) {
            $request->session()->put('has_visited', true);

            // Lifetime running total (for the stats card)
            $current = (int) Setting::get('site_visits', 0);
            Setting::set('site_visits', $current + 1);

            // Timestamped row (for the trend chart)
            Visit::create(['visited_at' => now()]);

            $this->pruneOldVisits();
        }

        return $next($request);
    }

    /**
     * Determine whether the current request represents a new human visit.
     */
    private function shouldCount(Request $request): bool
    {
        if (!$request->isMethod('GET') || $request->ajax()) {
            return false;
        }

        if ($request->session()->has('has_visited')) {
            return false;
        }

        return !$this->isBot($request->userAgent());
    }

    /**
     * Occasionally drop visit rows older than the retention window so the
     * table stays small without needing a scheduled task.
     */
    private function pruneOldVisits(): void
    {
        if (random_int(1, 100) === 1) {
            Visit::where('visited_at', '<', now()->subDays(self::RETENTION_DAYS))->delete();
        }
    }

    /**
     * Detect known bots/crawlers by their User-Agent string.
     */
    private function isBot(?string $userAgent): bool
    {
        if (empty($userAgent)) {
            return true;
        }

        $userAgent = strtolower($userAgent);

        foreach (self::BOT_SIGNATURES as $signature) {
            if (str_contains($userAgent, $signature)) {
                return true;
            }
        }

        return false;
    }
}
