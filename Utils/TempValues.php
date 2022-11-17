<?php namespace Utils;

abstract class TempValues {

    public static function InitValues($values): void
    {
        foreach ($values as $key => $param) {
            $_SESSION["temp-" . $key] = $param;
        }
    }

    public static function ValueExist($key): bool
    {
        return isset($_SESSION["temp-" . $key]);
    }

    public static function GetValue($key) {
        if (isset($_SESSION["temp-" . $key])) {
            $value = $_SESSION["temp-" . $key];
            unset($_SESSION["temp-" . $key]);
            return $value;
        }
        return null;
    }

    public static function UnsetValues(): void
    {
        foreach ($_SESSION as $key => $_) {
            if (strpos($key, "temp-") !== false) {
                unset($_SESSION[$key]);
            }
        }
    }
}

?>