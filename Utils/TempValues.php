<?php namespace Utils;

    abstract class TempValues {
        
        public static function InitValues($values) {
            foreach ($values as $key => $param) {
                $_SESSION["temp-".$key] = $param;
            }
        }

        public static function GetValue($key) {
            if (isset($_SESSION["temp-".$key])) {
                $value = $_SESSION["temp-".$key];
                unset($_SESSION["temp-".$key]);
                return $value;
            }
            return null;
        }

        public static function UnsetValues(){
            foreach ($_SESSION as $key => $_) {
                if (strpos($key, "temp-") !== false) {
                    unset($_SESSION[$key]);
                }
            }
        }
    }
?>