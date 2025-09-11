<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DatabaseService
{
    /**
     * Get the current database driver name
     *
     * @return string
     */
    public static function getDriver()
    {
        return config('database.default');
    }

    /**
     * Check if using SQLite database
     *
     * @return bool
     */
    public static function isSQLite()
    {
        return self::getDriver() === 'sqlite';
    }

    /**
     * Get year expression for raw queries
     *
     * @param string $column
     * @return string
     */
    public static function year($column)
    {
        if (self::isSQLite()) {
            return "strftime('%Y', {$column})";
        }
        return "YEAR({$column})";
    }

    /**
     * Get month expression for raw queries
     *
     * @param string $column
     * @return string
     */
    public static function month($column)
    {
        if (self::isSQLite()) {
            return "strftime('%m', {$column})";
        }
        return "MONTH({$column})";
    }

    /**
     * Create a where clause for year
     *
     * @param string $column
     * @param mixed $value
     * @return array
     */
    public static function whereYear($column, $value)
    {
        if (self::isSQLite()) {
            return [DB::raw("strftime('%Y', {$column}) = ?"), $value];
        }
        return [DB::raw("YEAR({$column}) = ?"), $value];
    }

    /**
     * Create a where clause for month
     *
     * @param string $column
     * @param mixed $value
     * @return array
     */
    public static function whereMonth($column, $value)
    {
        if (self::isSQLite()) {
            return [DB::raw("strftime('%m', {$column}) = ?"), str_pad($value, 2, '0', STR_PAD_LEFT)];
        }
        return [DB::raw("MONTH({$column}) = ?"), $value];
    }
}
