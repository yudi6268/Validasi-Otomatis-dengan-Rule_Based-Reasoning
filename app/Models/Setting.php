<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'description'];

    /**
     * Get setting value by key
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value
     */
    public static function set($key, $value, $description = null)
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'description' => $description]
        );
    }

    /**
     * Get available years for perjanjian
     */
    public static function getAvailableYears()
    {
        $year1 = self::get('tahun_perjanjian_1', date('Y'));
        $year2 = self::get('tahun_perjanjian_2', date('Y') + 1);
        
        return [$year1, $year2];
    }
}
