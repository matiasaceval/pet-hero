<?php

namespace Utils;

use DateTime;
use Exception;

abstract class FormatterDate
{
    /**
     * @throws Exception
     */
    public static function ConvertRangeSQLToApp($dates): array
    {
        //------------------ parse date from mysql to php
        $since = strtotime($dates["since"]);
        $until = strtotime($dates["until"]);
        //to string

        $dates["since"] = date("m-d-Y", $since);
        $dates["until"] = date("m-d-Y", $until);
        return $dates;
    }

    /**
     * @throws Exception
     */
    public static function ConvertRangeAppToSQL($dates): array
    {
        //------------------ parse date from php to mysql
        $since = DateTime::createFromFormat("m-d-Y", $dates["since"]);
        $until = DateTime::createFromFormat("m-d-Y", $dates["until"]);
        //to string

        $dates["since"] = $since->format("Y-m-d");
        $dates["until"] = $until->format("Y-m-d");
        return $dates;
    }

    /**
     * @throws Exception
     */
    public static function ConvertSingleDateSQLToApp($date): string
    {
        $date = strtotime($date);
        return date("m-d-Y", $date);
    }

    /**
     * @throws Exception
     */
    public static function ConvertSingleDateAppToSQL($date): string
    {
        $date = DateTime::createFromFormat("m-d-Y", $date);
        return $date->format("Y-m-d");
    }

}

?>