<?php

namespace SQLDAO;

use Models\Reviews;

interface IReviewsDAO {
    public function Add(Reviews $reviews);

    public function GetAll(): array;

    public function GetById(int $id): ?Reviews;

    public function RemoveById(int $id): bool;

    public function Update(Reviews $reviews): bool;

    public function GetByKeeperId(int $id): array;
}
