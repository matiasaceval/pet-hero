<?php

namespace DAO;

use Models\Reservation;

interface IReservationDAOJson
{
    public function Add(Reservation $reservation);

    public function GetAll(): array;

    public function GetById(int $id): ?Reservation;

    public function GetByKeeperId(int $id): array;

    public function GetByPetId(int $id): array;

    public function GetByState(string $state): array;

    public function GetByOwnerId(int $id): array;

    public function Update(Reservation $reservation): bool;

    public function RemoveById(int $id): bool;


}