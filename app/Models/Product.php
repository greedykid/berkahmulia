<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'description',
        'price',
        'status',
        'is_popular',
    ];

    protected $casts = [
        'is_popular' => 'boolean',
    ];

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('featured_products');
        });

        static::deleting(function ($product) {
            // Reclaim disk space for product and variant images on delete.
            // Runs for both soft and force deletes (there is no restore flow),
            // and covers every caller (single, bulk, cascade) in one place.
            $product->loadMissing('images', 'variants');

            foreach ($product->images as $img) {
                if ($img->image_path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($img->image_path);
                }
            }

            foreach ($product->variants as $variant) {
                if ($variant->image_path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($variant->image_path);
                }
            }
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('featured_products');
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        if ($this->price == 0 && $this->variants->isNotEmpty()) {
            $prices = $this->variants->map(function ($variant) {
                return ($variant->price !== null && $variant->price !== '') ? (float) $variant->price : 0.0;
            })->filter(function ($p) {
                return $p > 0;
            })->toArray();

            if (empty($prices)) {
                $prices = [0.0];
            }
            
            $minPrice = min($prices);
            $maxPrice = max($prices);

            if ($minPrice === $maxPrice) {
                return 'Rp ' . number_format($minPrice, 0, ',', '.');
            }
            return 'Rp ' . number_format($minPrice, 0, ',', '.') . ' - Rp ' . number_format($maxPrice, 0, ',', '.');
        }

        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
