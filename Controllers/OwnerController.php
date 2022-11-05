<?php

namespace Controllers;

use DAO\KeeperDAOJson as KeeperDAO;
use DAO\OwnerDAOJson as OwnerDAO;
use DAO\PetDAOJson as PetDAO;
use DAO\ReservationDAOJson as ReservationDAO;
use DAO\ReviewsDAOJson as ReviewsDAO;
use DateTime;
use Exception;
use Models\Owner as Owner;
use Models\Pet;
use Models\Reservation;
use Models\ReservationState;
use Utils\Session;
use Utils\TempValues;

class OwnerController {
    private OwnerDAO $ownerDAO;
    private PetDAO $petDAO;
    private KeeperDAO $keeperDAO;
    private ReservationDAO $reservationDAO;
    private ReviewsDAO $reviewsDAO;

    public function __construct() {
        $this->ownerDAO = new OwnerDAO();
        $this->petDAO = new PetDAO();
        $this->keeperDAO = new KeeperDAO();
        $this->reservationDAO = new ReservationDAO();
        $this->reviewsDAO = new ReviewsDAO();
    }

    public function Index() {
        $this->VerifyIsLogged();

        $owner = Session::Get("owner");
        require_once(VIEWS_PATH . "owner-home.php");
    }

    private function VerifyIsLogged() {
        if (Session::VerifySession("owner") == false) {
            Session::Set("error", "You must be logged in to access this page.");
            header("location:" . FRONT_ROOT . "Owner/LoginView");
            exit;
        }
    }

    public function SignUp(string $firstname, string $lastname, string $email, string $phone, string $password, string $confirmPassword) {
        // if there's an owner session already, redirect to home
        $this->IfLoggedGoToIndex();

        TempValues::InitValues(["firstname" => $firstname, "lastname" => $lastname, "email" => $email, "phone" => $phone]);

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


        $this->ownerDAO->Add($owner);

        TempValues::UnsetValues();
        Session::Set("owner", $owner);
        header("location:" . FRONT_ROOT . "Owner");
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

    public function Login(string $email, string $password) {
        $owner = $this->ownerDAO->GetByEmail($email);
        if ($owner != null && password_verify($password, $owner->getPassword())) {
            Session::Set("owner", $owner);
            header("Location: " . FRONT_ROOT . "Owner");
            exit;
        }

        TempValues::InitValues(["email" => $email]);
        Session::Set("error", "Invalid credentials");
        header("Location: " . FRONT_ROOT . "Owner/LoginView");
    }

    public function LogOut() {
        Session::Logout();
        header("Location: " . FRONT_ROOT);
        exit;
    }

    public function Pets() {
        $this->VerifyIsLogged();

        $petList = $this->petDAO->GetPetsByOwnerId(Session::Get("owner")->getId());

        TempValues::InitValues(["back-page" => FRONT_ROOT]);
        require_once(VIEWS_PATH . "pet-list.php");
    }

    public function AddPet($name, $species, $breed, $age, $sex, $image, $vaccine) {
        $this->VerifyIsLogged();

        $pet = new Pet();
        $pet->setName($name);
        $pet->setSpecies($species);
        $pet->setBreed($breed);
        $pet->setAge($age);
        $pet->setSex($sex);
        $pet->setVaccine($vaccine);
        $pet->setOwner(Session::Get("owner"));
        $this->petDAO->Add($pet, $image);

        header("location:" . FRONT_ROOT . "Owner/Pets");
    }

    public function AddPetView() {
        $this->VerifyIsLogged();
        TempValues::InitValues(["back-page" => FRONT_ROOT . "Owner/Pets"]);
        require_once(VIEWS_PATH . "pet-add.php");
    }

    public function EditPet($id, $name, $species, $breed, $age, $sex, $image, $vaccine) {
        $this->VerifyIsLogged();

        $getPet = $this->petDAO->GetById($id);
        if ($getPet->getOwner()->getId() != Session::Get("owner")->getId()) {
            header("location:" . FRONT_ROOT . "Owner/Pets");
            exit;
        }

        $pet = new Pet();
        $pet->setId($id);
        $pet->setName($name);
        $pet->setSpecies($species);
        $pet->setBreed($breed);
        $pet->setAge($age);
        $pet->setSex($sex);
        $pet->setVaccine($vaccine);
        $pet->setOwner(Session::Get("owner"));

        if ($image["size"] > 0) {
            try {
                $fileExt = explode(".", $image["name"]);
                $fileType = strtolower(end($fileExt));
                $filePreName = "photo-pet-" . $pet->getId();
                $fileName = $filePreName . "." . $fileType;
                $tempFileName = $image["tmp_name"];
                $filePath = UPLOADS_PATH . basename($fileName);

                $imageSize = getimagesize($tempFileName);

                if ($imageSize !== false) {
                    $files = glob(UPLOADS_PATH . $filePreName . ".*");
                    foreach ($files as $file) {
                        chmod($file, 0755); //Change the file permissions if allowed
                        unlink($file); //remove the file
                    }

                    if (move_uploaded_file($tempFileName, $filePath)) {
                        $pet->setImage($fileName);
                    } else {
                        Session::Set("error", "Error uploading image");
                    }
                } else {
                    Session::Set("error", "File is not an image");
                }
            } catch (Exception $ex) {
                Session::Set("error", $ex->getMessage());
            }
        } else {
            $pet->setImage($getPet->getImage());
        }
        $this->petDAO->Update($pet);


        header("location:" . FRONT_ROOT . "Owner/Pets#id-" . $id);
    }

    public function Update($id) {
        $this->VerifyIsLogged();

        $pet = $this->petDAO->GetById($id);
        if (!$pet || $pet->getOwner()->getId() != Session::Get("owner")->getId()) {
            header("location:" . FRONT_ROOT . "Owner/Pets");
            exit;
        }

        TempValues::InitValues(["back-page" => FRONT_ROOT . "Owner/Pets"]);
        require_once(VIEWS_PATH . "pet-update.php");
    }

    public function RemovePet($id) {
        $this->VerifyIsLogged();

        $pet = $this->petDAO->GetById($id);
        if ($pet && $pet->getOwner()->getId() == Session::Get("owner")->getId()) {
            $this->petDAO->RemoveById($id);
        } else {
            Session::Set("error", "You can't remove this pet");
        }
        header("location:" . FRONT_ROOT . "Owner/Pets");
    }

    public function KeepersListView($since = null, $until = null) {
        $this->VerifyIsLogged();
        $keeperList = $this->keeperDAO->GetAll();
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

        $keepersFromToday = array_filter($keepersFromToday, function ($keeper) {
            $reservations = $this->reservationDAO->GetByKeeperId($keeper->getId());
            $availableDays = $keeper->getAvailableDays($reservations);
            return $availableDays >= 1;
        });

        usort($keepersFromToday, function ($a, $b) {
            $aDate = DateTime::createFromFormat("m-d-Y", $a->getStay()->getUntil());
            $bDate = DateTime::createFromFormat("m-d-Y", $b->getStay()->getUntil());
            return $aDate <=> $bDate;
        });
        TempValues::InitValues(["back-page" => FRONT_ROOT]);
        require_once(VIEWS_PATH . "owner-list-keepers.php");
    }

    public function Reviews($id) {
        $this->VerifyIsLogged();
        $keeper = $this->keeperDAO->GetById($id);
        if ($keeper == null) {
            header("location:" . FRONT_ROOT . "Home/NotFound");
            exit;
        }
        $reviews = $this->reviewsDAO->GetByKeeperId($id);
        TempValues::InitValues(["back-page" => FRONT_ROOT . "Owner/KeepersListView"]);
        require_once(VIEWS_PATH . "keeper-reviews.php");
    }

    public function PlaceReservationView(int $id) {
        $this->VerifyIsLogged();
        $keeper = $this->keeperDAO->GetById($id);
        if ($keeper == null) {
            header("location:" . FRONT_ROOT . "Home/NotFound");
            exit;
        }
        $pets = $this->AvailablePets();
        $reservations = $this->reservationDAO->GetByKeeperId($keeper->getId());

        // filter reservations that are cancelled or rejected by the keeper, so they can be reused
        $reservations = array_filter($reservations, function (Reservation $reservation) {
            return $reservation->getState() != ReservationState::CANCELED && $reservation->getState() != ReservationState::REJECTED;
        });

        $reviews = $this->reviewsDAO->GetByKeeperId($keeper->getId());
        TempValues::InitValues(["back-page" => FRONT_ROOT . "Owner/KeepersListView"]);
        require_once(VIEWS_PATH . "owner-place-reservation.php");

    }

    private function AvailablePets(): null|array {
        $pets = $this->petDAO->GetPetsByOwnerId(Session::Get("owner")->getId());
        $reservationsOfOwner = $this->reservationDAO->GetByOwnerIdAndStates(Session::Get("owner")->getId(), ReservationState::GetDisablingStates());
        if ($pets == null && $reservationsOfOwner == null) {
            return null;
        }

        foreach ($pets as $key => $pet) {
            foreach ($reservationsOfOwner as $reservation) {
                if ($reservation->getPet()->getId() == $pet->getId()) {
                    unset($pets[$key]);
                }
            }
        }
        return $pets;
    }

    public function PlaceReservation(int $petId, int $keeperId, string $since, string $until) {
        $this->VerifyIsLogged();

        $pet = $this->petDAO->GetById($petId);
        $keeper = $this->keeperDAO->GetById($keeperId);

        if (!$pet || !$keeper) {
            Session::Set("error", "Invalid data");
            header("location:" . FRONT_ROOT . "Owner/KeepersListView");
            exit;
        }
        if (!$keeper->isDateAvailable($since, $until)) {
            Session::Set("error", "The keeper is not available in that date");
            header("location:" . FRONT_ROOT . "Owner/KeepersListView");
            exit;
        }

        $reservation = new Reservation();
        $reservation->setPet($pet);
        $reservation->setKeeper($keeper);
        $reservation->setSince($since);
        $reservation->setUntil($until);
        $reservation->setState(ReservationState::PENDING);

        $reservation->setPrice($keeper->calculatePrice($since, $until));

        $this->reservationDAO->Add($reservation);

        Session::Set("success", "Reservation placed successfully");

        header("location:" . FRONT_ROOT . "Owner/KeepersListView");
    }

    public function Reservations(array $states = array()) {
        $this->VerifyIsLogged();
        $reservations = array();
        if (!empty($states)) {
            if($states == ReservationState::GetStates()){
                // avoiding big URL when filtering by all states
                header("location:" . FRONT_ROOT . "Owner/Reservations");
                exit;
            }
            $reservations = $this->reservationDAO->GetByOwnerIdAndStates(Session::Get("owner")->getId(), $states);
        } else {
            $states = ReservationState::GetStates();
            $reservations = $this->reservationDAO->GetByOwnerId(Session::Get("owner")->getId());
        }

        TempValues::InitValues(["back-page" => FRONT_ROOT]);
        require_once(VIEWS_PATH . "owner-reservations.php");
    }

    public function UploadPayment(int $id, array $image) {
        $this->VerifyIsLogged();

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
            header("location:" . FRONT_ROOT . "Owner/Reservations");
            exit;
        }

        try {
            $fileExt = explode(".", $image["name"]);
            $fileType = strtolower(end($fileExt));
            $filePreName = "reservation-" . $reservation->getId() . "-payment";
            $fileName = $filePreName . "." . $fileType;
            $tempFileName = $image["tmp_name"];
            $filePath = UPLOADS_PATH . basename($fileName);

            $imageSize = getimagesize($tempFileName);

            if ($imageSize !== false) {
                $files = glob(UPLOADS_PATH . $filePreName . ".*");
                foreach ($files as $file) {
                    chmod($file, 0755); //Change the file permissions if allowed
                    unlink($file); //remove the file
                }

                if (move_uploaded_file($tempFileName, $filePath)) {
                    $reservation->setPayment($fileName);
                    $reservation->setState(ReservationState::PAID);
                    $this->reservationDAO->Update($reservation);
                } else {
                    Session::Set("error", "Error uploading image");
                }
            } else {
                Session::Set("error", "File is not an image");
            }
        } catch (Exception $ex) {
            Session::Set("error", $ex->getMessage());
        }

        header("location:" . FRONT_ROOT . "Owner/Reservations?states[]=" . ReservationState::ACCEPTED);
    }

    public function SignUpView() {
        $this->IfLoggedGoToIndex();
        TempValues::InitValues(["back-page" => FRONT_ROOT]);
        require_once(VIEWS_PATH . "owner-signup.php");
    }

    public function LoginView() {
        $this->IfLoggedGoToIndex();
        TempValues::InitValues(["back-page" => FRONT_ROOT]);
        require_once(VIEWS_PATH . "owner-login.php");
    }
}
