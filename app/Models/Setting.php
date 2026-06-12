<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get setting value by key (JSON decoded) with caching
     */
    public static function get(string $key, $default = null)
    {
        return \Illuminate\Support\Facades\Cache::remember("setting.{$key}", 3600, function() use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? json_decode($setting->value, true) : $default;
        });
    }

    /**
     * Set setting value by key (encoded to JSON) and clear cache
     */
    public static function set(string $key, $value)
    {
        \Illuminate\Support\Facades\Cache::forget("setting.{$key}");
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => json_encode($value)]
        );
    }
}
