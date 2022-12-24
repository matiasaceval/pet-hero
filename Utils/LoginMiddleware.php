<?php

namespace Utils;

abstract class LoginMiddleware
{
    public static function VerifyOwner(): void
    {
        if (!Session::VerifySession("owner")) {
            Session::Set("error", "You must be logged in to access this page.");
            header("location:" . FRONT_ROOT . "Owner/LoginView");
            exit;
        }
    }

    public static function VerifyKeeper(): void
    {
        if (Session::VerifySession("keeper") == false) {
            Session::Set("error", "You must be logged in to access this page.");
            header("location:" . FRONT_ROOT . "Keeper/LoginView");
            exit;
        }
    }

    public static function IfLoggedGoToIndex(): void
    {
        if (Session::VerifySession("owner")) {
            header("Location: " . FRONT_ROOT . "Owner");
            exit;
        } else if (Session::VerifySession("keeper")) {
            header("Location: " . FRONT_ROOT . "Keeper");
            exit;
        }
    }

    public static function IsLogged(): bool
    {
        return Session::VerifySession("owner") || Session::VerifySession("keeper");
    }
}
