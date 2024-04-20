<?php

namespace App;

class Config {
    private static $settings = [
        'db' => [
            'host' => '',
            'username' => '',
            'password' => '',
            'database' => ''
        ],
    ];

    public static function get($key) {
        $path = explode('.', $key);
        $value = self::$settings;

        foreach ($path as $piece) {
            if (isset($value[$piece])) {
                $value = $value[$piece];
            } else {
                return null;
            }
        }

        return $value;
    }
}
