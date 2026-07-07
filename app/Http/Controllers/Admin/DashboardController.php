<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\Visit;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display admin overview dashboard
     */
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();

        // Total public site visits (session-based, bot-filtered)
        $siteVisits = (int) Setting::get('site_visits', 0);

        // Low stock warning: variants with stock <= 5
        $lowStockCount = ProductVariant::where('stock', '>', 0)
            ->where('stock', '<=', 5)
            ->count();

        // Out of stock warning: variants with stock == 0 of active products (excluding soft-deleted)
        $outOfStockCount = ProductVariant::whereHas('product')->where('stock', 0)->count();

        // Latest added products
        $latestProducts = Product::with(['category', 'images'])
            ->latest()
            ->take(5)
            ->get();

        // Low stock items details
        $lowStockVariants = ProductVariant::with('product')
            ->whereHas('product')
            ->where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'siteVisits',
            'lowStockCount',
            'outOfStockCount',
            'latestProducts',
            'lowStockVariants'
        ));
    }

    /**
     * Return bucketed visit counts as JSON for the dashboard trend chart.
     * Ranges: 4h (per 30 min), 1d (per hour), 1w (per day).
     */
    public function visitStats(Request $request)
    {
        $range = $request->query('range', '1d');

        // [interval in minutes, number of buckets, label format]
        [$intervalMinutes, $count, $labelFormat] = match ($range) {
            '4h' => [30, 8, 'H:i'],
            '1w' => [60 * 24, 7, 'd M'],
            default => [60, 24, 'H:i'],
        };
        if (!in_array($range, ['4h', '1d', '1w'], true)) {
            $range = '1d';
        }

        // Align the most recent bucket to an interval boundary starting from midnight.
        $now = now();
        $minutesToday = $now->hour * 60 + $now->minute;
        $flooredMinutes = intdiv($minutesToday, $intervalMinutes) * $intervalMinutes;
        $lastBucketStart = $now->copy()->startOfDay()->addMinutes($flooredMinutes);

        // Build the ordered list of bucket start times (oldest first).
        $buckets = [];
        for ($i = $count - 1; $i >= 0; $i--) {
            $buckets[] = $lastBucketStart->copy()->subMinutes($i * $intervalMinutes);
        }
        $windowStart = $buckets[0]->copy();

        $data = array_fill(0, $count, 0);
        $visits = Visit::where('visited_at', '>=', $windowStart)->pluck('visited_at');
        foreach ($visits as $visitedAt) {
            $index = intdiv((int) floor($windowStart->diffInMinutes($visitedAt)), $intervalMinutes);
            if ($index >= 0 && $index < $count) {
                $data[$index]++;
            }
        }

        $labels = array_map(fn ($bucket) => $bucket->format($labelFormat), $buckets);

        return response()->json([
            'range' => $range,
            'labels' => $labels,
            'data' => $data,
            'total' => array_sum($data),
        ]);
    }

    /**
     * Display admin guide/help page
     */
    public function guide()
    {
        return view('admin.guide');
    }
}
