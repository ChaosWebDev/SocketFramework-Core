<?php

namespace Chaos\Core\Support;

class Config
{
    protected static array $items = [];

    /**
     * Load all config files from a directory.
     */
    public static function loadFromPath(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }

        foreach (glob($path . '/*.php') as $file) {
            $key = basename($file, '.php');
            static::load($file, $key);
        }
    }

    /**
     * Load a single config file into memory.
     */
    public static function load(string $file, ?string $key = null): void
    {
        if (!file_exists($file)) {
            return;
        }

        $data = require $file;

        if (!is_array($data)) {
            return;
        }

        if ($key) {
            static::$items[$key] = $data;
        } else {
            static::$items = array_merge(static::$items, $data);
        }
    }

    /**
     * Get a config value using dot notation.
     */
    public static function get(string $name, $default = null)
    {
        $segments = explode('.', $name);
        $value = static::$items;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }

    /**
     * Set a config value at runtime.
     */
    public static function set(string $name, $value): void
    {
        $segments = explode('.', $name);
        $ref =& static::$items;

        foreach ($segments as $segment) {
            if (!isset($ref[$segment]) || !is_array($ref[$segment])) {
                $ref[$segment] = [];
            }
            $ref =& $ref[$segment];
        }

        $ref = $value;
    }
}
