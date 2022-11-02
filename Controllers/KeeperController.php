<?php

namespace Controllers;

use DAO\KeeperDAOJson as KeeperDAO;
use DAO\ReservationDAOJson as ReservationDAO;
use Models\Keeper as Keeper;
use Models\Stay as Stay;
use Models\Reservation as Reservation;
use Models\ReservationState as ReservationState;
use Utils\Session;
use Utils\TempValues;

class KeeperController
{
    private KeeperDAO $keeperDAO;
    private ReservationDAO $reservationDAO;

    public function __construct()
    {
        $this->keeperDAO = new KeeperDAO();
        $this->reservationDAO = new ReservationDAO();
    }

    public function Index()
    {
        $this->VerifyIsLogged();
        // TODO: Keeper home view

        $keeper = Session::Get("keeper");
        echo "¡Hola " . $keeper->getFirstname() . "!";

        echo "<pre>";
        var_dump($keeper->getStay());
        var_dump($keeper->getReviews());
        echo "</pre>";
    }

    public function SignUp(string $firstname, string $lastname, string $email, string $phone, string $password, string $confirmPassword)
    {
        // if there's an keeper session already, redirect to home
        $this->IfLoggedGoToIndex();

        TempValues::InitValues(["firstname" => $firstname, "lastname" => $lastname, "email" => $email, "phone" => $phone]);

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

        TempValues::UnsetValues();
        TempValues::InitValues(["keeper" => $keeper]);
        header("location:" . FRONT_ROOT . "Keeper/SetFeeStayView");
    }

    public function Login(string $email, string $password)
    {
        $keeper = $this->keeperDAO->GetByEmail($email);
        if ($keeper != null && password_verify($password, $keeper->getPassword())) {
            Session::Set("keeper", $keeper);
            header("Location: " . FRONT_ROOT . "Keeper/Index");
            exit;
        }

        TempValues::InitValues(["email" => $email]);
        Session::Set("error", "Invalid credentials");
        header("Location: " . FRONT_ROOT . "Keeper/LoginView");
    }

    public function LogOut()
    {
        Session::Logout();
        header("Location: " . FRONT_ROOT);
        exit;
    }

    public function SignUpView()
    {
        $this->IfLoggedGoToIndex();
        TempValues::InitValues(["back-page" => FRONT_ROOT]);
        require_once(VIEWS_PATH . "keeper-signup.php");
    }

    public function LoginView()
    {
        $this->IfLoggedGoToIndex();
        TempValues::InitValues(["back-page" => FRONT_ROOT]);
        require_once(VIEWS_PATH . "keeper-login.php");
    }

    public function SetFeeStay($fee, $since, $until)
    {
        $tempKeeper = TempValues::GetValue("keeper-set-fee-stay");

        $keeper = $tempKeeper;
        $stay = new Stay();
        if ($tempKeeper == null) {
            $this->VerifyIsLogged();

            $keeper = Session::Get("keeper");
            $stay = $keeper->getStay();
        }

        $stay->setSince($since);
        $stay->setUntil($until);

        $keeper->setFee($fee);
        $keeper->setStay($stay);

        if ($tempKeeper) {
            $this->keeperDAO->Add($keeper, $stay);
        } else {
            $this->keeperDAO->Update($keeper, $stay);
        }

        Session::Set("keeper", $keeper);
        header("Location: " . FRONT_ROOT . "Keeper/Index");
    }

    public function Reviews($id = null)
    {
        $this->VerifyIsLogged();
        $keeper = $id ? $this->keeperDAO->GetById($id) : Session::Get("keeper");
        if ($keeper == null) {
            header("location:" . FRONT_ROOT . "Home/NotFound");
            exit;
        }
        $reviews = $keeper->getReviews();
        TempValues::InitValues(["back-page" => FRONT_ROOT]);
        require_once(VIEWS_PATH . "keeper-reviews.php");
    }

    public function SetFeeStayView()
    {
        if (TempValues::ValueExist("keeper") == false) {
            $this->VerifyIsLogged();
        }
        $tempKeeper = TempValues::GetValue("keeper");
        $keeper = $tempKeeper ?? Session::Get("keeper");
        if ($tempKeeper) {
            TempValues::InitValues(["keeper-set-fee-stay" => $keeper]);
        } else {
            TempValues::InitValues(["back-page" => FRONT_ROOT]);
        }
        include_once(VIEWS_PATH . "keeper-set-fee-stay.php");
    }

    public function ConfirmReservation(int $id)
    {
        $this->VerifyIsLogged();

        $reservation = $this->reservationDAO->GetById($id);

        if ($reservation == null) {
            header("location:" . FRONT_ROOT . "Home/NotFound");
            exit;
        }

        $reservation->setState(ReservationState::ACCEPTED);
        $this->reservationDAO->Update($reservation);
        // header("location:" . FRONT_ROOT . "Keeper/Reservations");
    }

    private function IfLoggedGoToIndex()
    {
        if (Session::VerifySession("owner")) {
            header("Location: " . FRONT_ROOT . "Owner");
            exit;
        } else if (Session::VerifySession("keeper")) {
            header("Location: " . FRONT_ROOT . "Keeper");
            exit;
        }
    }

    private function VerifyIsLogged()
    {
        if (Session::VerifySession("keeper") == false) {
            Session::Set("error", "You must be logged in to access this page.");
            header("location:" . FRONT_ROOT . "Keeper/LoginView");
            exit;
        }
    }
}
