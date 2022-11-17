<?php

namespace DAO\JSONDAO;

use DAO\IReviewsDAO;
use Models\Reviews;
use DAO\JSONDAO\ReservationDAO as ReservationDAO;
class ReviewsDAO implements IReviewsDAO {
    /**
     * @var Reviews[]
     */
    private array $reviewList = array();
    private string $fileName;
    private ReservationDAO $reservationDAO;

    public function __construct() {
        $this->fileName = ROOT . "/Data/reviews.json";
        $this->reservationDAO = new ReservationDAO();
    }

    function Add(Reviews $review): ?int {
        $this->RetrieveData();

        $id = $this->GetNextId();

        $review->setId($id);

        array_push($this->reviewList, $review);

        $this->SaveData();

        return $id;
    }

    private function RetrieveData() : void {
        $this->reviewList = array();

        if (file_exists($this->fileName)) {
            $jsonContent = file_get_contents($this->fileName);

            $arrayToDecode = ($jsonContent) ? json_decode($jsonContent, true) : array();

            foreach ($arrayToDecode as $valuesArray) {
                $review = new Reviews();
                $review->setId($valuesArray["id"]);
                $review->setComment($valuesArray["comment"]);
                $review->setRating($valuesArray["rating"]);
                $review->setDate($valuesArray["date"]);
                $review->setReservation($this->reservationDAO->GetById($valuesArray["reservation"]));
                array_push($this->reviewList, $review);
            }
        }
    }

    function GetById(int $id): ?Reviews {
        $this->RetrieveData();

        $review = array_filter($this->reviewList, fn($review) => $review->getId() == $id);

        return array_shift($review);
    }

    private function GetNextId(): int
    {
        $this->RetrieveData();
        $lastReviews = end($this->reviewList);
        return $lastReviews === false ? 1 : $lastReviews->getId() + 1;
    }

    private function SaveData() : void {
        $arrayToEncode = array();

        foreach ($this->reviewList as $review) {
            $valuesArray["id"] = $review->getId();
            $valuesArray["comment"] = $review->getComment();
            $valuesArray["rating"] = $review->getRating();
            $valuesArray["date"] = $review->getDate();
            $valuesArray["reservation"] = $review->getReservation()->getId();

            array_push($arrayToEncode, $valuesArray);
        }

        $jsonContent = json_encode($arrayToEncode, JSON_PRETTY_PRINT);

        file_put_contents($this->fileName, $jsonContent);
    }

    function GetAll(): array {
        $this->RetrieveData();

        return $this->reviewList;
    }

    function RemoveById(int $id): bool {
        $this->RetrieveData();

        $cleanedArray = array_filter($this->reviewList, fn($review) => $review->getId() != $id);

        $this->SaveData();
        return count($cleanedArray) < count($this->reviewList);
    }

    function Update(Reviews $review): bool {
        $this->RetrieveData();
        foreach ($this->reviewList as $key => $reviewOfList) {
            if ($reviewOfList->getId() == $review->getId()) {
                $this->reviewList[$key] = $review;
                $this->SaveData();
                return true;
            }
        }
        return false;
    }

    public function GetByKeeperId(int $id): array {
        $this->RetrieveData();

        return array_filter($this->reviewList, fn($review) => $review->getReservation()->getKeeper()->getId() == $id);
    }

    public function GetByOwnerId(int $id): array {
        $this->RetrieveData();

        return array_filter($this->reviewList, fn($review) => $review->getReservation()->getPet()->getOwner()->getId() == $id);
    }

    public function GetByReservationId(int $id): Reviews|null {
        $this->RetrieveData();

        $review = array_filter($this->reviewList, fn($review) => $review->getReservation()->getId() == $id);

        return array_shift($review);
    }
}
