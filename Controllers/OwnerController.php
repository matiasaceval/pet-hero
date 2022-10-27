<?php

namespace Controllers;

use DAO\OwnerDAOJson as OwnerDAO;
use DAO\PetDAOJson as PetDAO;
use DAO\KeeperDAOJson as KeeperDAO;
use Models\Owner as Owner;
use Models\Pet;
use Utils\Session;
use Utils\TempValues;

class OwnerController
{
    private OwnerDAO $ownerDAO;
    private PetDAO $petDAO;
    private KeeperDAO $keeperDAO;

    public function __construct()
    {
        $this->ownerDAO = new OwnerDAO();
        $this->petDAO = new PetDAO();
        $this->keeperDAO = new KeeperDAO();
    }

    public function Index()
    {
        $this->VerifyIsLogged();

        $owner = Session::Get("owner");
        require_once(VIEWS_PATH . "owner-home.php");
    }

    public function SignUp(string $firstname, string $lastname, string $email, string $phone, string $password, string $confirmPassword)
    {
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

    public function Login(string $email, string $password)
    {
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

    public function LogOut()
    {
        Session::Logout();
        header("Location: " . FRONT_ROOT);
        exit;
    }

    public function Pets()
    {
        $this->VerifyIsLogged();

        $petList = $this->petDAO->GetPetsByOwnerId(Session::Get("owner")->getId());

        require_once(VIEWS_PATH . "pet-list.php");
    }

    public function AddPet($name, $species, $breed, $age, $sex, $image, $vaccine)
    {
        $this->VerifyIsLogged();

        $pet = new Pet();
        $pet->setName($name);
        $pet->setSpecies($species);
        $pet->setBreed($breed);
        $pet->setAge($age);
        $pet->setSex($sex);
        $pet->setImage($image["name"]); // TODO: CHANGE WHEN DB IS IMPLEMENTED
        $pet->setVaccine($vaccine);
        $pet->setOwner(Session::Get("owner"));
        $this->petDAO->Add($pet);
        header("location:" . FRONT_ROOT . "Owner/AddPetView");

    }

    public function AddPetView()
    {
        $this->VerifyIsLogged();

        require_once(VIEWS_PATH . "pet-add.php");
    }

    public function EditPet(/* TODO: Parameters */)
    {
        $this->VerifyIsLogged();

        // TODO: Business logic
    }

    public function RemovePet(/* TODO: Parameters */)
    {
        $this->VerifyIsLogged();

        // TODO: Business logic
    }

    public function KeepersListView()
    {
        $this->VerifyIsLogged();
        $keeperList = $this->keeperDAO->GetAll();
        require_once(VIEWS_PATH . "keeper-list.php");
    }

    public function SignUpView()
    {
        $this->IfLoggedGoToIndex();
        require_once(VIEWS_PATH . "owner-signup.php");
    }

    public function LoginView()
    {
        $this->IfLoggedGoToIndex();
        require_once(VIEWS_PATH . "owner-login.php");
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
        if (Session::VerifySession("owner") == false) {
            Session::Set("error", "You must be logged in to access this page.");
            header("location:" . FRONT_ROOT . "Owner/LoginView");
            exit;
        }
    }
}