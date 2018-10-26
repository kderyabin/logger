<?php
/**
 * Created on 2018-10-19
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
