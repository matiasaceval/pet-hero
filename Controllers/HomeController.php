<?php

namespace Controllers;

use Utils\Session;

class HomeController {
    public function Index() {
        if (Session::VerifySession("owner")) {
            header("Location: " . FRONT_ROOT . "Owner");
            exit;
        } else if (Session::VerifySession("keeper")) {
            header("Location: " . FRONT_ROOT . "Keeper");
            exit;
        }

        require_once(VIEWS_PATH . "index.php");
    }

    public function NotFound() {
        require_once(VIEWS_PATH . "404.php");
    }

    public function LogOut() {
        Session::Logout();
        header("Location: " . FRONT_ROOT);
        exit;
    }
}

?>