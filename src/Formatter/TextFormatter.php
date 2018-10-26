<?php
/**
 * Created on 2018-10-19
 */

namespace Kod\Formatter;

/**
 * Class TextFormatter
 * @package Kod\AbstractFormatter
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
        'end_of_log' => PHP_EOL .'---------------------' . PHP_EOL,
    ];

    public function format($data)
    {
        $separator = $this->getOption('separator', $this->getDefault('separator'));;
        $content = [];
        foreach ($data as $key => $value) {
            $content[] = sprintf("[%s]: %s", $key, $this->stringify($value));
        }
        $result = implode($separator, $content);
        if (!$this->getOption('allow_line_breaks', $this->getDefault('allow_line_breaks'))) {
            $result = $this->removeEndLines($result);
        }
        $result =
            $this->getOption('start_of_log', $this->getDefault('start_of_log'))
            . $result
            . $this->getOption('end_of_log', $this->getDefault('end_of_log'));

        return $result;
    }

    /**
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
