<?php

namespace DAO;

use Models\Keeper;

interface IKeeperDAO
{
    public function Add(Keeper $keeper);

    public function GetAll(): array;

    public function GetById(int $id): ?Keeper;

    public function RemoveById(int $id): bool;

    public function Update(Keeper $keeper): bool;

    public function GetByEmail(string $email): ?Keeper;
}
