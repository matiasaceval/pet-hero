<?php

namespace DAO;

use Exception;
use Models\Owner as Owner;
use DAO\IOwnerDAO as IOwnerDao;
use Utils\MapFromSQL;
use Utils\SetterSQLData;

class OwnerSQLDAO implements IOwnerDao
{
    private $connection;

    /**
     * Data base Error
     * @throws Exception
     */
    public function Add(Owner $owner): ?int
    {
        $this->connection = Connection::GetInstance();
        $query = "CALL addOwner(?,?,?,?,?)";
        $parameters = SetterSQLData::SetFromOwner($owner);
        return $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure);
    }

    /**
     * Data base Error
     * @throws Exception
     */
    public function GetAll(): array
    {
        $this->connection = Connection::GetInstance();
        $query = "CALL getAllOwners()";
        $result = $this->connection->Execute($query, array(), QueryType::StoredProcedure);
        $ownersList = array();
        foreach ($result as $value) {
            $owner = MapFromSQL::MapFromOwner($value);
            array_push($ownersList, $owner);
        }
        return $ownersList;
    }

    /**
     * Data base Error
     * @throws Exception
     */
    public function GetById(int $id): ?Owner
    {
        $this->connection = Connection::GetInstance();
        $query = "CALL getOwnerById(?)";
        $parameters["id"] = $id;
        $result = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);
        if (count($result) > 0) {
            return MapFromSQL::MapFromOwner($result[0]);
        }
        return null;
    }

    /**
     * Data base Error
     * @throws Exception
     */
    public function RemoveById(int $id): bool
    {
        $this->connection = Connection::GetInstance();
        $query = "CALL deleteOwner(?)";
        $parameters["id"] = $id;
        return $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure) > 0;
    }


    /**
     * Data base Error
     * @throws Exception
     */
    public function Update(Owner $owner): bool
    {
        $this->connection = Connection::GetInstance();
        $query = "CALL updateOwner(?,?,?,?,?,?)";
        $parameters = SetterSQLData::SetFromOwner($owner);
        $parameters["id"] = $owner->getId();
        return $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure) != null;
    }

    /**
     * Data base Error
     * @throws Exception
     */
    public function GetByEmail(string $email): ?Owner
    {
        $this->connection = Connection::GetInstance();
        $query = "CALL getOwnerByEmail(?)";
        $parameters["email"] = $email;
        $result = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);
        if (count($result) > 0) {
            return MapFromSQL::MapFromOwner($result[0]);
        }
        return null;
    }


}