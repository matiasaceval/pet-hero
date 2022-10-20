<?php

namespace Services;
use DAO\OwnerDAOJson as OwnerDAOJson;
use DAO\PetDAOJson as PetDAOJson;
use Models\Owner as Owner;
class OwnerService
{
    private $ownerDAO;
    private $petDAO;

    public function __construct()
    {
        $this->ownerDAO = new OwnerDAOJson();
        $this->petDAO = new PetDAOJson();
    }

    public function GetOwnerByPetId(int $petId): ?Owner
    {
        return $this->ownerDAO->GetById($this->petDAO->GetOwnerId($petId));
    }


}