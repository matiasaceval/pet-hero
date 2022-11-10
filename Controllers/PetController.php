<?php

namespace Controllers;

use DAO\PetDAOJson as PetDAO;
use Exception;
use Models\Pet;
use Utils\GenerateImage;
use Utils\LoginMiddleware;
use Utils\Session;
use Utils\TempValues;

class PetController {

    private PetDAO $petDAO;

    public function __construct() {
        $this->petDAO = new PetDAO();
    }

    public function ListPets() {
        LoginMiddleware::VerifyOwner();

        $petList = $this->petDAO->GetPetsByOwnerId(Session::Get("owner")->getId());

        TempValues::InitValues(["back-page" => FRONT_ROOT]);
        require_once(VIEWS_PATH . "pet-list.php");
    }

    public function AddPet($name, $species, $breed, $age, $sex, $image, $vaccine) {
        LoginMiddleware::VerifyOwner();

        $pet = new Pet();
        $pet->setName($name);
        $pet->setSpecies($species);
        $pet->setBreed($breed);
        $pet->setAge($age);
        $pet->setSex($sex);
        $pet->setVaccine($vaccine);
        $pet->setOwner(Session::Get("owner"));
        $this->petDAO->Add($pet, $image);

        header("location:" . FRONT_ROOT . "Pet/ListPets");
    }

    public function AddPetView() {
        LoginMiddleware::VerifyOwner();
        TempValues::InitValues(["back-page" => FRONT_ROOT . "Pet/ListPets"]);
        require_once(VIEWS_PATH . "pet-add.php");
    }

    public function EditPet($id, $name, $species, $breed, $age, $sex, $image, $vaccine) {
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
        $pet->setVaccine($vaccine);
        $pet->setOwner(Session::Get("owner"));

        if ($image["size"] > 0) {
            $fileName = GenerateImage::PersistImage($image, "photo-pet-", $id);
            if ($fileName == null) {
                Session::Set("error", "Invalid image");
                header("location:" . FRONT_ROOT . "Pet/ListPets");
                exit;
            }
            $pet->setImage($fileName);
        } else {
            $pet->setImage($getPet->getImage());
        }
        $this->petDAO->Update($pet);


        header("location:" . FRONT_ROOT . "Pet/ListPets#id-" . $id);
    }

    public function Update($id) {
        LoginMiddleware::VerifyOwner();

        $pet = $this->petDAO->GetById($id);
        if (!$pet || $pet->getOwner()->getId() != Session::Get("owner")->getId()) {
            header("location:" . FRONT_ROOT . "Pet/ListPets");
            exit;
        }

        TempValues::InitValues(["back-page" => FRONT_ROOT . "Pet/ListPets"]);
        require_once(VIEWS_PATH . "pet-update.php");
    }

    public function RemovePet($id) {
        LoginMiddleware::VerifyOwner();

        $pet = $this->petDAO->GetById($id);
        if ($pet && $pet->getOwner()->getId() == Session::Get("owner")->getId()) {
            $this->petDAO->RemoveById($id);
        } else {
            Session::Set("error", "You can't remove this pet");
        }
        header("location:" . FRONT_ROOT . "Pet/ListPets");
    }
}