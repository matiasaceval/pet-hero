<?php

namespace DAO;

use Exception;
use Models\Owner as Owner;
use DAO\IOwnerDAO as IOwnerDao;

class OwnerSQLDAO implements IOwnerDao
{
    private $connection;

    /**
     * Data base Error
     * @throws Exception
     */
    public function Add(Owner $owner)
    {
        $this->connection = Connection::GetInstance();
        $query = "CALL addOwner(?,?,?,?,?)";
        $parameters = $this->SetFromOwner($owner);
        $row = $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure);
        return $row > 0;

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
            $owner = $this->MapOwner($value);
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
        if ($result != null) {
            return $this->MapOwner($result);
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
        $row = $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure);
        return $row > 0;
    }


    /**
     * Data base Error
     * @throws Exception
     */
    public function Update(Owner $owner): bool
    {
        $this->connection = Connection::GetInstance();
        $query = "CALL updateOwner(?,?,?,?,?,?)";
        $parameters = $this->SetFromOwner($owner);
        $parameters["id"] = $owner->getId();
        $row = $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure);
        return $row > 0;
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
        if ($result != null) {
            return $this->MapOwner($result);
        }
        return null;
    }

    private function SetFromOwner(Owner $owner): array
    {
        $value["firstname"] = $owner->getFirstname();
        $value["lastname"] = $owner->getLastname();
        $value["phone"] = $owner->getPhone();
        $value["email"] = $owner->getEmail();
        $value["password"] = $owner->getPassword();
        return $value;
    }

    private function MapOwner($value): Owner
    {
        $owner = new Owner();
        $owner->setId($value["id"]);
        $owner->setFirstname($value["firstname"]);
        $owner->setLastname($value["lastname"]);
        $owner->setEmail($value["email"]);
        $owner->setPhone($value["phone"]);
        $owner->setPassword($value["password"]);
        return $owner;
    }
}