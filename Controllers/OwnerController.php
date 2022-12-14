<?php

namespace Controllers;

use DAO\SQLDAO\ChatDAO;
use DAO\SQLDAO\KeeperDAO as KeeperDAO;
use DAO\SQLDAO\OwnerDAO as OwnerDAO;
use DAO\SQLDAO\ReservationDAO as ReservationDAO;
use DAO\SQLDAO\ReviewsDAO as ReviewsDAO;
use DateTime;
use Exception;
use Models\Owner as Owner;
use Models\ReservationState;
use Utils\GenerateFile;
use Utils\LoginMiddleware;
use Utils\Session;
use Utils\SingUpMiddleware;
use Utils\TempValues;
use Utils\Email;

class OwnerController
{
    private OwnerDAO $ownerDAO;
    private KeeperDAO $keeperDAO;
    private ReservationDAO $reservationDAO;
    private ReviewsDAO $reviewsDAO;
    private ChatDAO $chatDAO;

    public function __construct()
    {
        $this->ownerDAO = new OwnerDAO();
        $this->keeperDAO = new KeeperDAO();
        $this->reservationDAO = new ReservationDAO();
        $this->reviewsDAO = new ReviewsDAO();
        $this->chatDAO = new ChatDAO();
    }

    public function Index()
    {
        LoginMiddleware::VerifyOwner();

        $owner = Session::Get("owner");
        require_once(VIEWS_PATH . "owner-home.php");
    }

    /* Owner Sign Up */
    /* -------------------------------------------------------------------------- */
    /**
     * @throws Exception
     */
    public function SignUp(string $firstname, string $lastname, string $email, string $phone, string $password, string $confirmPassword)
    {
        // if there's an owner session already, redirect to home
        LoginMiddleware::IfLoggedGoToIndex();

        TempValues::InitValues(["firstname" => $firstname, "lastname" => $lastname, "email" => $email, "phone" => $phone]);

        if (!SingUpMiddleware::VerifySecurePassword($password)) {
            Session::Set("error", "Password must have at least 8 characters, 2 digits, 1 uppercase and 1 lowercase letter");
            header("location:" . FRONT_ROOT . "Keeper/SignUpView");
            exit;
        }

        if ($password != $confirmPassword) {
            Session::Set("error", "Passwords do not match");
            header("location:" . FRONT_ROOT . "Owner/SignUpView");
            exit;
        }

        if ($this->ownerDAO->GetByEmail($email) != null) {
            Session::Set("error", "Email already exists");
            header("Location: " . FRONT_ROOT . "Owner/SignUpView");
            exit;
        }


        $owner = new Owner();
        $owner->setFirstname($firstname);
        $owner->setLastname($lastname);
        $owner->setEmail($email);
        $owner->setPhone($phone);
        $owner->setPassword(password_hash($password, PASSWORD_DEFAULT));


        $id = $this->ownerDAO->Add($owner);
        $owner->setId($id);

        TempValues::UnsetValues();
        Session::Set("owner", $owner);
        header("location:" . FRONT_ROOT . "Owner");
    }

    public function SignUpView()
    {
        LoginMiddleware::IfLoggedGoToIndex();
        $userType = "Owner";
        TempValues::InitValues(["back-page" => FRONT_ROOT]);
        require_once(VIEWS_PATH . "user-signup.php");
    }

    /* -------------------------------------------------------------------------- */


    /* Owner Login */
    /* -------------------------------------------------------------------------- */
    /**
     * @throws Exception
     */
    public function Login(string $email, string $password)
    {
        $owner = $this->ownerDAO->GetByEmail($email);
        if ($owner != null && password_verify($password, $owner->getPassword())) {
            Session::Set("owner", $owner);
            $this->chatDAO->MarkAsReceived($owner);
            header("Location: " . FRONT_ROOT . "Owner");
            exit;
        }

        TempValues::InitValues(["email" => $email]);
        Session::Set("error", "Invalid credentials");
        header("Location: " . FRONT_ROOT . "Owner/LoginView");
    }

    public function LoginView()
    {
        LoginMiddleware::IfLoggedGoToIndex();
        $userType = "Owner";
        TempValues::InitValues(["back-page" => FRONT_ROOT]);
        require_once(VIEWS_PATH . "user-login.php");
    }

    /* -------------------------------------------------------------------------- */


    /* Owner Forgot Password */

    public function ForgotPasswordView(): void
    {
        LoginMiddleware::IfLoggedGoToIndex();
        $userType = "Owner";
        TempValues::InitValues(["back-page" => FRONT_ROOT . "Owner/LoginView"]);
        require_once(VIEWS_PATH . "user-forgot-password.php");
    }

    public function ForgotPassword(string $email): void
    {
        LoginMiddleware::IfLoggedGoToIndex();
        $owner = $this->ownerDAO->GetByEmail($email);

        if ($owner != null) {
            $code = rand(10000, 99999);
            TempValues::InitValues(["code" => $code, "email" => $email]);

            $message = Email::forgotPassword($code, 'an', 'owner');

            Email::sendEmail([$owner->getEmail()], "Password Recovery", $message);
        }

        header("Location: " . FRONT_ROOT . "Owner/ForgotPasswordCodeView");
    }

    public function ForgotPasswordCodeView(): void
    {
        LoginMiddleware::IfLoggedGoToIndex();
        $userType = "Owner";

        if (TempValues::ValueExist("code")) {
            TempValues::InitValues(["back-page" => FRONT_ROOT . "Owner/ForgotPasswordView"]);
            require_once(VIEWS_PATH . "user-forgot-password-code.php");
        } else {
            header("Location: " . FRONT_ROOT . "Owner/ForgotPasswordView");
            exit;
        }
    }

    public function SubmitCode(array $code): void
    {
        LoginMiddleware::IfLoggedGoToIndex();
        $userType = "Owner";

        $code = intval(implode("", $code));
        $randCode = TempValues::GetValue("code");
        $correct = $code == $randCode;

        if ($correct) {
            TempValues::InitValues(["back-page" => FRONT_ROOT . "Owner/ForgotPasswordView"]);
            header("Location: " . FRONT_ROOT . "Owner/ResetPasswordView");
            exit;
        }

        Session::Set("error", "Invalid code!");
        header("Location: " . FRONT_ROOT . "Owner/ForgotPasswordView");
    }

    public function ResetPasswordView(): void
    {
        LoginMiddleware::IfLoggedGoToIndex();
        $userType = "Owner";

        if (TempValues::ValueExist("email")) {
            TempValues::InitValues(["back-page" => FRONT_ROOT . "Owner/ForgotPasswordView"]);
            require_once(VIEWS_PATH . "user-reset-password.php");
        } else {
            header("Location: " . FRONT_ROOT . "Owner/ForgotPasswordView");
            exit;
        }
    }

    public function ResetPassword(string $password, string $confirmPassword): void
    {
        LoginMiddleware::IfLoggedGoToIndex();
        $userType = "Owner";

        if ($password != $confirmPassword) {
            Session::Set("error", "Passwords do not match");
            header("location:" . FRONT_ROOT . "Owner/ResetPasswordView");
            exit;
        }

        if (!SingUpMiddleware::VerifySecurePassword($password)) {
            Session::Set("error", "Password must have at least 8 characters, 2 digits, 1 uppercase and 1 lowercase letter");
            header("location:" . FRONT_ROOT . "Owner/ResetPasswordView");
            exit;
        }

        $email = TempValues::GetValue("email");

        $owner = $this->ownerDAO->GetByEmail($email);

        $owner->setPassword(password_hash($password, PASSWORD_DEFAULT));

        $this->ownerDAO->Update($owner);

        TempValues::UnsetValues();
        Session::Set("success", "Password changed successfully!");
        header("Location: " . FRONT_ROOT . "Owner/LoginView");
    }

    /* -------------------------------------------------------------------------- */


    /* Owner List Keepers */
    /* -------------------------------------------------------------------------- */
    /**
     * @throws Exception
     */
    public function KeepersListView(string $since = null, string $until = null)
    {
        LoginMiddleware::VerifyOwner();
        $keeperList = $this->keeperDAO->GetAll();
        $keepersFromToday = $this->SanitizeKeepers($keeperList, $since, $until);
        TempValues::InitValues(["back-page" => FRONT_ROOT]);
        require_once(VIEWS_PATH . "owner-list-keepers.php");
    }

    private function SanitizeKeepers(array $keeperList, ?string $since, ?string $until): array
    {
        if ($since == null || $until == null) {
            // only show those who are available
            $keepersFromToday = array_filter($keeperList, function ($keeper) {
                $until = DateTime::createFromFormat("m-d-Y", $keeper->getStay()->getUntil());
                $today = new DateTime();
                return $until >= $today;
            });
        } else {
            // show those who are available between the dates
            $keepersFromToday = array_filter($keeperList, function ($keeper) use ($since, $until) {
                $keeperSince = DateTime::createFromFormat("m-d-Y", $keeper->getStay()->getSince());
                $keeperUntil = DateTime::createFromFormat("m-d-Y", $keeper->getStay()->getUntil());
                $since = DateTime::createFromFormat("m-d-Y", $since);
                $until = DateTime::createFromFormat("m-d-Y", $until);
                return ($since >= $keeperSince) && ($until <= $keeperUntil);
            });
        }

        $keepersFromToday = array_filter(
            /**
             * @throws Exception
             */
            /**
             * @throws Exception
             */
            $keepersFromToday,
            function ($keeper) {
                $reservations = $this->reservationDAO->GetByKeeperId($keeper->getId());
                $availableDays = $keeper->getAvailableDays($reservations);
                return $availableDays >= 1;
            }
        );

        usort($keepersFromToday, function ($a, $b) {
            $aDate = DateTime::createFromFormat("m-d-Y", $a->getStay()->getUntil());
            $bDate = DateTime::createFromFormat("m-d-Y", $b->getStay()->getUntil());
            return $aDate <=> $bDate;
        });

        return $keepersFromToday;
    }

    /* -------------------------------------------------------------------------- */


    /* Owner pay reservation */
    /* -------------------------------------------------------------------------- */
    /**
     * @throws Exception
     */
    public function UploadPayment(int $id, array $image)
    {
        LoginMiddleware::VerifyOwner();

        $reservation = $this->reservationDAO->GetById($id);
        if ($reservation == null) {
            header("location:" . FRONT_ROOT . "Home/NotFound");
            exit;
        }

        if ($reservation->getPet()->getOwner()->getId() != Session::Get("owner")->getId()) {
            header("location:" . FRONT_ROOT . "Home/NotFound");
            exit;
        }

        if ($reservation->getState() != ReservationState::ACCEPTED) {
            Session::Set("error", "The reservation is not in a valid state");
            header("location:" . FRONT_ROOT . "Reservation/Reservations");
            exit;
        }

        $fileName = GenerateFile::PersistFile($image, "reservation-", $reservation->getId(), "-payment");
        if ($fileName == null) {
            Session::Set("error", "Invalid image");
            header("location:" . FRONT_ROOT . "Reservation/Reservations");
            exit;
        }
        $reservation->setPayment($fileName);
        $reservation->setState(ReservationState::PAID);
        $this->reservationDAO->Update($reservation);

        header("location:" . FRONT_ROOT . "Reservation/Reservations");
    }

    /**
     * @throws Exception
     */
    public function GenerateReservationBill(int $id)
    {
        LoginMiddleware::VerifyOwner();

        $reservation = $this->reservationDAO->GetById($id);
        if ($reservation == null) {
            header("location:" . FRONT_ROOT . "Home/NotFound");
            exit;
        }

        if ($reservation->getPet()->getOwner()->getId() != Session::Get("owner")->getId()) {
            header("location:" . FRONT_ROOT . "Home/NotFound");
            exit;
        }

        require_once(VIEWS_PATH . "bill-imprint.php");
    }

    /* -------------------------------------------------------------------------- */


    /* Owner Reviews Made */

    /**
     * @throws Exception
     */
    public function ReviewsMade()
    {
        LoginMiddleware::VerifyOwner();
        $owner = Session::Get("owner");
        $reviews = $this->reviewsDAO->GetByOwnerId($owner->getId());
        TempValues::InitValues(["back-page" => FRONT_ROOT]);
        require_once(VIEWS_PATH . "owner-reviews-made.php");
    }

    /* -------------------------------------------------------------------------- */
}
