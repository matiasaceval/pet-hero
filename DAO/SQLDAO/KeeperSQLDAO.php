<?php

namespace DAO;

use DAO\IKeeperDAO as IKeeperDAO;
use Models\Keeper as Keeper;
use Models\Stay;


class KeeperSQLDAO implements IKeeperDAO
{
    private $connection;

    public function Add(Keeper $keeper)
    {
        try {
            $this->connection = Connection::GetInstance();

            $parameters = $this->setFromValue($keeper);

            $query = "CALL addKepper(?,?,?,?,?,?,?,?)";
            $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function GetAll(): array
    {
        try {
            $this->connection = Connection::GetInstance();
            $query = "CALL getAllKeepers()";
            $result = $this->connection->Execute($query, array(), QueryType::StoredProcedure);
            $keepersList = array();
            foreach ($result as $value) {
                $keeper = $this->mapFromValue($value);
                array_push($keepersList, $keeper);
            }
            return $keepersList;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function GetById(int $id): ?Keeper
    {
        try {
            $this->connection = Connection::GetInstance();

            $parameters["id"] = $id;

            $query = "CALL getKeeperById(?)";
            $result = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);
            if ($result != null) {
                return $this->mapFromValue($result);
            }
            return null;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function RemoveById(int $id): bool
    {
        try {
            $this->connection = Connection::GetInstance();

            $parameters["id"] = $id;

            $query = "CALL deleteKeeper(?)";
            $row = $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure);
            return $row > 0;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function Update(Keeper $keeper): bool
    {
        try {
            $this->connection = Connection::GetInstance();

            $parameters = $this->setFromValue($keeper);
            $parameters["id"] = $keeper->getId();
            $query = "CALL updateKeeper(?,?,?,?,?,?,?,?,?)";

            $row = $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure);

            return $row > 0;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function GetByEmail(string $email): ?Keeper
    {
        try {
            $this->connection = Connection::GetInstance();

            $parameters["email"] = $email;

            $query = "CALL getKeeperByEmail(?)";

            $keeper = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);
            if ($keeper != null)
                return $this->mapFromValue($keeper);
            return null;


        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function mapFromValue($value): Keeper
    {
        $keeper = new Keeper();
        $stay = new Stay();
        $keeper->setId($value["id"]);
        $keeper->setFirstname($value["firstname"]);
        $keeper->setLastname($value["lastname"]);
        $keeper->setEmail($value["email"]);
        $keeper->setPassword($value["password"]);
        $keeper->setPhone($value["phone"]);
        $keeper->setFee($value["fee"]);
        $stay->setId($value["id"]);
        $stay->setSince($value["since"]);
        $stay->setUntil($value["until"]);
        $keeper->setStay($stay);
        return $value;
    }

    private function setFromValue(Keeper $keeper): array
    {
        $parameters["firstname"] = $keeper->getFirstname();
        $parameters["lastname"] = $keeper->getLastname();
        $parameters["email"] = $keeper->getEmail();
        $parameters["password"] = $keeper->getPassword();
        $parameters["phone"] = $keeper->getPhone();
        $parameters["fee"] = $keeper->getFee();
        $parameters["since"] = $keeper->getStay()->getSince();
        $parameters["until"] = $keeper->getStay()->getUntil();
        return $parameters;
    }
}