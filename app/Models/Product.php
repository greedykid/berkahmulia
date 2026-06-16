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

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('featured_products');
        });

        static::forceDeleting(function ($product) {
            \Illuminate\Support\Facades\Cache::forget('featured_products');
            // Delete all actual image files from disk when permanently deleted
            foreach ($product->images as $img) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($img->image_path);
            }
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
}
