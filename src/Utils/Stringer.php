<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Utils;

class Stringer
{
    /**
     * Converts a value into a string.
     *
     * @param mixed $value
     * @return string
     */
    public static function stringify($value)
    {
        if (is_string($value)) {
            return $value;
        }
        if ($value === null || is_bool($value)) {
            return var_export($value, true);
        }

        if (is_array($value) || (is_object($value) && !method_exists($value, '__toString'))) {
            return json_encode($value, JSON_ERROR_NONE | JSON_UNESCAPED_SLASHES);
        }
        return (string)$value;
    }

    /**
     * @param string $message
     * @return string
     */
    public static function removeEndLines(string $message)
    {
        return str_replace(["\r\n", "\r", "\n"], ' ', $message);
    }

    /**
     * @param string $template
     * @param array $data
     * @return string
     */
    public static function interpolate(string $template, array $data = [])
    {
        $replace = array();
        foreach ($data as $key => $val) {
            $replace['{' . $key . '}'] = static::stringify($val);
        }

        // interpolate replacement values into the template
        return strtr($template, $replace);
    }
}
