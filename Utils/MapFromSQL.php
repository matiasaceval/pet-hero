<?php

namespace Utils;

use DateTime;
use Exception;
use Models\Chat;
use Models\Keeper;
use Models\Message;
use Models\Owner;
use Models\Pet;
use Models\Reservation;
use Models\Reviews;
use Models\Stay;

abstract class MapFromSQL
{
    /**
     * @throws Exception
     */
    public static function MapFromReview($value): Reviews
    {
        $reviews = new Reviews();
        $reviews->setId($value["reviewId"]);
        $reviews->setComment($value["comment"]);
        $reviews->setRating($value["rating"]);
        $value["date"] = FormatterDate::ConvertSingleDateSQLToApp($value["date"]);
        $reviews->setDate($value["date"]);
        $reviews->setReservation(self::MapFromReservation($value));
        return $reviews;
    }

    /**
     * @throws Exception
     */
    public static function MapFromReservation($value): Reservation
    {
        $reservation = new Reservation();
        $reservation->setId($value["reservationId"]);
        $reservation->setState($value["state"]);
        $reservation->setPrice($value["price"]);
        $reservation->setCreatedAt($value["createdAt"]);
        $reservation->setPayment($value["payment"]);
        $dates = FormatterDate::ConvertRangeSQLToApp($value);
        $reservation->setSince($dates["since"]);
        $reservation->setUntil($dates["until"]);
        $reservation->setPet(self::MapFromPet($value));
        $reservation->setKeeper(self::MapFromKeeper($value));

        return $reservation;
    }

    public static function MapFromPet($value): Pet
    {
        $pet = new Pet();
        $pet->setId($value["petId"]);
        $pet->setName($value["name"]);
        $pet->setSpecies($value["species"]);
        $pet->setBreed($value["breed"]);
        $pet->setAge($value["age"]);
        $pet->setImage($value["image"]);
        $pet->setVaccine($value["vaccines"]);
        $pet->setSex($value["sex"]);
        $pet->setActive($value["active"]);
        $pet->setOwner(self::MapFromOwner($value));
        return $pet;
    }


    public static function MapFromOwner($value): Owner
    {
        $owner = new Owner();
        $owner->setId($value["ownerId"]);
        $owner->setFirstname($value["firstname"]);
        $owner->setLastname($value["lastname"]);
        $owner->setEmail($value["email"]);
        $owner->setPhone($value["phone"]);
        $owner->setPassword($value["password"]);
        return $owner;
    }

    /**
     * @throws Exception
     */
    public static function MapFromKeeper($value): ?Keeper
    {
        $keeper = new Keeper();
        $stay = new Stay();
        $keeper->setId($value["keeperId"]);
        $keeper->setFirstname($value["firstname"]);
        $keeper->setLastname($value["lastname"]);
        $keeper->setEmail($value["email"]);
        $keeper->setPassword($value["password"]);
        $keeper->setPhone($value["phone"]);
        $keeper->setFee($value["fee"]);
        $stay->setId($value["id"]);

        $dates = FormatterDate::ConvertRangeSQLToApp($value);


        $stay->setSince($dates["since"]);
        $stay->setUntil($dates["until"]);
        $keeper->setStay($stay);
        return $keeper;
    }

    /**
     * @throws Exception
     */
    public static function MapFromChat($value): Chat
    {
        $owner = new Owner();
        $keeper = new Keeper();


        $owner->setId(intval($value["ownerId"]));
        $owner->setFirstname($value["ownerFirstname"]);
        $owner->setLastname($value["ownerLastname"]);

        $keeper->setId(intval($value["keeperId"]));
        $keeper->setFirstname($value["keeperFirstname"]);
        $keeper->setLastname($value["keeperLastname"]);

        $chat = new Chat(
            $value["reservationId"],
            $keeper,
            $owner,
            [],
        );

        return $chat;
    }

    public static function MapFromMessages($chat, $value): array
    {
        $messages = [];
        foreach ($value as $message) {
            if ($message["id"] !== NULL) {
                $date = date("m-d-Y H:i", strtotime($message["createdAt"]));
                array_push($messages, new Message(
                    boolval($message["ownerIsSender"]) ? $chat->getOwner() : $chat->getKeeper(),
                    $message["text"],
                    $message["state"],
                    $date
                ));
            }
        }
        return $messages;
    }
}
