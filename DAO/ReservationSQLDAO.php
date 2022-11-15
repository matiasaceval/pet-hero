<?php

namespace DAO;

use Exception;
use Models\Reservation;
use DAO\IReservationDAOJson as IReservationDAOJson;
use Utils\MapFromSQL;
use Utils\SetterSQLData;

class ReservationSQLDAO implements IReservationDAOJson
{
    private $connection;


    /**
     * @throws Exception
     */
    public function Add(Reservation $reservation)
    {
        $this->connection = Connection::GetInstance();
        $query = 'CALL addReservation(?,?,?,?,?,?,?)';
        $parameters = SetterSQLData::SetFromReservation($reservation);
        return $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure);


    }

    /**
     * @throws Exception
     */
    public function GetAll(): array
    {
        $this->connection = Connection::GetInstance();
        $query = 'CALL getAllReservations()';

        $result = $this->connection->Execute($query, array(), QueryType::StoredProcedure);
        $reservationList = array();
        foreach ($result as $value) {
            $reservation = MapFromSQL::MapFromReservation($value);
            array_push($reservationList, $reservation);
        }
        return $reservationList;
    }

    /**
     * @throws Exception
     */
    public function GetById(int $id): ?Reservation
    {
        $this->connection = Connection::GetInstance();
        $query = 'CALL getReservationById(?)';
        $parameters["id"] = $id;
        $result = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);
        if (count($result) > 0) {
            return MapFromSQL::MapFromReservation($result[0]);
        }
        return null;
    }

    /**
     * @throws Exception
     */
    public function GetByKeeperId(int $id): array
    {
        $this->connection = Connection::GetInstance();
        $query = 'CALL getReservationByKeeperId(?)';
        $parameters["keeperId"] = $id;
        $result = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);
        $reservationList = array();
        foreach ($result as $value) {
            $reservation = MapFromSQL::MapFromReservation($value);
            array_push($reservationList, $reservation);
        }
        return $reservationList;
    }

    /**
     * @throws Exception
     */
    public function GetByState(string $state): array
    {
        $this->connection = Connection::GetInstance();
        $query = 'CALL getReservationByState(?)';
        $parameters["state"] = $state;
        $result = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);
        $reservationList = array();
        foreach ($result as $value) {
            $reservation = MapFromSQL::MapFromReservation($value);
            array_push($reservationList, $reservation);
        }
        return $reservationList;
    }

    /**
     * @throws Exception
     */
    public function GetByOwnerId(int $id): array
    {
        $this->connection = Connection::GetInstance();
        $query = 'CALL getReservationByOwnerId(?)';
        $parameters["ownerId"] = $id;
        $result = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);
        $reservationList = array();
        foreach ($result as $value) {
            $reservation = MapFromSQL::MapFromReservation($value);
            array_push($reservationList, $reservation);
        }
        return $reservationList;
    }

    /**
     * @throws Exception
     */
    public function GetByPetId(int $id): array
    {
        $this->connection = Connection::GetInstance();
        $query = 'CALL getReservationByPetId(?)';
        $parameters["petId"] = $id;
        $result = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);
        $reservationList = array();
        foreach ($result as $value) {
            $reservation = MapFromSQL::MapFromReservation($value);
            array_push($reservationList, $reservation);
        }
        return $reservationList;
    }

    /**
     * @throws Exception
     */
    public function GetByKeeperIdAndState(int $id, string $state): array
    {
        $reservationList = $this->GetByKeeperId($id);
        return array_filter($reservationList, function ($reservation) use ($state) {
            return $reservation->getState() == $state;
        });

    }

    /**
     * @throws Exception
     */
    public function GetByKeeperIdAndStates(int $id, array $state): array
    {
        $reservationList = $this->GetByKeeperId($id);
        return array_filter($reservationList, function ($reservation) use ($state) {
            return in_array($reservation->getState(), $state);
        });
    }


    /**
     * @throws Exception
     */
    public function GetByOwnerIdAndState(int $id, string $state): array
    {
        $reservationList = $this->GetByOwnerId($id);
        return array_filter($reservationList, function ($reservation) use ($state) {
            return $reservation->getState() == $state;
        });
    }

    /**
     * @throws Exception
     */
    public function GetByOwnerIdAndStates(int $id, array $state): array
    {
        $reservationList = $this->GetByOwnerId($id);
        return array_filter($reservationList, function ($reservation) use ($state) {
            return in_array($reservation->getState(), $state);
        });
    }

    /**
     * @throws Exception
     */
    public function Update(Reservation $reservation): bool
    {
        $this->connection = Connection::GetInstance();
        $query = 'CALL updateReservation(?,?,?)';
        $parameters = $this->SetDataUpdate($reservation);
        return $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure) != null;
    }

    /**
     * @throws Exception
     */
    public function RemoveById(int $id): bool
    {
        $this->connection = Connection::GetInstance();
        $query = 'CALL deleteReservation(?)';
        $parameters["id"] = $id;
        return $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure) > 0;
    }

    private function SetDataUpdate(Reservation $reservation): array
    {
        $parameters = array();
        $parameters["id"] = $reservation->getId();
        $parameters["state"] = $reservation->getState();
        if ($reservation->getPayment() != null) {
            $parameters["payment"] = $reservation->getPayment();
        } else {
            $parameters["payment"] = null;
        }
        return $parameters;
    }
}