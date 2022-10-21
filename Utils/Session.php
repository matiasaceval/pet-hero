<?php

namespace Utils;

abstract class Session {

    public static function Init(): void {
        session_start();
    }

    public static function Set(string $key, mixed $value): void {
        $_SESSION[$key] = $value;
    }

    public static function Unset(string $key): void {
        unset($_SESSION[$key]);
    }

    public static function Get(string $key): mixed {
        return $_SESSION[$key] ?? null;
    }

    public static function VerifySession(string $key): bool {
        return isset($_SESSION[$key]);
    }

    public static function Logout(): void {
        session_destroy();
    }
}

?>
