<?php

namespace Utils;

use Exception;
use Models\Keeper;
use Models\Owner as Owner;
use Models\Pet;

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
        $value["phone"] = $owner->getPhone();
        $value["email"] = $owner->getEmail();
        $value["password"] = $owner->getPassword();
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
        $values["age"] = $pet->getAge();
        $values["sex"] = $pet->getSex();
        $values["ownerId"] = $pet->getOwner()->getId();
        return $values;
    }

    /**
     * @throws Exception
     */

}