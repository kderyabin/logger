<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Utils;

/**
 * Date
 */
class Date
{
    /**
     * Returns current date time.
     * @param string $format
     * @return string
     */
    public static function getNow($format = 'Y-m-d\TH:i:s.v\Z')
    {
        $now = number_format(microtime(true), 3, '.', '');
        return (\DateTime::createFromFormat('U.u', $now))->format($format);
    }
}
