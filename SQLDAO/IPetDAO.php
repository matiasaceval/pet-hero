<?php

namespace SQLDAO;

use Models\Pet as Pet;

interface IPetDAO {
    public function Add(Pet $pet, $image);

    public function GetAll(): array;

    public function GetById(int $id): ?Pet;

    public function RemoveById(int $id): bool;

    public function Update(Pet $pet): bool;

    public function GetOwnerId(int $petId): ?int;

    public function GetPetsByOwnerId(int $ownerId): ?array;

}