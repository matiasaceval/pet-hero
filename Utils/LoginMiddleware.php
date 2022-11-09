<?php

namespace Utils;

abstract class LoginMiddleware {
    public static function VerifyOwner() {
        if (!Session::VerifySession("owner")) {
            Session::Set("error", "You must be logged in to access this page.");
            header("location:" . FRONT_ROOT . "Owner/LoginView");
            exit;
        }
    }

    public static function VerifyKeeper() {
        if (Session::VerifySession("keeper") == false) {
            Session::Set("error", "You must be logged in to access this page.");
            header("location:" . FRONT_ROOT . "Keeper/LoginView");
            exit;
        }
    }

    public static function IfLoggedGoToIndex() {
        if (Session::VerifySession("owner")) {
            header("Location: " . FRONT_ROOT . "Owner");
            exit;
        } else if (Session::VerifySession("keeper")) {
            header("Location: " . FRONT_ROOT . "Keeper");
            exit;
        }
    }
}