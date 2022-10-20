<?php

namespace Controllers;

use DAO\OwnerDAOJson as OwnerDAO;
use Models\Owner as Owner;
use Utils\Session;

class OwnerController {
    private OwnerDAO $ownerDAO;

    public function __construct() {
        $this->ownerDAO = new OwnerDAO();
    }

    public function Index() {
        if (Session::Get("owner") == null) {
            Session::Set("error", "You must be logged in to access this page.");
            header("location:" . FRONT_ROOT . "Owner/LogIn");
        }
        // TODO: Owner home view
    }

    public function SignUp(string $firstname, string $lastname, string $email, string $phone, string $password, string $confirmPassword) {
        // if there's an owner session already, redirect to home
        if (Session::Get("owner") != null) {
            header("location:" . FRONT_ROOT . "Owner");
        }
        if ($password != $confirmPassword) {
            Session::Set("error", "Passwords do not match");
            header("location:" . FRONT_ROOT . "Owner/SignUp");
        }

        $owner = new Owner();
        $owner->setFirstname($firstname);
        $owner->setLastname($lastname);
        $owner->setEmail($email);
        $owner->setPhone($phone);
        $owner->setPassword($password);

        if ($this->ownerDAO->GetByEmail($email) != null) {
            Session::Set("error", "Email already exists");
            header("Location: " . FRONT_ROOT . "Owner/SignUp");
        }

        $this->ownerDAO->Add($owner);

        Session::Set("owner", $owner);
        header("location:" . FRONT_ROOT . "Owner");

    }

    public function LogIn(string $email, string $password) {
        $owner = $this->ownerDAO->GetByEmail($email);
        if ($owner != null && $owner->getPassword() == $password) {
            Session::Set("owner", $owner);
            header("Location: " . FRONT_ROOT . "Owner");
        }


        Session::Set("error", "Invalid credentials");
        header("Location: " . FRONT_ROOT . "Home/Index");


    }

    public function LogOut() {
        Session::Logout();
        header("Location: " . FRONT_ROOT . "Home/Index");
    }


    public function Pets() {
        // TODO: List pets FR-3
    }

    public function AddPet(/* TODO: Parameters */) {
        // TODO: Business logic FR-2
    }

    public function EditPet(/* TODO: Parameters */) {
        // TODO: Business logic
    }

    public function RemovePet(/* TODO: Parameters */) {
        // TODO: Business logic
    }

    public function Keepers() {
        // TODO: List keepers FR-6
    }
}