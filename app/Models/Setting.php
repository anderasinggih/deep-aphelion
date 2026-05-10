<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        return \Illuminate\Support\Facades\Cache::remember('setting_' . $key, 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    protected static function booted()
    {
        static::saved(function ($setting) {
            \Illuminate\Support\Facades\Cache::forget('setting_' . $setting->key);
        });

        static::deleted(function ($setting) {
            \Illuminate\Support\Facades\Cache::forget('setting_' . $setting->key);
        });
    }

}
