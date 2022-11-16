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
    public function Add(Pet $pet, array $files): ?int
    {
        $this->connection = Connection::GetInstance();
        $query = "CALL addPet(?,?,?,?,?,?,?,?)";
        $parameters = SetterSQLData::SetFromPet($pet);


        $id = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);
        if (count($id) > 0) {
            $id = $id[0]['LAST_INSERT_ID()'];
            $pet->setId($id);
            $image = $files['image'];
            $vaccine = $files['vaccine'];
            $fileName = GenerateFile::PersistFile($image, "photo-pet-", $id);
            $fileVaccine = GenerateFile::PersistFile($vaccine, "vaccine-pet-", $id);
            $pet->setImage($fileName);
            $pet->setVaccine($fileVaccine);
            $this->Update($pet);
            return $id;
        }
        return null;
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
        if (count($result) > 0) {
            return MapFromSQL::MapFromPet($result[0]);
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
        $query = "CALL updatePet(?,?,?,?,?,?,?,?,?,?)";
        $parameters = $this->SetParametersToUpdate($pet);
        $petUpdated = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);
        if (count($petUpdated) > 0) {
            return MapFromSQL::MapFromPet($petUpdated[0]);
        }
        return null;
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

    private function SetParametersToUpdate(Pet $pet): array
    {
        $parameters = array();
        $parameters["id"] = $pet->getId();
        $parameters["name"] = $pet->getName();
        $parameters["species"] = $pet->getSpecies();
        $parameters["breed"] = $pet->getBreed();
        $parameters["sex"] = $pet->getSex();
        $parameters["age"] = $pet->getAge();
        $parameters["image"] = $pet->getImage();
        $parameters["vaccines"] = $pet->getVaccine();
        $parameters["ownerId"] = $pet->getOwner()->getId();
        $parameters["active"] = $pet->getActive();
        return $parameters;
    }
}


