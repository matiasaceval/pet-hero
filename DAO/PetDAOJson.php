<?php

namespace DAO;

use Models\Pet;
use DAO\OwnerDAOJson as OwnerDAO;

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

    private function RetrieveData()
    {
        $this->petList = array();

        if (file_exists($this->fileName)) {
            $jsonContent = file_get_contents($this->fileName);

            $arrayToDecode = ($jsonContent) ? json_decode($jsonContent, true) : array();

            foreach ($arrayToDecode as $valuesArray) {
                $pet = new Pet();
                $pet->setId($valuesArray["id"]);
                $pet->setName($valuesArray["name"]);
                $pet->setAge($valuesArray["age"]);
                $pet->setSpecies($valuesArray["species"]);
                $pet->setBreed($valuesArray["breed"]);
                array_push($this->petList, $pet);
            }
        }

    }

    private function SaveData()
    {
        $arrayToEncode = array();
        /**
         * @var Pet $pet
         */
        foreach ($this->petList as $pet) {
            $valuesArray["id"] = $pet->getId();
            $valuesArray["name"] = $pet->getName();
            $valuesArray["age"] = $pet->getAge();
            $valuesArray["species"] = $pet->getSpecies();
            $valuesArray["breed"] = $pet->getBreed();
            $valuesArray["ownerId"] = $pet->getOwner()->getId();
            array_push($arrayToEncode, $valuesArray);
        }

        $jsonContent = json_encode($arrayToEncode, JSON_PRETTY_PRINT);

        file_put_contents($this->fileName, $jsonContent);
    }

    private function GetNextId()
    {
        $id = 0;

        foreach ($this->petList as $pet) {
            $id = ($pet->getId() > $id) ? $pet->getId() : $id;
        }

        return $id + 1;
    }

    public function Add(Pet $pet)
    {
        $this->RetrieveData();

        $pet->setId($this->GetNextId());

        array_push($this->petList, $pet);

        $this->SaveData();
    }

    public function &GetAll(): array
    {
        $this->RetrieveData();

        return $this->petList;
    }

    public function GetById(int $id): ?Pet
    {
        $this->RetrieveData();

        $owner = array_filter($this->petList, fn($pet) => $pet->getId() == $id);

        return array_shift($owner);
    }

    public function RemoveById(int $id): bool
    {
        $this->RetrieveData();

        $newList = array_filter($this->petList, fn($pet) => $pet->getId() != $id);

        $bool = count($newList) < count($this->petList);

        $this->petList = $newList;

        $this->SaveData();

        return $bool;

    }

    public function Update(Pet $pet): bool
    {
       $this->RetrieveData();

       foreach ($this->petList as $key => $value) {
           if($value->getId() == $pet->getId()){
               $this->petList[$key] = $pet;
               $this->SaveData();
               return true;
           }
       }

    }

    public function GetOwnerId(int $petId): ?int
    {
        $this->petList = array();

        if (file_exists($this->fileName)) {
            $jsonContent = file_get_contents($this->fileName);

            $arrayToDecode = ($jsonContent) ? json_decode($jsonContent, true) : array();

            foreach ($arrayToDecode as $valuesArray) {
                if ($valuesArray["id"] == $petId) {
                    return $valuesArray["ownerId"];
                }
            }
            return null;
        }

    }
}