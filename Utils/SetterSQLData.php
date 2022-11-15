<?php

namespace Utils;

use Exception;
use Models\Keeper;
use Models\Owner as Owner;
use Models\Pet;
use Models\Reservation;
use Models\Reviews;

abstract class SetterSQLData
{
    /**
     * @throws Exception
     */
    public static function SetFromKeeper(Keeper $keeper): array
    {
        $parameters["firstname"] = $keeper->getFirstname();
        $parameters["lastname"] = $keeper->getLastname();
        $parameters["email"] = $keeper->getEmail();
        $parameters["password"] = $keeper->getPassword();
        $parameters["phone"] = $keeper->getPhone();
        $parameters["fee"] = $keeper->getFee();

        //-----------------parse date for mysql data base
        $dates["since"] = $keeper->getStay()->getSince();
        $dates["until"] = $keeper->getStay()->getUntil();

        $value = FormatterDate::ConvertRangeAppToSQL($dates);

        $parameters["since"] = $value["since"];
        $parameters["until"] = $value["until"];
        return $parameters;
    }

    /**
     * @throws Exception
     */
    public static function SetFromOwner(Owner $owner): array
    {
        $value["firstname"] = $owner->getFirstname();
        $value["lastname"] = $owner->getLastname();
        $value["email"] = $owner->getEmail();
        $value["password"] = $owner->getPassword();
        $value["phone"] = $owner->getPhone();
        return $value;
    }

    /**
     * @throws Exception
     */
    public static function SetFromPet(Pet $pet): array
    {
        $values = array();
        $values["name"] = $pet->getName();
        $values["species"] = $pet->getSpecies();
        $values["breed"] = $pet->getBreed();
        $values["sex"] = $pet->getSex();
        $values["age"] = $pet->getAge();
        $values["image"] = $pet->getImage();
        $values["vaccine"] = $pet->getVaccine();
        $values["ownerId"] = $pet->getOwner()->getId();
        $values["active"] = $pet->getActive();
        return $values;
    }

    /**
     * @throws Exception
     */
    public static function SetFromReviews(Reviews $reviews): array
    {
        $parameters = array();
        $parameters["comment"] = $reviews->getComment();
        $parameters["rating"] = $reviews->getRating();
        $parameters["reservationId"] = $reviews->getReservation()->getId();
        $parameters["date"] = $reviews->getDate();
        return $parameters;
    }

    /**
     * @throws Exception
     */
    public static function SetFromReservation(Reservation $reservation): array
    {
        $parameters = array();
        $parameters["payment"] = $reservation->getPayment();
        $parameters["petId"] = $reservation->getPet()->getId();
        $parameters["keeperId"] = $reservation->getKeeper()->getId();
        $parameters["price"] = $reservation->getPrice();
        $parameters["state"] = $reservation->getState();

        $dates["since"] = $reservation->getSince();
        $dates["until"] = $reservation->getUntil();
        $value = FormatterDate::ConvertRangeAppToSQL($dates);
        $parameters["since"] = $value["since"];
        $parameters["until"] = $value["until"];
        return $parameters;
    }

}