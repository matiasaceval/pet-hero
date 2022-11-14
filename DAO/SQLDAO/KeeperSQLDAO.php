<?php

namespace DAO;

use DAO\IKeeperDAO as IKeeperDAO;
use DateTime;
use Exception;
use Models\Keeper as Keeper;
use Models\Stay;
use Utils\FormatterDate;
use Utils\MapFromSQL;


class KeeperSQLDAO implements IKeeperDAO
{
    private $connection;

    /**
     * @throws Exception
     */
    public function Add(Keeper $keeper): ?int
    {
        $this->connection = Connection::GetInstance();

        $parameters = $this->setFromValue($keeper);

        $query = "CALL addKeeper(?,?,?,?,?,?,?,?)";
        return $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure);
    }

    /**
     * @throws Exception
     */
    public function GetAll(): array
    {
        $this->connection = Connection::GetInstance();
        $query = "CALL getAllKeepers()";
        $result = $this->connection->Execute($query, array(), QueryType::StoredProcedure);
        $keepersList = array();
        foreach ($result as $value) {
            $keeper = MapFromSQL::MapFromKeeper($value);
            array_push($keepersList, $keeper);
        }
        return $keepersList;
    }

    /**
     * @throws Exception
     */
    public function GetById(int $id): ?Keeper
    {
        $this->connection = Connection::GetInstance();

        $parameters["id"] = $id;
        $query = "CALL getKeeperById(?)";
        $result = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);
        if ($result != null) {
            return MapFromSQL::MapFromKeeper($result);
        }
        return null;
    }

    /**
     * @throws Exception
     */
    public function RemoveById(int $id): ?int
    {
        $this->connection = Connection::GetInstance();

        $parameters["id"] = $id;

        $query = "CALL deleteKeeper(?)";
        return $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure);

    }

    /**
     * @throws Exception
     */
    public function Update(Keeper $keeper): ?Keeper
    {
        $this->connection = Connection::GetInstance();

        $parameters = $this->setFromValue($keeper);
        $parameters["id"] = $keeper->getId();
        $query = "CALL updateKeeper(?,?,?,?,?,?,?,?,?)";

        return $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure);


    }

    /**
     * @throws Exception
     */
    public function GetByEmail(string $email): ?Keeper
    {
        $this->connection = Connection::GetInstance();

        $parameters["email"] = $email;

        $query = "CALL getKeeperByEmail(?)";

        $keeper = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);

        if ($keeper != null)
            return MapFromSQL::MapFromKeeper($keeper);
        return null;
    }



    /**
     * @throws Exception
     */
    private function setFromValue(Keeper $keeper): array
    {
        $parameters["firstname"] = $keeper->getFirstname();
        $parameters["lastname"] = $keeper->getLastname();
        $parameters["email"] = $keeper->getEmail();
        $parameters["password"] = $keeper->getPassword();
        $parameters["phone"] = $keeper->getPhone();
        $parameters["fee"] = $keeper->getFee();

        //-----------------parse date for mysql data base
        $dates["since"] = $keeper->getStay()->getSince();
        $dates["until"] = $keeper->getStay()->getUntil();

        $value = FormatterDate::ConvertRangeAppToSQL($dates);

        $parameters["since"] = $value["since"];
        $parameters["until"] = $value["until"];
        return $parameters;
    }
}