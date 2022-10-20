<?php

namespace DAO;

use Models\Pet;

class PetDAOJson implements IPetDAO
{
    /**
     * @var Pet[]
     */

    private array $petList = array();
    private string $fileName;

    public function __construct()
    {
        $this->fileName = ROOT . "/Data/pets.json";
    }

    private function RetrieveData(){}

    public function Add(Pet $pet)
    {
        // TODO: Implement Add() method.
    }

    public function GetAll(): array
    {
        // TODO: Implement GetAll() method.
    }

    public function GetById(int $id): ?Pet
    {
        // TODO: Implement GetById() method.
    }

    public function RemoveById(int $id): bool
    {
        // TODO: Implement RemoveById() method.
    }

    public function Update(Pet $pet): bool
    {
        // TODO: Implement Update() method.
    }

    public function GetByOwnerId(int $ownerId): array
    {
        // TODO: Implement GetByOwnerId() method.
    }
}