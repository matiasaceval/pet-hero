<?php

namespace DAO;

use Models\Reviews;

interface IReviewsDAO {
    public function Add(Reviews $reviews);

    public function GetAll(): array;

    public function GetById(int $id): ?Reviews;

    public function RemoveById(int $id): bool;

    public function Update(Reviews $reviews): bool;

    public function GetByKeeperId(int $id): array;

    public function GetByOwnerId(int $id): array;

    public function GetByReservationId(int $id): Reviews|null;
}
