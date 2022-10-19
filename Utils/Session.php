<?php

namespace Utils;

abstract class Session {

    public static function Init(): void {
        session_start();
    }

    public static function Set(string $key, mixed $value): void {
        $_SESSION[$key] = $value;
    }

    public static function Get(string $key): mixed {
        if (isset($_SESSION[$key]))
            return $_SESSION[$key];
        return null;
    }

    public static function VerifySession(string $key): bool {
        return isset($_SESSION[$key]);
    }

    public static function Logout(): void {
        session_destroy();
        require_once(VIEWS_PATH . "index.php");
    }
}

