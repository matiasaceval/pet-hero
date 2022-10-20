<?php

namespace Services;

use DAO\OwnerDAOJson as OwnerDAOJson;
use DAO\PetDAOJson as PetDAOJson;
use Models\Pet as Pet;

/**
 * Class PetService
 * @package Services
 */
class PetService
{
    private $petDAO;
    private $ownerDAO;

    public function __construct()
    {
        $this->petDAO = new PetDAOJson();
        $this->ownerDAO = new OwnerDAOJson();
    }

    public function GetPets(): array
    {
        /**
         * & is used to get the reference of the object, not a copy of it.
         */
        $petList = &$this->petDAO->GetAll();
        /*
         * @var Pet $pet
         */
        foreach ($petList as $pet) {
            $owner = $this->ownerDAO->GetById($this->petDAO->GetOwnerId($pet->getId()));
            $pet->setOwner($owner);
        }

        return $petList;
    }

    public function GetPetByOwnerId(int $ownerId): ?array
    {
       $petList = $this->GetPets();

       $petListByOwnerId = array_filter($petList, fn($pet) => $pet->getOwner()->getId() == $ownerId);

       return $petListByOwnerId;

    }
}