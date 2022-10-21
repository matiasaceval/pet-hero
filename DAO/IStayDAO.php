<?php

namespace DAO;

use Models\Stay;

interface IStayDAO
{
    public function Add(Stay $stay);

    public function GetAll(): array;

    public function GetById(int $id): ?Stay;

    public function RemoveById(int $id): bool;

    public function Update(Stay $stay): bool;
}
