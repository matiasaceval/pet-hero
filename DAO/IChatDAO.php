<?php

namespace DAO;

use Models\Chat;
use Models\Keeper;
use Models\Owner;

interface IChatDAO
{
    public function GetById(int $id): ?Chat;

    public function CreateMessage(Chat $chat): ?int;

    public function MarkAsReceived(Owner|Keeper $user): void;

    public function MarkAsRead(Owner|Keeper $user, Chat $chat): void;
}
