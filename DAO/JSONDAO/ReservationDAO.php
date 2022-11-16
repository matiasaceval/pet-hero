<?php

namespace DAO\JSONDAO;

use DAO\IReservationDAOJson;
use DAO\JSONDAO\PetDAO as PetDAO;
use DAO\JSONDAO\KeeperDAO as KeeperDAO;
use Models\Reservation;

class ReservationDAO implements IReservationDAOJson {
    private $reservationList = array();
    private $fileName;
    private PetDAO $petDAO;
    private KeeperDAO $keeperDAO;

    public function __construct() {
        $this->petDAO = new PetDAO();
        $this->keeperDAO = new KeeperDAO();
        $this->fileName = ROOT . "/Data/reservations.json";
    }

    public function Add(Reservation $reservation): ?int {
        $this->RetrieveData();
        $id = $this->GetNextId();
        $reservation->setId($id);
        $reservation->setCreatedAt(date("Y-m-d H:i:s"));
        array_push($this->reservationList, $reservation);
        $this->SaveData();

        return $id;
    }

    private function RetrieveData() {
        $this->reservationList = array();
        if (file_exists($this->fileName)) {
            $jsonContent = file_get_contents($this->fileName);
            $arrayToDecode = ($jsonContent) ? json_decode($jsonContent, true) : array();

            foreach ($arrayToDecode as $valuesArray) {
                $reservation = new Reservation();
                $reservation->setId($valuesArray["id"]);
                $reservation->setSince($valuesArray["since"]);
                $reservation->setUntil($valuesArray["until"]);
                $reservation->setState($valuesArray["state"]);
                $reservation->setPet($this->petDAO->GetById($valuesArray["petId"]));
                $reservation->setKeeper($this->keeperDAO->GetById($valuesArray["keeperId"]));
                $reservation->setPrice($valuesArray["price"]);
                $reservation->setCreatedAt($valuesArray["createdAt"]);
                $reservation->setPayment($valuesArray["payment"]);
                array_push($this->reservationList, $reservation);
            }
        }

    }

    public function GetById(int $id): ?Reservation {
        $this->RetrieveData();
        $reservation = array_filter($this->reservationList, fn($reservation) => $reservation->getId() == $id);
        return array_shift($reservation);
    }

    private function GetNextId() {
        $this->RetrieveData();
        $lastReservation = end($this->reservationList);
        return $lastReservation == false ? 0 : $lastReservation->getId() + 1;
    }

    private function SaveData() {
        $arrayToEncode = array();
        foreach ($this->reservationList as $reservation) {
            $valuesArray["id"] = $reservation->getId();
            $valuesArray["since"] = $reservation->getSince();
            $valuesArray["until"] = $reservation->getUntil();
            $valuesArray["state"] = $reservation->getState();
            $valuesArray["petId"] = $reservation->getPet()->getId();
            $valuesArray["keeperId"] = $reservation->getKeeper()->getId();
            $valuesArray["price"] = $reservation->getPrice();
            $valuesArray["createdAt"] = $reservation->getCreatedAt();
            $valuesArray["payment"] = $reservation->getPayment();
            array_push($arrayToEncode, $valuesArray);
        }
        $jsonContent = json_encode($arrayToEncode, JSON_PRETTY_PRINT);
        file_put_contents($this->fileName, $jsonContent);
    }

    public function GetAll(): array {
        $this->RetrieveData();
        return $this->reservationList;
    }

    public function GetByKeeperId(int $id): array {
        $this->RetrieveData();
        return array_filter($this->reservationList, fn($reservation) => $reservation->getKeeper()->getId() == $id);
    }

    public function GetByKeeperIdAndState(int $id, string $state): array {
        $this->RetrieveData();
        return array_filter($this->reservationList, fn($reservation) => $reservation->getKeeper()->getId() == $id && $reservation->getState() == $state);
    }

    public function GetByKeeperIdAndStates(int $id, array $state): array {
        $this->RetrieveData();
        return array_filter($this->reservationList, fn($reservation) => $reservation->getKeeper()->getId() == $id && in_array($reservation->getState(), $state));
    }

    public function GetByPetId(int $id): array {
        $this->RetrieveData();
        return array_filter($this->reservationList, fn($reservation) => $reservation->getPet()->getId() == $id);
    }

    public function GetByOwnerId(int $id): array {
        $this->RetrieveData();
        return array_filter($this->reservationList, fn($reservation) => $reservation->getPet()->getOwner()->getId() == $id);
    }

    public function GetByOwnerIdAndState(int $id, string $state): array {
        $this->RetrieveData();
        return array_filter($this->reservationList, fn($reservation) => $reservation->getPet()->getOwner()->getId() == $id && $reservation->getState() == $state);
    }

    public function GetByOwnerIdAndStates(int $id, array $state): array {
        $this->RetrieveData();
        return array_filter($this->reservationList, fn($reservation) => $reservation->getPet()->getOwner()->getId() == $id && in_array($reservation->getState(), $state));
    }

    public function GetByState(string $state): array {
        $this->RetrieveData();
        return array_filter($this->reservationList, fn($reservation) => $reservation->getState() == $state);
    }


    public function Update(Reservation $reservation): bool {
        $this->RetrieveData();
        foreach ($this->reservationList as $key => $value) {
            if ($value->getId() == $reservation->getId()) {
                $this->reservationList[$key] = $reservation;
                $this->SaveData();
                return true;
            }
        }
        return false;
    }

    public function RemoveById(int $id): bool {
        $this->RetrieveData();

        $cleanedArray = array_filter($this->reservationList, fn($reservation) => $reservation->getId() != $id);

        $isRemoved = count($this->reservationList) > count($cleanedArray);

        $this->reservationList = $cleanedArray;

        $this->SaveData();

        return $isRemoved;
    }
}