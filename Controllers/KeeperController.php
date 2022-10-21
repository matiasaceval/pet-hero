<?php

namespace Controllers;

use DAO\KeeperDAOJson as KeeperDAO;
use DAO\PetDAOJson as PetDAO;
use Models\Keeper as Keeper;
use Models\Pet;
use Utils\Session;

class KeeperController {
    private KeeperDAO $keeperDAO;
    private PetDAO $petDAO;

    public function __construct() {
        $this->keeperDAO = new KeeperDAO();
        $this->petDAO = new PetDAO();
    }

    public function Index() {
        if (Session::VerifySession("keeper") == false) {
            Session::Set("error", "You must be logged in to access this page.");
            header("location:" . FRONT_ROOT . "Keeper/LoginView");
        }
        // TODO: Keeper home view
    }

    public function SignUp(string $firstname, string $lastname, string $email, string $phone, string $password, string $confirmPassword) {
        // if there's an keeper session already, redirect to home
        $this->VerifyKeeper();

        if ($password != $confirmPassword) {
            Session::Set("error", "Passwords do not match");
            header("location:" . FRONT_ROOT . "Keeper/SignUpView");
        }

        $keeper = new Keeper();
        $keeper->setFirstname($firstname);
        $keeper->setLastname($lastname);
        $keeper->setEmail($email);
        $keeper->setPhone($phone);
        $keeper->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $keeper->setFee(-1);
        $keeper->setReviews([]);
        
        if ($this->keeperDAO->GetByEmail($email) != null) {
            Session::Set("error", "Email already exists");
            header("Location: " . FRONT_ROOT . "Keeper/SignUpView");
        }

        $this->keeperDAO->Add($keeper);

        Session::Set("keeper", $keeper);
        header("location:" . FRONT_ROOT . "Keeper");
    }

    public function Login(string $email, string $password) {
        $keeper = $this->keeperDAO->GetByEmail($email);
        if ($keeper != null && password_verify($password, $keeper->getPassword())) {
            Session::Set("keeper", $keeper);
            header("Location: " . FRONT_ROOT . "Keeper");
        }

        Session::Set("error", "Invalid credentials");
        header("Location: " . FRONT_ROOT . "Keeper/LoginView");
    }

    public function LogOut() {
        Session::Logout();
        header("Location: " . FRONT_ROOT . "Home/Index");
    }

    public function SignUpView() {
        $this->VerifyKeeper();
        require_once(VIEWS_PATH . "keeper-signup.php");
    }

    public function LoginView() {
        $this->VerifyKeeper();
        require_once(VIEWS_PATH . "keeper-login.php");
    }

    private function VerifyKeeper() {
        if (Session::VerifySession("keeper")) {
            header("Location: " . FRONT_ROOT . "Keeper");
        }
    }
}