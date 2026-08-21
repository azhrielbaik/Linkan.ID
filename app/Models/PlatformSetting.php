<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PlatformSetting extends Model
{
    protected $table = 'platform_settings';

    protected $fillable = [
        'key',
        'value',
        'description',
    ];

    /**
     * Ambil nilai setting berdasarkan key dengan fallback default value.
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Simpan atau perbarui nilai setting.
     */
    public static function set(string $key, $value, ?string $description = null)
    {
        $setting = static::where('key', $key)->first();
        if ($setting) {
            $setting->value = $value;
            if ($description !== null) {
                $setting->description = $description;
            }
            $setting->save();
            return $setting;
        }

        return static::create([
            'key' => $key,
            'value' => $value,
            'description' => $description,
        ]);
    }
}
