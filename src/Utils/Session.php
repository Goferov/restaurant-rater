<?php

namespace App\Utils;

class Session {
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function set(string $key, $value): void {
        $_SESSION[$key] = $value;
    }

    public function get(string $key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    public function exists(string $key): bool {
        return isset($_SESSION[$key]);
    }

    public function remove(string $key): void {
        unset($_SESSION[$key]);
    }

    public function destroy(): void {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }
}
