<?php

namespace DAO;

use DAO\OwnerDAOJson as OwnerDAO;
use Exception;
use Models\Pet;
use Utils\Session;
use DAO\IPetDAO as IPetDAO;

class PetSQLDAO implements IPetDAO
{

    private $connection;


    /**
     * @throws Exception
     */
    public function Add(Pet $pet, $image)
    {
        $this->connection = Connection::GetInstance();
        $query = "CALL addPet(?,?,?,?,?,?,?,?)";
        $parameters = $this->SetFromPet($pet);
        $parameters["image"] = $image;
        $row = $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure);
        return $row > 0;
    }

    public function GetAll(): array
    {
        // TODO: Implement GetAll() method.
    }

    public function GetById(int $id): ?Pet
    {
        // TODO: Implement GetById() method.
    }

    public function RemoveById(int $id): bool
    {
        // TODO: Implement RemoveById() method.
    }

    public function Update(Pet $pet): bool
    {
        // TODO: Implement Update() method.
    }

    public function GetOwnerId(int $petId): ?int
    {
        // TODO: Implement GetOwnerId() method.
    }

    public function GetPetsByOwnerId(int $ownerId): ?array
    {
        // TODO: Implement GetPetsByOwnerId() method.
    }


    private function SetFromPet(Pet $pet): array
    {
        $values = array();
        $values["name"] = $pet->getName();
        $values["species"] = $pet->getSpecies();
        $values["breed"] = $pet->getBreed();
        $values["age"] = $pet->getAge();
        $values["sex"] = $pet->getSex();
        $values["vaccine"] = $pet->getVaccine();
        $values["ownerId"] = $pet->getOwner()->getId();
    }

    /**
     * @throws Exception
     */
    private function PersistImage($image, int $petId): string
    {
        $fileExt = explode(".", $image["name"]);
        $fileType = strtolower(end($fileExt));
        $filePreName = "photo-pet-" . $petId;
        $fileName = $filePreName . "." . $fileType;
        $tempFileName = $image["tmp_name"];
        $filePath = UPLOADS_PATH . basename($fileName);
        $imageSize = getimagesize($tempFileName);

        if ($imageSize !== false) {
            $files = glob(UPLOADS_PATH . $filePreName . ".*");
            foreach ($files as $file) {
                chmod($file, 0755); //Change the file permissions if allowed
                unlink($file); //remove the file
            }
            if (move_uploaded_file($tempFileName, $filePath)) {
                return $fileName;
            } else {
                Session::Set("error", "Error uploading image");
            }
        } else {
            Session::Set("error", "File is not an image");
        }
        return "";
    }
}
