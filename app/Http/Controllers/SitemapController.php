<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $products = Product::select('slug', 'updated_at')->latest('updated_at')->get();
        $categories = Category::select('slug', 'updated_at')->get();

        $content = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Homepage
        $content .= $this->url(route('home'), now()->toDateString(), 'daily', '1.0');

        // Catalog
        $content .= $this->url(route('catalog.index'), now()->toDateString(), 'daily', '0.9');

        // Categories
        foreach ($categories as $cat) {
            $content .= $this->url(
                route('catalog.index', ['category' => $cat->slug]),
                $cat->updated_at->toDateString(),
                'weekly',
                '0.8'
            );
        }

        // Products
        foreach ($products as $product) {
            $content .= $this->url(
                route('catalog.show', $product->slug),
                $product->updated_at->toDateString(),
                'weekly',
                '0.7'
            );
        }

        $content .= '</urlset>';

        return response($content, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    private function url(string $loc, string $lastmod, string $changefreq, string $priority): string
    {
        return "  <url>\n    <loc>{$loc}</loc>\n    <lastmod>{$lastmod}</lastmod>\n    <changefreq>{$changefreq}</changefreq>\n    <priority>{$priority}</priority>\n  </url>\n";
    }
}
