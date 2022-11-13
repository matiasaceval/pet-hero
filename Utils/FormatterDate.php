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
        $since = new DateTime($dates["since"]);
        $until = new DateTime($dates["until"]);
        //to string
        $sinceAmericanFormat = \date("m-d-Y", $since);
        $untilAmericanFormat = \date("m-d-Y", $until);
        $dates["since"] = $sinceAmericanFormat;
        $dates["until"] = $untilAmericanFormat;
        return $dates;
    }

    /**
     * @throws Exception
     */
    public static function ConvertRangeAppToSQL($dates): array
    {
        //------------------ parse date from php to mysql
        $since = new DateTime($dates["since"]);
        $until = new DateTime($dates["until"]);
        //to string
        $sinceSQLFormat = \date("Y-m-d H:i:s", $since);
        $untilSQLFormat = \date("Y-m-d H:i:s", $until);
        $dates["since"] = $sinceSQLFormat;
        $dates["until"] = $untilSQLFormat;
        return $dates;
    }

    /**
     * @throws Exception
     */
    public static function ConvertSingleDateSQLToApp($date): array
    {

        $date = new DateTime($date["date"]);
        $dateAmericanFormat = \date("m-d-Y", $date);
        $dates["date"] = $dateAmericanFormat;
        return $dates;
    }

    /**
     * @throws Exception
     */
    public static function ConvertSingleDateAppToSQL($date): array
    {
        $date = new DateTime($date["date"]);
        $dateSQLFormat = \date("Y-m-d", $date);
        $dates["date"] = $dateSQLFormat;
        return $dates;
    }
}