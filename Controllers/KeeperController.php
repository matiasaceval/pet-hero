<?php

namespace Controllers;

use DAO\KeeperDAOJson as KeeperDAO;
use DAO\ReservationDAOJson as ReservationDAO;
use DAO\ReviewsDAOJson as ReviewsDAO;
use Models\Keeper as Keeper;
use Models\ReservationState as ReservationState;
use Models\Stay as Stay;
use Utils\LoginMiddleware;
use Utils\Session;
use Utils\TempValues;

class KeeperController {
    private KeeperDAO $keeperDAO;
    private ReservationDAO $reservationDAO;
    private ReviewsDAO $reviewsDAO;

    public function __construct() {
        $this->keeperDAO = new KeeperDAO();
        $this->reservationDAO = new ReservationDAO();
        $this->reviewsDAO = new ReviewsDAO();
    }

    public function Index() {
        LoginMiddleware::VerifyKeeper();

        $keeper = Session::Get("keeper");
        $reservationsOngoing = $this->reservationDAO->GetByKeeperId($keeper->getId());
        $reservations = $this->reservationDAO->GetByKeeperId($keeper->getId());
        $availableDays = $keeper->getAvailableDays($reservations);
        require_once(VIEWS_PATH . "keeper-home.php");
    }

    /* Keeper Sign Up */
    /* -------------------------------------------------------------------------- */
    public function SignUp(string $firstname, string $lastname, string $email, string $phone, string $password, string $confirmPassword) {
        // if there's an keeper session already, redirect to home
        LoginMiddleware::IfLoggedGoToIndex();

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

        TempValues::UnsetValues();
        TempValues::InitValues(["keeper" => $keeper]);
        header("location:" . FRONT_ROOT . "Keeper/SetFeeStayView");
    }

    public function SignUpView() {
        LoginMiddleware::IfLoggedGoToIndex();
        TempValues::InitValues(["back-page" => FRONT_ROOT]);
        require_once(VIEWS_PATH . "keeper-signup.php");
    }

    /* -------------------------------------------------------------------------- */


    /* Keeper Login */
    /* -------------------------------------------------------------------------- */

    public function Login(string $email, string $password) {
        $keeper = $this->keeperDAO->GetByEmail($email);
        if ($keeper != null && password_verify($password, $keeper->getPassword())) {
            Session::Set("keeper", $keeper);
            header("Location: " . FRONT_ROOT . "Keeper");
            exit;
        }

        TempValues::InitValues(["email" => $email]);
        Session::Set("error", "Invalid credentials");
        header("Location: " . FRONT_ROOT . "Keeper/LoginView");
    }

    public function LoginView() {
        LoginMiddleware::IfLoggedGoToIndex();
        TempValues::InitValues(["back-page" => FRONT_ROOT]);
        require_once(VIEWS_PATH . "keeper-login.php");
    }

    /* -------------------------------------------------------------------------- */

    public function SetFeeStay($fee, $since, $until) {
        $tempKeeper = TempValues::GetValue("keeper-set-fee-stay");

        $keeper = $tempKeeper;
        $stay = new Stay();
        if ($tempKeeper == null) {
            LoginMiddleware::VerifyKeeper();

            $keeper = Session::Get("keeper");
            $stay = $keeper->getStay();
        }

        $stay->setSince($since);
        $stay->setUntil($until);

        $keeper->setFee($fee);
        $keeper->setStay($stay);

        if ($tempKeeper) {
            $this->keeperDAO->Add($keeper);
        } else {
            $this->keeperDAO->Update($keeper);
        }

        Session::Set("keeper", $keeper);
        header("Location: " . FRONT_ROOT . "Keeper");
    }

    public function Reviews($id = null) {
        LoginMiddleware::VerifyKeeper();
        $keeper = $id ? $this->keeperDAO->GetById($id) : Session::Get("keeper");
        if ($keeper == null) {
            header("location:" . FRONT_ROOT . "Home/NotFound");
            exit;
        }
        $reviews = $this->reviewsDAO->GetByKeeperId($keeper->getId());
        TempValues::InitValues(["back-page" => FRONT_ROOT]);
        require_once(VIEWS_PATH . "keeper-reviews.php");
    }

    public function SetFeeStayView() {
        if (TempValues::ValueExist("keeper") == false) {
            LoginMiddleware::VerifyKeeper();
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

    public function PendingReservationsView() {
        LoginMiddleware::VerifyKeeper();
        $keeper = Session::Get("keeper");
        $reservations = $this->reservationDAO->GetByKeeperIdAndState($keeper->getId(), ReservationState::PENDING);
        TempValues::InitValues(["back-page" => FRONT_ROOT]);
        require_once(VIEWS_PATH . "keeper-pending-reservations.php");
    }

    public function Reservations() {
        LoginMiddleware::VerifyKeeper();
        $keeper = Session::Get("keeper");
        $reservations = $this->reservationDAO->GetByKeeperId($keeper->getId());
        TempValues::InitValues(["back-page" => FRONT_ROOT]);
        require_once(VIEWS_PATH . "keeper-reservations.php");
    }

    public function OngoingReservations() {
        LoginMiddleware::VerifyKeeper();
        $keeper = Session::Get("keeper");
        $reservations = $this->reservationDAO->GetByKeeperIdAndStates($keeper->getId(), [ReservationState::ACCEPTED, ReservationState::PAID, ReservationState::IN_PROGRESS]);
        TempValues::InitValues(["back-page" => FRONT_ROOT]);
        require_once(VIEWS_PATH . "keeper-reservations.php");
    }

    public function ConfirmReservation(int $id) {
        LoginMiddleware::VerifyKeeper();

        $reservation = $this->reservationDAO->GetById($id);

        if ($reservation == null || $reservation->getKeeper()->getId() != Session::Get("keeper")->getId()) {
            header("location:" . FRONT_ROOT . "Home/NotFound");
            exit;
        }

        $reservation->setState(ReservationState::ACCEPTED);
        $this->reservationDAO->Update($reservation);
        header("location:" . FRONT_ROOT . "Keeper/PendingReservationsView");
    }

    public function RejectReservation(int $id) {
        LoginMiddleware::VerifyKeeper();

        $reservation = $this->reservationDAO->GetById($id);

        if ($reservation == null || $reservation->getKeeper()->getId() != Session::Get("keeper")->getId()) {
            header("location:" . FRONT_ROOT . "Home/NotFound");
            exit;
        }

        $reservation->setState(ReservationState::REJECTED);
        $this->reservationDAO->Update($reservation);
        header("location:" . FRONT_ROOT . "Keeper/PendingReservationsView");
    }
}
