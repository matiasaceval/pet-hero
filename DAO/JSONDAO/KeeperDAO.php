<?php

namespace DAO\JSONDAO;

use DAO\IKeeperDAO;
use DAO\JSONDAO\StayDAO as StayDAO;
use Models\Keeper;
use Models\Stay;

class KeeperDAO implements IKeeperDAO {
    /**
     * @var Keeper[]
     */
    private array $keeperList = array();
    private string $fileName;
    private StayDAO $stayDAO;

    public function __construct() {
        $this->fileName = ROOT . "/Data/keepers.json";
        $this->stayDAO = new StayDAO();
    }

    function Add(Keeper $keeper): ?int {
        $this->RetrieveData();

        $id = $this->GetNextId();
        $keeper->setId($id);

        $keeper->getStay()->setId($id);

        $this->stayDAO->Add($keeper->getStay());

        array_push($this->keeperList, $keeper);

        $this->SaveData();

        return $id;
    }

    private function RetrieveData() {
        $this->keeperList = array();

        if (file_exists($this->fileName)) {
            $jsonContent = file_get_contents($this->fileName);

            $arrayToDecode = ($jsonContent) ? json_decode($jsonContent, true) : array();

            foreach ($arrayToDecode as $valuesArray) {
                $keeper = new Keeper();
                $keeper->setId($valuesArray["id"]);
                $keeper->setFirstname($valuesArray["firstname"]);
                $keeper->setLastname($valuesArray["lastname"]);
                $keeper->setEmail($valuesArray["email"]);
                $keeper->setPassword($valuesArray["password"]);
                $keeper->setPhone($valuesArray["phone"]);
                $keeper->setFee($valuesArray["fee"]);
                $keeper->setStay($this->stayDAO->GetById($valuesArray["id"])); // because StayId is the same as KeeperId
                array_push($this->keeperList, $keeper);
            }
        }
    }

    function GetById(int $id): ?Keeper {
        $this->RetrieveData();

        $keeper = array_filter($this->keeperList, fn($keeper) => $keeper->getId() == $id);

        return array_shift($keeper);
    }

    public function GetNextId() {
        $this->RetrieveData();
        $lastKeeper = end($this->keeperList);
        return $lastKeeper === false ? 1 : $lastKeeper->getId() + 1;
    }

    private function SaveData() {
        $arrayToEncode = array();

        foreach ($this->keeperList as $keeper) {
            $valuesArray["id"] = $keeper->getId();
            $valuesArray["firstname"] = $keeper->getFirstname();
            $valuesArray["lastname"] = $keeper->getLastname();
            $valuesArray["email"] = $keeper->getEmail();
            $valuesArray["password"] = $keeper->getPassword();
            $valuesArray["phone"] = $keeper->getPhone();
            $valuesArray["fee"] = $keeper->getFee();
            $valuesArray["stay"] = $keeper->getStay()->getId();

            array_push($arrayToEncode, $valuesArray);
        }

        $jsonContent = json_encode($arrayToEncode, JSON_PRETTY_PRINT);

        file_put_contents($this->fileName, $jsonContent);
    }


    function GetAll(): array {
        $this->RetrieveData();

        return $this->keeperList;
    }

    function RemoveById(int $id): bool {
        $this->RetrieveData();

        $cleanedArray = array_filter($this->keeperList, fn($keeper) => $keeper->getId() != $id);

        $this->keeperList = $cleanedArray;

        $this->SaveData();
        return count($cleanedArray) < count($this->keeperList);
    }

    function Update(Keeper $keeper): bool {
        $this->RetrieveData();
        foreach ($this->keeperList as $key => $keeperOfList) {
            if ($keeperOfList->getId() == $keeper->getId()) {
                $this->keeperList[$key] = $keeper;
                $this->stayDAO->Update($keeper->getStay());
                $this->SaveData();
                return true;
            }
        }
        return false;
    }

    public function GetByEmail(string $email): ?Keeper {

        $this->RetrieveData();

        $keeper = array_filter($this->keeperList, fn($keeper) => $keeper->getEmail() == $email);

        return array_shift($keeper);
    }
}
