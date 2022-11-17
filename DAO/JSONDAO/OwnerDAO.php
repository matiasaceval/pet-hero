<?php

namespace DAO\JSONDAO;

use DAO\IOwnerDAO;
use Models\Owner;

class OwnerDAO implements IOwnerDAO
{
    /**
     * @var Owner[]
     */
    private array $ownerList = array();
    private string $fileName;

    public function __construct()
    {
        $this->fileName = ROOT . "/Data/owners.json";
    }

    function Add(Owner $owner): int
    {
        $this->RetrieveData();

        $id = $this->GetNextId();

        $owner->setId($id);

        array_push($this->ownerList, $owner);

        $this->SaveData();

        return $id;
    }

    private function RetrieveData(): void
    {
        $this->ownerList = array();

        if (file_exists($this->fileName)) {
            $jsonContent = file_get_contents($this->fileName);

            $arrayToDecode = ($jsonContent) ? json_decode($jsonContent, true) : array();

            foreach ($arrayToDecode as $valuesArray) {
                $owner = new Owner();
                $owner->setId($valuesArray["id"]);
                $owner->setFirstname($valuesArray["firstname"]);
                $owner->setLastname($valuesArray["lastname"]);
                $owner->setEmail($valuesArray["email"]);
                $owner->setPassword($valuesArray["password"]);
                $owner->setPhone($valuesArray["phone"]);

                array_push($this->ownerList, $owner);
            }
        }
    }

    private function GetNextId(): int
    {
        $this->RetrieveData();
        $lastElement = end($this->ownerList);
        return $lastElement == false ? 0 : $lastElement->getId() + 1;
    }

    private function SaveData(): void
    {
        $arrayToEncode = array();

        foreach ($this->ownerList as $owner) {
            $valuesArray["id"] = $owner->getId();
            $valuesArray["firstname"] = $owner->getFirstname();
            $valuesArray["lastname"] = $owner->getLastname();
            $valuesArray["email"] = $owner->getEmail();
            $valuesArray["password"] = $owner->getPassword();
            $valuesArray["phone"] = $owner->getPhone();

            array_push($arrayToEncode, $valuesArray);
        }

        $jsonContent = json_encode($arrayToEncode, JSON_PRETTY_PRINT);

        file_put_contents($this->fileName, $jsonContent);
    }

    function GetAll(): array
    {
        $this->RetrieveData();

        return $this->ownerList;
    }

    function GetById(int $id): ?Owner
    {
        $this->RetrieveData();

        $owner = array_filter($this->ownerList, fn($owner) => $owner->getId() == $id);

        return array_shift($owner);
    }

    function RemoveById(int $id): bool
    {
        $this->RetrieveData();

        $cleanedArray = array_filter($this->ownerList, fn($owner) => $owner->getId() != $id);

        $this->ownerList = $cleanedArray;

        $this->SaveData();
        return count($cleanedArray) < count($this->ownerList);
    }

    function Update(Owner $owner): bool
    {
        $this->RetrieveData();
        foreach ($this->ownerList as $key => $ownerOfList) {
            if ($ownerOfList->getId() == $owner->getId()) {
                $this->ownerList[$key] = $owner;
                $this->SaveData();
                return true;
            }
        }
        return false;
    }

    public function GetByEmail(string $email): ?Owner
    {

        $this->RetrieveData();

        $owner = array_filter($this->ownerList, fn($owner) => $owner->getEmail() == $email);

        return array_shift($owner);
    }
}
