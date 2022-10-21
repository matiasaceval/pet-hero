<?php

namespace Controllers;

use DAO\KeeperDAOJson as KeeperDAO;
use DAO\StayDAOJson as StayDAO;
use Models\Keeper as Keeper;
use Models\Stay as Stay;
use Utils\Session;

class KeeperController {
    private KeeperDAO $keeperDAO;
    private StayDAO $stayDAO;

    public function __construct() {
        $this->keeperDAO = new KeeperDAO();
        $this->stayDAO = new StayDAO();
    }

    public function Index() {
        $this->VerifyIsLogged();
        // TODO: Keeper home view

        $keeper = Session::Get("keeper");
        echo "¡Hola " . $keeper->getFirstname() . "!";

        echo "<pre>";
        var_dump($keeper->getStay());
        var_dump($keeper->getReviews());
        echo "</pre>";
    }

    public function SignUp(string $firstname, string $lastname, string $email, string $phone, string $password, string $confirmPassword) {
        // if there's an keeper session already, redirect to home
        $this->IfLoggedGoToIndex();

        if ($password != $confirmPassword) {
            Session::Set("error", "Passwords do not match");
            header("location:" . FRONT_ROOT . "Keeper/SignUpView");
            exit;
        }
        if ($this->keeperDAO->GetByEmail($email) != null) {
            Session::Set("error", "Email already exists");
            header("Location: " . FRONT_ROOT . "Keeper/SignUpView");
            exit;
        }

        $keeper = new Keeper();
        $keeper->setId($this->keeperDAO->GetNextId());
        $keeper->setFirstname($firstname);
        $keeper->setLastname($lastname);
        $keeper->setEmail($email);
        $keeper->setPhone($phone);
        $keeper->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $keeper->setFee(-1);
        $keeper->setReviews([]);


        Session::Set("keeper", $keeper);
        header("location:" . FRONT_ROOT . "Keeper/SetFeeStayView");
    }

    public function Login(string $email, string $password) {
        $keeper = $this->keeperDAO->GetByEmail($email);
        if ($keeper != null && password_verify($password, $keeper->getPassword())) {
            Session::Set("keeper", $keeper);
            header("Location: " . FRONT_ROOT . "Keeper/Index");
            exit;
        }

        Session::Set("error", "Invalid credentials");
        header("Location: " . FRONT_ROOT . "Keeper/LoginView");
    }

    public function LogOut() {
        Session::Logout();
        header("Location: " . FRONT_ROOT . "Keeper/LoginView");
    }

    public function SignUpView() {
        $this->IfLoggedGoToIndex();
        require_once(VIEWS_PATH . "keeper-signup.php");
    }

    public function LoginView() {
        $this->IfLoggedGoToIndex();
        require_once(VIEWS_PATH . "keeper-login.php");
    }

    public function SetFeeStay($fee, $since, $until) {
        $this->VerifyIsLogged();

        $keeper = Session::Get("keeper");
        $keeper->setFee($fee);

        $stay = new Stay();
        $stay->setId($keeper->getId());
        $stay->setSince($since);
        $stay->setUntil($until);

        $keeper->setStay($stay);

        $this->keeperDAO->Add($keeper);
        $this->stayDAO->Add($stay);

        header("Location: " . FRONT_ROOT . "Keeper/Index");
    }

    public function SetFeeStayView() {
        $this->VerifyIsLogged();
        require_once(VIEWS_PATH . "keeper-set-fee-stay.php");
    }

    private function IfLoggedGoToIndex() {
        if (Session::VerifySession("owner")) {
            header("Location: " . FRONT_ROOT . "Owner");
			exit;
        } else if (Session::VerifySession("keeper")) {
            header("Location: " . FRONT_ROOT . "Keeper");
            exit;
        }
    }

    private function VerifyIsLogged() {
        if (Session::VerifySession("keeper") == false) {
            Session::Set("error", "You must be logged in to access this page.");
            header("location:" . FRONT_ROOT . "Keeper/LoginView");
            exit;
        }
    }
}