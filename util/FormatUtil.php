<?php

/**
 * Class FormatUtil
 */
class FormatUtil
{
    const UTF8_ENC = 'UTF-8';

    /**
     * Convert PHP DateTime object to SQL timestamp
     *
     * @param DateTime $dateTime
     *
     * @return mixed
     */
    public static function dateTimeToSQLTimestamp($dateTime)
    {
        $dateTime->setTimezone(new DateTimeZone("UTC"));
        return $dateTime->format('Y-m-d H:i:s');
    }

    /**
     * Converts a SQL timestamp to a PHP DateTime string
     *
     * @param string $sqlTimestamp
     *
     * @return string
     */
    public static function sqlTimestampToRFC3339String($sqlTimestamp)
    {
        $dt = DateTime::createFromFormat('Y-m-d G:i:s', $sqlTimestamp);
        return FormatUtil::dateTimeToRFC3339String($dt);
    }

    /**
     * Converts a DateTime to a RFC3339 string in UTC
     *
     * @param mixed $dt
     *
     * @return string
     */
    public static function dateTimeToRFC3339String($dt)
    {
        return str_replace("+00:00", "Z", $dt->format("Y-m-d\\TH:i:s.000P"));
        // Backport from PHP7 - https://github.com/php/php-src/blob/master/ext/date/php_date.c#L824
        // DATE_FORMAT_RFC3339_EXTENDED - Format "v" not existing PHP5 -> .000
        // Z = +00:00 -> RFC3339 short for UTC (application is UTC)
    }

    /**
     * Creates a new DateTime object py parsing string
     *
     * @param string $datetimeStr String to be parsed
     *
     * @return DateTime|bool A DateTime object or false on failure
     */
    public static function parseDateTime($datetimeStr)
    {
        // Some versions of PHP return false, some throw exception if parsing error occurs
        try {
            return new DateTime($datetimeStr);
        } catch (Exception $e) {
            // Try another method
            try {
                return DateTime::createFromFormat(DateTime::RFC3339, $datetimeStr);
            } catch (Exception $e2) {
                // Do nothing
            }
        }

        return false;
    }

    /**
     * Limits a UTF-8 string to a certain length
     *
     * @param string  $str   The string to be limited
     * @param integer $limit Limit to this count
     *
     * @return string UTF-8 String capped to limit
     */
    public static function limitStrUTF8($str, $limit)
    {
        if (mb_strlen($str, FormatUtil::UTF8_ENC) > $limit) {
            return mb_substr($str, 0, $limit, FormatUtil::UTF8_ENC);
        }

        return $str;
    }
}
