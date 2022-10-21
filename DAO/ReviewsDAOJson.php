<?php

namespace DAO;

use DAO\PetDAOJson as PetDAO;
use Models\Reviews;

class ReviewsDAOJson implements IReviewsDAO
{
    /**
     * @var Reviews[]
     */
    private array $reviewList = array();
    private string $fileName;
    private PetDAO $petDAO;

    public function __construct()
    {
        $this->fileName = ROOT . "/Data/reviews.json";
        $this->petDAO = new PetDAO();
    }

    function Add(Reviews $review)
    {
        $this->RetrieveData();

        $review->setId($this->GetNextId());

        array_push($this->reviewList, $review);

        $this->SaveData();
    }

    private function RetrieveData()
    {
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
                $review->setPet($this->petDAO->GetById($valuesArray["pet"]));
                array_push($this->reviewList, $review);
            }
        }
    }

    private function GetNextId()
    {
        $this->RetrieveData();
        $lastReviews = end($this->reviewList);
        return $lastReviews === false ? 0 : $lastReviews->getId() + 1;
    }

    private function SaveData()
    {
        $arrayToEncode = array();

        foreach ($this->reviewList as $review) {
            $valuesArray["id"] = $review->getId();
            $valuesArray["comment"] = $review->getComment();
            $valuesArray["rating"] = $review->getRating();
            $valuesArray["date"] = $review->getDate();
            $valuesArray["pet"] = $review->getPet()->getId();

            array_push($arrayToEncode, $valuesArray);
        }

        $jsonContent = json_encode($arrayToEncode, JSON_PRETTY_PRINT);

        file_put_contents($this->fileName, $jsonContent);
    }

    function GetAll(): array
    {
        $this->RetrieveData();

        return $this->reviewList;
    }

    function GetById(int $id): ?Reviews
    {
        $this->RetrieveData();

        $review = array_filter($this->reviewList, fn($review) => $review->getId() == $id);

        return array_shift($review);
    }

    function RemoveById(int $id): bool
    {
        $this->RetrieveData();

        $cleanedArray = array_filter($this->reviewList, fn($review) => $review->getId() != $id);

        $this->SaveData();
        return count($cleanedArray) < count($this->reviewList);
    }

    function Update(Reviews $review): bool
    {
        $this->RetrieveData();
        foreach ($this->reviewList as $reviewOfList) {
            if ($reviewOfList->getId() == $review->getId()) {
                $reviewOfList->setComment($review->getComment());
                $reviewOfList->setRating($review->getRating());
                $reviewOfList->setDate($review->getDate());
                $reviewOfList->setPet($review->getPet());

                $this->SaveData();
                return true;
            }
        }
        return false;
    }

    public function GetByArrIds(array $arrIds): array
    {
        $this->RetrieveData();

        $reviews = array_filter($this->reviewList, fn($review) => in_array($review->getId(), $arrIds));

        return $reviews;
    }
}
