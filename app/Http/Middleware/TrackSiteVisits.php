<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackSiteVisits
{
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

            $current = (int) Setting::get('site_visits', 0);
            Setting::set('site_visits', $current + 1);
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
