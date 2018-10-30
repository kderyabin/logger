<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Formatters;

/**
 * Class TextFormatter
 * @package Kod\AbstractFormatter
 */
class LinePatternFormatter extends TextFormatter
{
    protected $default = [
        // Formatting pattern.
        'format' => '%datetime% %level%(%level_code%): %message%',
        'end_of_log' => PHP_EOL,
    ];

    /**
     * @param array $data
     * @return mixed|string
     */
    public function format(array $data)
    {
        $result = $this->getOptionOrDefault('format');
        foreach ($data as $key => $value) {
            if (false === strpos($result, '%' . $key . '%')) {
                continue;
            }
            $result = str_replace('%' . $key . '%', $this->stringify($value), $result);
        }
        $result = $this->removeEndLines($result) . $this->getOptionOrDefault('end_of_log');

        return $result;
    }
}
