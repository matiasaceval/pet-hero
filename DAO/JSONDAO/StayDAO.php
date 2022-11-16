<?php

namespace DAO\JSONDAO;

use DAO\IStayDAO;
use Models\Stay;

class StayDAO implements IStayDAO {
    /**
     * @var Stay[]
     */
    private array $stayList = array();
    private string $fileName;

    public function __construct() {
        $this->fileName = ROOT . "/Data/stays.json";
    }

    function Add(Stay $stay): ?int {
        $this->RetrieveData();

        array_push($this->stayList, $stay);

        $this->SaveData();

        return $stay->getId();
    }

    private function RetrieveData() {
        $this->stayList = array();

        if (file_exists($this->fileName)) {
            $jsonContent = file_get_contents($this->fileName);

            $arrayToDecode = ($jsonContent) ? json_decode($jsonContent, true) : array();

            foreach ($arrayToDecode as $valuesArray) {
                $stay = new Stay();
                $stay->setId($valuesArray["id"]);
                $stay->setSince($valuesArray["since"]);
                $stay->setUntil($valuesArray["until"]);

                array_push($this->stayList, $stay);
            }
        }
    }

    private function SaveData() {
        $arrayToEncode = array();

        foreach ($this->stayList as $stay) {
            $valuesArray["id"] = $stay->getId();
            $valuesArray["since"] = $stay->getSince();
            $valuesArray["until"] = $stay->getUntil();

            array_push($arrayToEncode, $valuesArray);
        }

        $jsonContent = json_encode($arrayToEncode, JSON_PRETTY_PRINT);

        file_put_contents($this->fileName, $jsonContent);
    }

    function GetAll(): array {
        $this->RetrieveData();

        return $this->stayList;
    }

    function GetById(int $id): ?Stay {
        $this->RetrieveData();

        $stay = array_filter($this->stayList, fn($stay) => $stay->getId() == $id);

        return array_shift($stay);
    }

    function RemoveById(int $id): bool {
        $this->RetrieveData();

        $cleanedArray = array_filter($this->stayList, fn($stay) => $stay->getId() != $id);

        $this->stayList = $cleanedArray;

        $this->SaveData();
        return count($cleanedArray) < count($this->stayList);
    }

    function Update(Stay $stay): bool {
        $this->RetrieveData();
        foreach ($this->stayList as $key => $stayOfList) {
            if ($stayOfList->getId() == $stay->getId()) {
                $this->stayList[$key] = $stay;
                $this->SaveData();
                return true;
            }
        }
        return false;
    }

    private function GetNextId() {
        $this->RetrieveData();
        $lastStay = end($this->stayList);
        return $lastStay === false ? 1 : $lastStay->getId() + 1;
    }
}
