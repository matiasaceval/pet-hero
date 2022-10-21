<?php

namespace DAO;

use DAO\StayDAOJson as StayDAO;
use DAO\ReviewsDAOJson as ReviewsDAO;

use Models\Keeper;
use Models\Reviews as Reviews;

class KeeperDAOJson implements IKeeperDAO
{
    /**
     * @var Keeper[]
     */
    private array $keeperList = array();
    private string $fileName;
    private StayDAO $stayDAO;
    private ReviewsDAO $reviewsDAO;

    public function __construct()
    {
        $this->fileName = ROOT . "/Data/keepers.json";
        $this->stayDAO = new StayDAO();
        $this->reviewsDAO = new ReviewsDAO();
    }

    function Add(Keeper $keeper)
    {
        $this->RetrieveData();

        array_push($this->keeperList, $keeper);

        $this->SaveData();
    }

    private function RetrieveData()
    {
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
                $keeper->setReviews($this->reviewsDAO->GetByArrIds($valuesArray["reviews"]));
                array_push($this->keeperList, $keeper);
            }
        }
    }

    public function GetNextId()
    {
        $this->RetrieveData();
        $lastKeeper = end($this->keeperList);
        return $lastKeeper === false ? 0 : $lastKeeper->getId() + 1;
    }

    private function SaveData()
    {
        $arrayToEncode = array();

        foreach ($this->keeperList as $keeper) {
            $valuesArray["id"] = $keeper->getId();
            $valuesArray["firstname"] = $keeper->getFirstname();
            $valuesArray["lastname"] = $keeper->getLastname();
            $valuesArray["email"] = $keeper->getEmail();
            $valuesArray["password"] = $keeper->getPassword();
            $valuesArray["phone"] = $keeper->getPhone();
            $valuesArray["fee"] = $keeper->getFee();
            $valuesArray["reviews"] = $this->ReviewsAsId($keeper->getReviews());
            $valuesArray["stay"] = $keeper->getStay()->getId();

            array_push($arrayToEncode, $valuesArray);
        }

        $jsonContent = json_encode($arrayToEncode, JSON_PRETTY_PRINT);

        file_put_contents($this->fileName, $jsonContent);
    }

    function &GetAll(): array
    {
        $this->RetrieveData();

        return $this->keeperList;
    }

    function GetById(int $id): ?Keeper
    {
        $this->RetrieveData();

        $keeper = array_filter($this->keeperList, fn($keeper) => $keeper->getId() == $id);

        return array_shift($keeper);
    }

    function RemoveById(int $id): bool
    {
        $this->RetrieveData();

        $cleanedArray = array_filter($this->keeperList, fn($keeper) => $keeper->getId() != $id);

        $this->SaveData();
        return count($cleanedArray) < count($this->keeperList);
    }

    function Update(Keeper $keeper): bool
    {
        $this->RetrieveData();
        foreach ($this->keeperList as $keeperOfList) {
            if ($keeperOfList->getId() == $keeper->getId()) {
                $keeperOfList->setFirstname($keeper->getFirstname());
                $keeperOfList->setLastname($keeper->getLastname());
                $keeperOfList->setEmail($keeper->getEmail());
                $keeperOfList->setPassword($keeper->getPassword());
                $keeperOfList->setPhone($keeper->getPhone());

                $this->SaveData();
                return true;
            }
        }
        return false;
    }

    public function GetByEmail(string $email): ?Keeper
    {

        $this->RetrieveData();

        $keeper = array_filter($this->keeperList, fn($keeper) => $keeper->getEmail() == $email);

        return array_shift($keeper);
    }

    private function ReviewsAsId(array $reviews)
    {
        return array_map(function (Reviews $review) {
            return $review->getId();
        }, $reviews);
    }
}
