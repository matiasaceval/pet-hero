<?php

namespace Utils;

use DateTime;
use Exception;

abstract class FormatterDate
{
    /**
     * @throws Exception
     */
    public static function ConvertRangeSQLToApp($dates)
    {
        //------------------ parse date from mysql to php
        $since = new DateTime($dates["since"]);
        $until = new DateTime($dates["until"]);
        //to string
        $sinceAmericanFormat = \date("d-m-Y", $since);
        $untilAmericanFormat = \date("d-m-Y", $until);
        $dates["since"] = $sinceAmericanFormat;
        $dates["until"] = $untilAmericanFormat;
        return $dates;
    }

    /**
     * @throws Exception
     */
    public static function ConvertRangeAppToSQL($dates)
    {
        //------------------ parse date from php to mysql
        $since = new DateTime($dates["since"]);
        $until = new DateTime($dates["until"]);
        //to string
        $sinceAmericanFormat = \date("Y-m-d H:i:s", $since);
        $untilAmericanFormat = \date("Y-m-d H:i:s", $until);
        $dates["since"] = $sinceAmericanFormat;
        $dates["until"] = $untilAmericanFormat;
        return $dates;
    }

    /**
     * @throws Exception
     */
    public static function ConvertSingleDateSQLToApp($date): string
    {
        $date = new DateTime($date);
        return \date("d-m-Y", $date);
    }

    /**
     * @throws Exception
     */
    public static function ConvertSingleDateAppToSQL($date): string
    {
        $date = new DateTime($date);
        return \date("Y-m-d H:i:s", $date);
    }

}
?>