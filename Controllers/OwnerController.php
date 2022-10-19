<?php

namespace Controllers;

use DAO\OwnerDAOJson as OwnerDAO;
use Models\Owner as Owner;

class OwnerController
{
    private OwnerDAO $ownerDAO;

    public function __construct()
    {
        $this->ownerDAO = new OwnerDAO();
    }

    public function SignUp(string $firstname, string $lastname, string $email, string $phone, string $password, string $confirmPassword)
    {
        if ($password != $confirmPassword) {
            // TODO: Locate with error message
        }

        $owner = new Owner();
        $owner->setFirstname($firstname);
        $owner->setLastname($lastname);
        $owner->setEmail($email);
        $owner->setPhone($phone);
        $owner->setPassword($password);

        $this->ownerDAO->Add($owner);

        // TODO: Locate to home and store owner in session

    }

    public function LogIn(string $email, string $password)
    {
        // TODO: Implement GetByEmail method in OwnerDAO and Interface
        $owner = $this->ownerDAO->GetByEmail($email);
        if ($owner != null && $owner->getPassword() == $password) {
            // TODO: Locate to home and store owner in session
        }

        // TODO: Locate with error message

    }

    public function LogOut()
    {
        // TODO: Remove owner from session and locate to home
    }


    public function Pets()
    {
        // TODO: List pets FR-3
    }

    public function AddPet(/* TODO: Parameters */)
    {
        // TODO: Business logic FR-2
    }

    public function EditPet(/* TODO: Parameters */)
    {
        // TODO: Business logic
    }

    public function RemovePet(/* TODO: Parameters */)
    {
        // TODO: Business logic
    }

    public function Keepers()
    {
        // TODO: List keepers FR-6
    }
}