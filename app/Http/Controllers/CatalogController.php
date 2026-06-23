<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Homepage
     */
    public function home()
    {
        $categories = collect(\Illuminate\Support\Facades\Cache::remember('nav_categories', 3600, function() {
            return Category::all()->map(function($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'image_path' => $cat->image_path
                ];
            })->toArray();
        }))->map(function($cat) {
            return (object) $cat;
        });
        
        // Eager load category and first image for products (Prioritize is_popular, fallback to latest)
        $featuredProducts = collect(\Illuminate\Support\Facades\Cache::remember('featured_products', 3600, function() {
            $baseQuery = Product::with(['category', 'images'])
                ->where('status', 'ready');

            // Check if there are any products explicitly marked as popular
            $popularExists = (clone $baseQuery)->where('is_popular', true)->exists();

            if ($popularExists) {
                $products = $baseQuery->where('is_popular', true)->latest()->take(8)->get();
            } else {
                $products = $baseQuery->latest()->take(8)->get();
            }

            return $products->map(function($prod) {
                    return [
                        'id' => $prod->id,
                        'name' => $prod->name,
                        'slug' => $prod->slug,
                        'price' => $prod->price,
                        'status' => $prod->status,
                        'category' => [
                            'name' => $prod->category->name
                        ],
                        'images' => $prod->images->map(function($img) {
                            return [
                                'image_path' => $img->image_path
                            ];
                        })->toArray()
                    ];
                })->toArray();
        }))->map(function($prod) {
            $prodObj = (object) $prod;
            $prodObj->category = (object) $prodObj->category;
            $prodObj->images = collect($prodObj->images)->map(function($img) {
                return (object) $img;
            });
            return $prodObj;
        });

        $banners = \App\Models\Setting::get('hero_banners', []);

        // Get 4 random products (with or without images)
        $randomBanners = Product::with('images')
            ->where('status', 'ready')
            ->inRandomOrder()
            ->take(4)
            ->get()
            ->map(function($prod) {
                $image = $prod->images->first();
                return [
                    'name' => $prod->name,
                    'slug' => $prod->slug,
                    'price' => $prod->price,
                    'image_path' => $image ? 'storage/' . $image->image_path : null
                ];
            })
            ->toArray();

        // Hero text settings
        $heroBadge = \App\Models\Setting::get('hero_badge', 'Koleksi Baru 2026');
        $heroTitleLine1 = \App\Models\Setting::get('hero_title_line1', 'Pakaian Lembut & Nyaman');
        $heroTitleLine2 = \App\Models\Setting::get('hero_title_line2', 'Untuk Buah Hati Anda');
        $heroDescription = \App\Models\Setting::get('hero_description', 'Dapatkan koleksi pakaian bayi, baju anak, setelan gemas, hingga pakaian dalam dengan bahan katun premium yang lembut, aman, dan menyerap keringat.');

        // Location section text settings
        $locationBadge = \App\Models\Setting::get('location_badge', 'Kunjungi Kami');
        $locationTitle = \App\Models\Setting::get('location_title', 'Lokasi Toko Offline');
        $locationDescription = \App\Models\Setting::get('location_description', 'Silakan datang langsung ke toko fisik kami untuk melihat kualitas produk pakaian bayi secara langsung, berkonsultasi mengenai pembelian grosir/partai besar, serta mendapatkan penawaran spesial reseller.');

        return view('home', compact('categories', 'featuredProducts', 'banners', 'randomBanners', 'heroBadge', 'heroTitleLine1', 'heroTitleLine2', 'heroDescription', 'locationBadge', 'locationTitle', 'locationDescription'));
    }

    /**
     * Catalog Listing Page (with filters and search)
     */
    public function index(Request $request)
    {
        $categories = collect(\Illuminate\Support\Facades\Cache::remember('nav_categories', 3600, function() {
            return Category::all()->map(function($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'image_path' => $cat->image_path
                ];
            })->toArray();
        }))->map(function($cat) {
            return (object) $cat;
        });
        
        // Build product query with eager loading to prevent N+1 query issues
        $query = Product::with(['category', 'images', 'variants']);

        // Search filter (Name or SKU)
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('sku', 'like', '%' . $searchTerm . '%');
            });
        }

        // Category filter (allows multiple selection)
        if ($request->has('categories') || $request->has('category')) {
            $categorySlugs = $request->input('categories') ?: $request->input('category');
            if (!is_array($categorySlugs)) {
                $categorySlugs = explode(',', $categorySlugs);
            }
            $categorySlugs = array_filter($categorySlugs);
            if (!empty($categorySlugs)) {
                $query->whereHas('category', function($q) use ($categorySlugs) {
                    $q->whereIn('slug', $categorySlugs);
                });
            }
        }

        // Price filters
        if ($request->has('price_min') && is_numeric($request->price_min)) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max') && is_numeric($request->price_max)) {
            $query->where('price', '<=', $request->price_max);
        }

        // Size filter
        if ($request->has('sizes') && is_array($request->sizes) && count($request->sizes) > 0) {
            $sizes = $request->sizes;
            $query->whereHas('variants', function($q) use ($sizes) {
                $q->whereIn('size', $sizes);
            });
        }

        // Sort option
        $sort = $request->input('sort', 'latest');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        // Paginate products (12 items per page)
        $products = $query->paginate(12)->withQueryString();

        // Get all unique sizes for the filter sidebar
        $availableSizes = \App\Models\ProductVariant::whereNotNull('size')
            ->select('size')
            ->distinct()
            ->pluck('size')
            ->toArray();

        return view('catalog.index', compact('categories', 'products', 'availableSizes'));
    }

    /**
     * Product Detail Page
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'images', 'variants'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Get related products from the same category
        $relatedProducts = Product::with(['category', 'images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('catalog.show', compact('product', 'relatedProducts'));
    }
}
