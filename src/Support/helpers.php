<?php

use Chaos\Core\Support\Config;

if (!function_exists('config')) {
    function config(?string $name = null, $default = null)
    {
        if ($name === null) {
            return Config::class;
        }
        return Config::get($name, $default);
    }
}
