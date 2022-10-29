<?php

namespace DAO;

use Models\Stay;
use Models\Keeper;

interface IKeeperDAO
{
    public function Add(Keeper $keeper, Stay $stay);

    public function GetAll(): array;

    public function GetById(int $id): ?Keeper;

    public function RemoveById(int $id): bool;

    public function Update(Keeper $keeper, Stay $stay): bool;

    public function GetByEmail(string $email): ?Keeper;
}
