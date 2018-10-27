<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Formatter;

/**
 * Class TextFormatter
 * @package Kod\Formatter
 */
class TextFormatter extends AbstractFormatter
{
    protected $default = [
        'allow_line_breaks' => true,
        // added after each fields/value
        'separator' => PHP_EOL,
        // appended before the log
        'start_of_log' => PHP_EOL . '---------------------' . PHP_EOL,
        // appended after the log
        'end_of_log' => PHP_EOL . '---------------------' . PHP_EOL,
    ];

    /**
     * @param array $data
     * @return string
     */
    public function format(array $data)
    {
        $separator = $this->getOptionOrDefault('separator');
        $content = [];
        foreach ($data as $key => $value) {
            $content[] = sprintf("[%s]: %s", $key, $this->stringify($value));
        }
        $result = implode($separator, $content);
        if (!$this->getOptionOrDefault('allow_line_breaks')) {
            $result = $this->removeEndLines($result);
        }
        $result =
            $this->getOptionOrDefault('start_of_log')
            . $result
            . $this->getOptionOrDefault('end_of_log');

        return $result;
    }

    /**
     * Converts a value into a string.
     *
     * @param mixed $value
     * @return string
     */
    public function stringify($value)
    {
        if ($value === null || is_bool($value)) {
            return var_export($value, true);
        }

        if (is_array($value) || (is_object($value) && !method_exists($value, '__toString'))) {
            return json_encode($value);
        }
        return (string)$value;
    }

    /**
     * @param string $message
     * @return string
     */
    public function removeEndLines(string $message)
    {
        return str_replace(["\r\n", "\r", "\n"], ' ', $message);
    }
}
