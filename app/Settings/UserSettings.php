<?php

namespace App\Settings;

class UserSettings
{

    /**
     * Available settings
     * @var array
     */
    protected static $settings = [
        'order_by', 'order', 'per_page'
    ];

    /**
     * Cache key prefix
     * @var string
     */
    protected static $cachePrefix = 'user';

    /**
     * All available cache keys
     * @var array
     */
    public static $cacheKeys = [
        'users',
    ];

    /**
     * Get user settings
     * @param $userId
     * @param $cacheKey
     * @param string $order_by
     * @param string $order
     * @param int $per_page
     * @param bool $amend
     * @return array
     */
    public static function getSettings($userId, $cacheKey, $order_by = 'updated_at', $order = 'desc', $per_page = 25, $amend = false)
    {
        $settings = [];
        $prefix = static::$cachePrefix;

        foreach( static::$settings as $setting ) {
            if ( $amend )
                cache()->forget("{$prefix}_{$userId}_{$cacheKey}_{$setting}");

            if ( cache()->has("{$prefix}_{$userId}_{$cacheKey}_{$setting}") )
                $settings["{$cacheKey}_{$setting}"] = cache("{$prefix}_{$userId}_{$cacheKey}_{$setting}");
            else {
                cache()->forever("{$prefix}_{$userId}_{$cacheKey}_{$setting}", ${$setting});
                $settings["{$cacheKey}_{$setting}"] = ${$setting};
            }
        }

        return $settings;
    }

    /**
     * Get all user settings
     * @param null $userId
     * @return array
     */
    public static function getAllUserSettings($userId = null)
    {
        $allSettings = [];

        if ( $userId ) {
            $cacheKeys = static::$cacheKeys;

            if ( count($cacheKeys) ) {
                foreach ( $cacheKeys as $cacheKey )
                    $allSettings[$cacheKey] = static::getSettings($userId, $cacheKey);
            }

        }

        return $allSettings;
    }

}