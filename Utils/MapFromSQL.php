<?php

namespace Utils;

use Exception;
use Models\Keeper;
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
        $reviews->setId($value["id"]);
        $reviews->setComment($value["comment"]);
        $reviews->setRating($value["rating"]);
        $value["date"] = FormatterDate::ConvertSingleDateSQLToApp($value["date"]);
        $reviews->setDate((string)$value["date"]);
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
        $dates = FormatterDate::ConvertRangeSQLToApp([$value["since"], $value["until"]]);
        $reservation->setSince($dates["since"]);
        $reservation->setUntil($dates["until"]);
        $reservation->setPet(self::MapFromPet($value));
        $reservation->setKeeper(self::MapFromKeeper($value));

        return $reservation;

    }

    public static function MapFromPet($value): Pet
    {
        $pet = new Pet();
        $pet->setId($value["id"]);
        $pet->setName($value["name"]);
        $pet->setSpecies($value["species"]);
        $pet->setBreed($value["breed"]);
        $pet->setAge($value["age"]);
        $pet->setImage($value["image"]);
        $pet->setVaccine($value["vaccine"]);
        $pet->setSex($value["sex"]);
        $pet->setOwner(self::MapFromOwner($value));
        return $pet;

    }


    public static function MapFromOwner($value): Owner
    {
        $owner = new Owner();
        $owner->setId($value["id"]);
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
    public static function MapFromKeeper($value): Keeper
    {
        $keeper = new Keeper();
        $stay = new Stay();
        $keeper->setId($value["id"]);
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
}

?>