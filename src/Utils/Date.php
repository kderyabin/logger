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
     * Returns current date time in desired format.
     *
     * @param string $format
     * @return string
     */
    public static function getNow($format = DATE_RFC3339_EXTENDED)
    {
        return (\DateTime::createFromFormat('U.u', microtime(true)))->format($format);
    }
}
