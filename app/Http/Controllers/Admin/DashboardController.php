<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;

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
     * Display admin guide/help page
     */
    public function guide()
    {
        return view('admin.guide');
    }
}
