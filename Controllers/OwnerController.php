<?php

namespace Controllers;

use DAO\OwnerDAOJson as OwnerDAO;
use DAO\PetDAOJson as PetDAO;
use Models\Owner as Owner;
use Models\Pet;
use Utils\Session;

class OwnerController {
    private OwnerDAO $ownerDAO;
    private PetDAO $petDAO;

    public function __construct() {
        $this->ownerDAO = new OwnerDAO();
        $this->petDAO = new PetDAO();
    }

    public function Index() {
        if (Session::VerifySession("owner") == false) {
            Session::Set("error", "You must be logged in to access this page.");
            header("location:" . FRONT_ROOT . "Owner/LoginView");
        }
        // TODO: Owner home view
    }

    public function SignUp(string $firstname, string $lastname, string $email, string $phone, string $password, string $confirmPassword) {
        // if there's an owner session already, redirect to home
        $this->VerifyOwner();

        if ($password != $confirmPassword) {
            Session::Set("error", "Passwords do not match");
            header("location:" . FRONT_ROOT . "Owner/SignUpView");
        }

        $owner = new Owner();
        $owner->setFirstname($firstname);
        $owner->setLastname($lastname);
        $owner->setEmail($email);
        $owner->setPhone($phone);
        $owner->setPassword($password);

        if ($this->ownerDAO->GetByEmail($email) != null) {
            Session::Set("error", "Email already exists");
            header("Location: " . FRONT_ROOT . "Owner/SignUpView");
        }

        $this->ownerDAO->Add($owner);

        Session::Set("owner", $owner);
        header("location:" . FRONT_ROOT . "Owner");
    }

    public function Login(string $email, string $password) {
        $owner = $this->ownerDAO->GetByEmail($email);
        if ($owner != null && $owner->getPassword() == $password) {
            Session::Set("owner", $owner);
            header("Location: " . FRONT_ROOT . "Owner");
        }

        Session::Set("error", "Invalid credentials");
        header("Location: " . FRONT_ROOT . "Owner/LoginView");
    }

    public function LogOut() {
        Session::Logout();
        header("Location: " . FRONT_ROOT . "Home/Index");
    }

    public function Pets() {
        if (Session::VerifySession("owner")) {
            $petList = $this->petDAO->GetPetsByOwnerId(Session::Get("owner")->getId());
            require_once(VIEWS_PATH . "list-pet.php");
        } else {
            Session::Set("error", "You must be logged in to access this page.");
            header("location:" . FRONT_ROOT . "Owner/LoginView");
        }
    }

    public function AddPet($name, $species, $breed, $age, $gender) {

        $this->VerifyOwner();

        $pet = new Pet();
        $pet->setName($name);
        $pet->setSpecies($species);
        $pet->setBreed($breed);
        $pet->setAge($age);
        $pet->setGender($gender);
        $pet->setOwner(Session::Get("owner"));
        $this->petDAO->Add($pet);
        header("location:" . FRONT_ROOT . "Owner/AddPetView");

    }

    public function AddPetView() {
        if (Session::VerifySession("owner")) {
            require_once(VIEWS_PATH . "add-pet.php");
        } else {
            Session::Set("error", "You must be logged in to access this page.");
            header("location:" . FRONT_ROOT . "Owner/LoginView");
        }
    }

    public function EditPet(/* TODO: Parameters */) {
        if (Session::VerifySession("owner")) {
            // TODO: Business logic
        } else {
            Session::Set("error", "You must be logged in to access this page.");
            header("location:" . FRONT_ROOT . "Owner/LoginView");
        }
    }

    public function RemovePet(/* TODO: Parameters */) {
        if (Session::VerifySession("owner")) {
            // TODO: Business logic
        } else {
            Session::Set("error", "You must be logged in to access this page.");
            header("location:" . FRONT_ROOT . "Owner/LoginView");
        }
    }

    public function Keepers() {
        if (Session::VerifySession("owner")) {
            // TODO: List keepers FR-6
        } else {
            Session::Set("error", "You must be logged in to access this page.");
            header("location:" . FRONT_ROOT . "Owner/Login");

        }
    }

    public function SignUpView() {
        $this->VerifyOwner();
        require_once(VIEWS_PATH . "owner-signup.php");
    }

    public function LoginView() {
        $this->VerifyOwner();
        require_once(VIEWS_PATH . "owner-login.php");
    }

    private function VerifyOwner() {
        if (Session::VerifySession("owner")) {
            header("Location: " . FRONT_ROOT . "Owner");
        }
    }
}