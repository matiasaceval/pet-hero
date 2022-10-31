<?php

namespace Controllers;

use DAO\KeeperDAOJson as KeeperDAO;
use DAO\OwnerDAOJson as OwnerDAO;
use DAO\PetDAOJson as PetDAO;
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

    public function __construct() {
        $this->ownerDAO = new OwnerDAO();
        $this->petDAO = new PetDAO();
        $this->keeperDAO = new KeeperDAO();
    }

    public function Index() {
        $this->VerifyIsLogged();

        $owner = Session::Get("owner");
        require_once(VIEWS_PATH . "owner-home.php");
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

        require_once(VIEWS_PATH . "pet-add.php");
    }

    public function Update($id) {
        $this->VerifyIsLogged();

        $pet = $this->petDAO->GetById($id);
        if (!$pet || $pet->getOwner()->getId() != Session::Get("owner")->getId()) {
            header("location:" . FRONT_ROOT . "Owner/Pets");
            exit;
        }

        require_once(VIEWS_PATH . "pet-update.php");
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

    public function KeepersListView() {
        $this->VerifyIsLogged();
        $keeperList = $this->keeperDAO->GetAll();
        require_once(VIEWS_PATH . "keeper-list.php");
    }

    public function SignUpView() {
        $this->IfLoggedGoToIndex();
        require_once(VIEWS_PATH . "owner-signup.php");
    }

    public function LoginView() {
        $this->IfLoggedGoToIndex();
        require_once(VIEWS_PATH . "owner-login.php");
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
        if (Session::VerifySession("owner") == false) {
            Session::Set("error", "You must be logged in to access this page.");
            header("location:" . FRONT_ROOT . "Owner/LoginView");
            exit;
        }
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
        if ($keeper->isDateAvailable($since, $until)) {
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

        // TODO: ReservationDAO
        $this->reservationDAO->Add($reservation);

        header("location:" . FRONT_ROOT . "Owner/Resservations");
    }
}
