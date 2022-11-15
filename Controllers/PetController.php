<?php

namespace Controllers;

use DAO\PetSQLDAO as PetDAO;
use Exception;
use Models\Pet;
use Utils\GenerateFile;
use Utils\LoginMiddleware;
use Utils\Session;
use Utils\TempValues;

class PetController
{

    private PetDAO $petDAO;

    public function __construct()
    {
        $this->petDAO = new PetDAO();
    }

    /**
     * @throws Exception
     */
    public function ListPets(): void
    {
        LoginMiddleware::VerifyOwner();

        $petList = $this->petDAO->GetPetsByOwnerId(Session::Get("owner")->getId());

        // we need the pets that are for the user
        $petList = array_filter($petList, function ($pet) {
            return $pet->getActive();
        });
        TempValues::InitValues(["back-page" => FRONT_ROOT]);
        require_once(VIEWS_PATH . "pet-list.php");
    }

    public function AddPet($name, $species, $breed, $age, $sex, $image, $vaccine): void
    {
        LoginMiddleware::VerifyOwner();

        $pet = new Pet();
        $pet->setName($name);
        $pet->setSpecies($species);
        $pet->setBreed($breed);
        $pet->setAge($age);
        $pet->setSex($sex);
        $pet->setOwner(Session::Get("owner"));
        $this->petDAO->Add($pet, ['image' => $image, 'vaccine' => $vaccine]);

        header("location:" . FRONT_ROOT . "Pet/ListPets");
    }

    public function AddPetView(): void
    {
        LoginMiddleware::VerifyOwner();
        TempValues::InitValues(["back-page" => FRONT_ROOT . "Pet/ListPets"]);
        require_once(VIEWS_PATH . "pet-add.php");
    }

    /**
     * @throws Exception
     */
    public function EditPet($id, $name, $species, $breed, $age, $sex, $image, $vaccine): void
    {
        LoginMiddleware::VerifyOwner();

        $getPet = $this->petDAO->GetById($id);
        if ($getPet->getOwner()->getId() != Session::Get("owner")->getId()) {
            header("location:" . FRONT_ROOT . "Pet/ListPets");
            exit;
        }

        $pet = new Pet();
        $pet->setId($id);
        $pet->setName($name);
        $pet->setSpecies($species);
        $pet->setBreed($breed);
        $pet->setAge($age);
        $pet->setSex($sex);
        $pet->setOwner(Session::Get("owner"));

        if ($image["size"] > 0) {
            $fileName = GenerateFile::PersistFile($image, "photo-pet-", $id);
            if ($fileName == null) {
                Session::Set("error", "Invalid image");
                header("location:" . FRONT_ROOT . "Pet/ListPets");
                exit;
            }
            $pet->setImage($fileName);
        } else {
            $pet->setImage($getPet->getImage());
        }

        if ($vaccine["size"] > 0) {
            $fileName = GenerateFile::PersistFile($vaccine, "vaccine-pet-", $id);
            if ($fileName == null) {
                Session::Set("error", "Invalid vaccine");
                header("location:" . FRONT_ROOT . "Pet/ListPets");
                exit;
            }
            $pet->setVaccine($fileName);
        } else {
            $pet->setVaccine($getPet->getVaccine());
        }

        $this->petDAO->Update($pet);


        header("location:" . FRONT_ROOT . "Pet/ListPets#id-" . $id);
    }

    /**
     * @throws Exception
     */
    public function Update($id): void
    {
        LoginMiddleware::VerifyOwner();

        $pet = $this->petDAO->GetById($id);
        if (!$pet || $pet->getOwner()->getId() != Session::Get("owner")->getId()) {
            header("location:" . FRONT_ROOT . "Pet/ListPets");
            exit;
        }

        TempValues::InitValues(["back-page" => FRONT_ROOT . "Pet/ListPets"]);
        require_once(VIEWS_PATH . "pet-update.php");
    }

    // Remove Pet actually disables the pet

    /**
     * @throws Exception
     */
    public function RemovePet($id): void
    {
        LoginMiddleware::VerifyOwner();

        $pet = $this->petDAO->GetById($id);

        if ($pet && $pet->getOwner()->getId() == Session::Get("owner")->getId()) {
            // we change active to false instead of deleting the pet

            $this->petDAO->DisablePetById($pet->getId());
        } else {
            Session::Set("error", "You can't remove this pet");
        }
        header("location:" . FRONT_ROOT . "Pet/ListPets");
    }
}