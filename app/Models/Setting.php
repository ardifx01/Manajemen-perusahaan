<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    
    protected $fillable = [
        'key',
        'value'
    ];

    public $timestamps = true;

    /**
     * Dapatkan nilai setting berdasarkan key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set nilai setting
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function setValue($key, $value)
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
