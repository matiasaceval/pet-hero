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
use Utils\MapFromSQL;
use Utils\SetterSQLData;

class ReviewsSQLDAO implements IReviewsDAO
{
    private $connection;


    /**
     * @throws Exception
     */
    public function Add(Reviews $reviews)
    {
        $this->connection = Connection::GetInstance();

        $parameters = SetterSQLData::SetFromReviews($reviews);

        $query = "CALL addReviews(?,?,?,?)";

        return $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure);

    }


    /**
     * @throws Exception
     */
    public function GetByOwnerId(int $id): array
    {
        $this->connection = Connection::GetInstance();

        $parameters["id"] = $id;
        $query = "CALL getReviewByOwnerId(?)";
        $result = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);
        $reviewsList = array();
        foreach ($result as $value) {
            $reviews = MapFromSQL::MapFromReview($value);
            array_push($reviewsList, $reviews);
        }
        return $reviewsList;
    }

    /**
     * @throws Exception
     */
    public function GetAll(): array
    {
        $this->connection = Connection::GetInstance();
        $query = "CALL getAllReviews()";
        $result = $this->connection->Execute($query, array(), QueryType::StoredProcedure);
        $reviewsList = array();
        foreach ($result as $value) {
            $reviews = MapFromSQL::MapFromReview($value);
            array_push($reviewsList, $reviews);
        }
        return $reviewsList;
    }

    /**
     * @throws Exception
     */
    public function GetById(int $id): ?Reviews
    {
        $this->connection = Connection::GetInstance();

        $parameters["id"] = $id;
        $query = "CALL getReviewById(?)";
        $result = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);
        if ($result != null) {
            return MapFromSQL::MapFromReview($result);
        }
        return null;
    }

    /**
     * @throws Exception
     */
    public function GetByReservationId(int $id): Reviews|null
    {
        $this->connection = Connection::GetInstance();

        $parameters["id"] = $id;
        $query = "CALL getReviewByReservationId(?)";
        $result = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);
        if ($result != null) {
            return MapFromSQL::MapFromReview($result);
        }
        return null;
    }

    /**
     * @throws Exception
     */
    public function GetByKeeperId(int $id): array
    {
        $this->connection = Connection::GetInstance();

        $parameters["id"] = $id;

        $query = "CALL getReviewByKeeperId(?)";
        $result = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);
        $reviewsList = array();

        foreach ($result as $value) {
            $reviews = MapFromSQL::MapFromReview($value);
            array_push($reviewsList, $reviews);
        }
        return $reviewsList;

    }

    /**
     * @throws Exception
     */
    public function RemoveById(int $id): bool
    {
        $this->connection = Connection::GetInstance();

        $parameters["id"] = $id;
        $query = "CALL deleteReview(?)";
        return $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure) > 0;
    }

    /**
     * @throws Exception
     */
    public function Update(Reviews $reviews): bool
    {
        $this->connection = Connection::GetInstance();

        $parameters = SetterSQLData::SetFromReviews($reviews);

        $parametersUpdate = $this->SetParametersUpdate($parameters);

        $query = "CALL updateReview(?,?,?)";

        return $this->connection->ExecuteNonQuery($query, $parametersUpdate, QueryType::StoredProcedure) > 0;
    }

    private function SetParametersUpdate(array $parameters): array
    {
        $parametersUpdate = array();
        $parametersUpdate["id"] = $parameters["id"];
        $parametersUpdate["rating"] = $parameters["rating"];
        $parametersUpdate["comment"] = $parameters["comment"];
        return $parametersUpdate;
    }


}