<?php

namespace SQLDAO;

use Models\Owner;

interface IOwnerDAO {
    public function Add(Owner $owner);

    public function GetAll(): array;

    public function GetById(int $id): ?Owner;

    public function RemoveById(int $id): bool;

    public function Update(Owner $owner): bool;

    public function GetByEmail(string $email): ?Owner;
}