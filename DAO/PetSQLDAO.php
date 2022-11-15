<?php

namespace DAO;


use Exception;
use Models\Pet;
use Utils\GenerateFile;
use Utils\MapFromSQL;
use DAO\IPetDAO as IPetDAO;
use Utils\SetterSQLData;

class PetSQLDAO implements IPetDAO
{

    private $connection;


    /**
     * @throws Exception
     */
    public function Add(Pet $pet, array $files): int
    {
        $this->connection = Connection::GetInstance();
        $query = "CALL addPet(?,?,?,?,?,?,?,?)";
        $parameters = SetterSQLData::SetFromPet($pet);


        $id = $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure);
        $pet = $this->GetById($id);
        $image = $files['image'];
        $vaccine = $files['vaccine'];
        $fileName = GenerateFile::PersistFile($image, "photo-pet-", $pet->getId());
        $fileVaccine = GenerateFile::PersistFile($vaccine, "vaccine-pet-", $pet->getId());
        $pet->setImage($fileName);
        $pet->setVaccine($fileVaccine);
        $updatePet = $this->Update($pet);
        return $updatePet->getId();
    }


    /**
     * @throws Exception
     */
    public function GetAll(): array
    {

        $this->connection = Connection::GetInstance();
        $query = "CALL getAllPetAndOwner()";
        $result = $this->connection->Execute($query, array(), QueryType::StoredProcedure);
        $petsList = array();
        foreach ($result as $value) {
            $pet = MapFromSQL::MapFromPet($value);
            array_push($petsList, $pet);
        }
        return $petsList;
    }

    /**
     * @throws Exception
     */
    public function GetById(int $id): ?Pet
    {

        $this->connection = Connection::GetInstance();
        $query = "CALL GetPetById(?)";
        $parameters["id"] = $id;
        $result = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);
        if ($result != null) {
            return MapFromSQL::MapFromPet($result);
        }
        return null;
    }

    /**
     * @throws Exception
     */
    public function GetPetsByOwnerId(int $ownerId): ?array
    {
        $this->connection = Connection::GetInstance();
        $query = "CALL getPetByOwnerId(?)";
        $parameters["id"] = $ownerId;
        $result = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);
        $petsList = array();

        foreach ($result as $value) {
            $pet = MapFromSQL::MapFromPet($value);
            array_push($petsList, $pet);
        }
        return $petsList;

    }

    /**
     * @throws Exception
     */
    public function GetOwnerId(int $petId): ?int
    {
        $this->connection = Connection::GetInstance();
        $query = "CALL GetPetById(?)";
        $parameters["id"] = $petId;
        $result = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);
        if ($result != null) {
            return $result[0]["ownerId"];
        }
        return null;
    }

    /**
     * @throws Exception
     */
    public function RemoveById(int $id): bool
    {

        $this->connection = Connection::GetInstance();
        $query = "CALL deletePet(?)";
        $parameters["id"] = $id;
        return $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure) > 0;
    }

    /**
     * @throws Exception
     */
    public function Update(Pet $pet): ?Pet
    {
        $this->connection = Connection::GetInstance();
        $query = "CALL updatePet(?,?,?,?,?,?,?,?,?)";
        $parameters = SetterSQLData::SetFromPet($pet);
        return $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure);

    }


    /**
     * @throws Exception
     */
    public function DisablePetById(int $id): bool
    {
        $this->connection = Connection::GetInstance();
        $query = "CALL disablePet(?)";
        $parameters["id"] = $id;
        return $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure) != null;
    }
}

