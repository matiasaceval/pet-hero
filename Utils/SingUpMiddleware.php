<?php

namespace Utils;

abstract class  SingUpMiddleware
{
    public static function VerifySecurePassword($password): bool
    {
        $regex = array();
        array_push($regex, "(?=.*[A-Z])");// Ensure string has one uppercase letter.
        array_push($regex, "(?=.*[a-z])"); // Ensure string has one special case letter.
        array_push($regex, "(?=.*[0-9].*[0-9])");// Ensure string has two digits.
        array_push($regex, ".{8}");// Ensure string is of length 8.

        for ($i = 0; $i < count($regex); $i++) {
            if (!preg_match("/" . $regex[$i] . "/", $password)) {
                return false;
            }
        }
        return true;
    }
}