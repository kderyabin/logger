<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Utils;

/**
 * Class Date
 * @package Kod\Utils
 */
class Date
{
    /**
     * Returns DateTime object.
     *
     * @return \DateTime
     */
    public static function getDateTime()
    {
        $dt = \DateTime::createFromFormat('U.u', microtime(true));
        $dt->setTimezone(new \DateTimeZone(date('e')));

        return $dt;
    }

    /**
     * Returns current date in desired format.
     *
     * @param string $format
     * @return string
     */
    public static function getNow($format = DATE_RFC3339_EXTENDED)
    {
        $dt = static::getDateTime();
        return $dt->format($format);
    }
}
