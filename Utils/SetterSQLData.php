<?php

namespace Utils;

use DateTime;
use Exception;
use Models\Keeper;
use Models\Message;
use Models\Owner as Owner;
use Models\Pet;
use Models\Reservation;
use Models\Reviews;

abstract class SetterSQLData
{
    /**
     * @throws Exception
     */
    public static function SetFromKeeper(Keeper $keeper, int $id = null): array
    {
        if ($id != null) $parameters["id"] = $id;
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
    public static function SetFromOwner(Owner $owner, int $id = null): array
    {
        if ($id != null) $value["id"] = $id;
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
        $parameters["date"] = FormatterDate::ConvertSingleDateAppToSQL($reviews->getDate());
        $parameters["reservationId"] = $reviews->getReservation()->getId();
        return $parameters;
    }

    /**
     * @throws Exception
     */
    public static function SetFromReservation(Reservation $reservation): array
    {
        $parameters = array();
        $parameters["petId"] = $reservation->getPet()->getId();
        $parameters["keeperId"] = $reservation->getKeeper()->getId();
        $parameters["state"] = $reservation->getState();

        $dates["since"] = $reservation->getSince();
        $dates["until"] = $reservation->getUntil();
        $value = FormatterDate::ConvertRangeAppToSQL($dates);
        $parameters["since"] = $value["since"];
        $parameters["until"] = $value["until"];

        $parameters["price"] = $reservation->getPrice();
        $parameters["payment"] = $reservation->getPayment();

        return $parameters;
    }

    /**
     * @throws Exception
     */
    public static function SetFromMessage(int $chatId, Message $message): array
    {
        $parameters["chatId"] = $chatId;
        $parameters["content"] = $message->getText();
        $parameters["ownerIsSender"] = $message->getSender() instanceof Owner;
        return $parameters;
    }
}
