<?php

namespace TimeTracking;

class Config
{
    /**
     * Config file locations (in order of inclusion)
     *
     * @var array
     */
    protected static $locations = [
        '/config/*.php',
        '/config/*/*.php',
    ];

    /** @var null|array */
    protected static $config = null;

    /**
     * @return array
     */
    public static function getLocations()
    {
        return self::$locations;
    }

    /**
     * @param array $locations
     */
    public static function setLocations($locations)
    {
        self::$locations = $locations;
    }

    /**
     * Returns the config
     *
     * @param bool $reset Reload config
     *
     * @return array
     */
    public static function get($reset = false)
    {
        if ($reset || self::$config === null) {
            self::$config = static::loadConfig();
        }

        return self::$config;
    }

    /**
     * Load and merge all config files
     *
     * @return array
     */
    protected static function loadConfig()
    {
        $config = [];

        foreach (self::$locations as $location) {
            foreach (glob(_ROOT_ . $location) as $file) {
                $config = array_replace_recursive($config, include $file);
            }
        }

        return $config;
    }
}
