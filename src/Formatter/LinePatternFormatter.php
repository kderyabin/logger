<?php
/**
 * Created on 2018-10-19
 */

namespace Kod\Formatter;

/**
 * Class TextFormatter
 * @package Kod\AbstractFormatter
 */
class LinePatternFormatter extends TextFormatter
{
    protected $default = [
        'format' => '%datetime% %level%(%level_code%): %message%',
        'end_of_log' => PHP_EOL,
    ];

    public function format($data)
    {
        $result = $this->getOption('format', $this->getDefault('format'));
        foreach ($data as $key => $value) {
            if (false === strpos($result, '%' . $key . '%')) {
                continue;
            }
            $result = str_replace('%' . $key . '%', $this->stringify($value), $result);
        }
        $result = $this->removeEndLines($result) . $this->getOption('end_of_log', $this->getDefault('end_of_log'));

        return $result;
    }
}
