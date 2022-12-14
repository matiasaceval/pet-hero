<?php

namespace DAO\SQLDAO;

use DAO\Connection;
use DAO\IKeeperDAO as IKeeperDAO;
use DAO\QueryType;
use Exception;
use Models\Keeper as Keeper;
use Utils\MapFromSQL;
use Utils\SetterSQLData;


class KeeperDAO implements IKeeperDAO
{
    private $connection;

    /**
     * @throws Exception
     */
    public function Add(Keeper $keeper): ?int
    {
        $this->connection = Connection::GetInstance();

        $parameters = SetterSQLData::SetFromKeeper($keeper);

        $query = "CALL addKeeper(?,?,?,?,?,?,?,?)";
        $id = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);
        if(count($id) > 0) {
            return $id[0]['LAST_INSERT_ID()'];
        }
        return null;
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
        if (count($result) > 0) {
            return MapFromSQL::MapFromKeeper($result[0]);
        }
        return null;
    }

    /**
     * @throws Exception
     */
    public function RemoveById(int $id): bool
    {
        $this->connection = Connection::GetInstance();

        $parameters["id"] = $id;

        $query = "CALL deleteKeeper(?)";
        $keeperId = $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure);

        if (isset($keeperId)) {
            return true;
        }
        return false;


    }

    /**
     * @throws Exception
     */
    public function Update(Keeper $keeper): bool
    {
        $this->connection = Connection::GetInstance();

        $parameters = SetterSQLData::SetFromKeeper($keeper, $keeper->getId());
        $query = "CALL updateKeeper(?,?,?,?,?,?,?,?,?)";

        return $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure) != null;


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

        if (count($keeper) > 0) {
            return MapFromSQL::MapFromKeeper($keeper[0]);
        }
        return null;
    }


}