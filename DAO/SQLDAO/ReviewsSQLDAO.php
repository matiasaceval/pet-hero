<?php

namespace DAO;

use DAO\IReviewsDAO as IReviewsDAO;
use Exception;
use Models\Keeper;
use Models\Owner;
use Models\Pet;
use Models\Reservation;
use Models\Reviews as Reviews;
use Models\Stay;
use Utils\FormatterDate;

class ReviewsSQLDAO implements IReviewsDAO
{
    private $connection;

    private function SetFromValue(Reviews $reviews): array
    {
        $parameters["id"] = $reviews->getId();
        $parameters["comment"] = $reviews->getComment();
        $parameters["rating"] = $reviews->getRating();
        $parameters["reservationId"] = $reviews->getReservation()->getId();
        $parameters["date"] = $reviews->getDate();
        return $parameters;
    }

    public function Add(Reviews $reviews)
    {
        $this->connection = Connection::GetInstance();

    }


    public function GetByOwnerId(int $id): array
    {
        // TODO: Implement GetByOwnerId() method.
    }

    public function GetAll(): array
    {
        // TODO: Implement GetAll() method.
    }

    public function GetById(int $id): ?Reviews
    {
        // TODO: Implement GetById() method.
    }

    public function RemoveById(int $id): bool
    {
        // TODO: Implement RemoveById() method.
    }

    public function Update(Reviews $reviews): bool
    {
        // TODO: Implement Update() method.
    }

    public function GetByKeeperId(int $id): array
    {
        // TODO: Implement GetByKeeperId() method.
    }

    public function GetByReservationId(int $id): Reviews|null
    {
        // TODO: Implement GetByReservationId() method.
    }
}