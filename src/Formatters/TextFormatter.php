<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Formatters;

use Kod\Utils\Stringer;

/**
 * Class TextFormatter
 * @package Kod\Formatters
 */
class TextFormatter extends AbstractFormatter
{
    protected $default = [
        'allow_line_breaks' => true,
        // added after each fields/value
        'separator' => PHP_EOL,
        // appended before the log
        'start_of_log' => '',
        // appended after the log
        'end_of_log' => PHP_EOL ,
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
            $content[] = sprintf("[%s]: %s", $key, Stringer::stringify($value));
        }
        $result = implode($separator, $content);
        if (!$this->getOptionOrDefault('allow_line_breaks')) {
            $result = Stringer::removeEndLines($result);
        }
        $result =
            $this->getOptionOrDefault('start_of_log')
            . $result
            . $this->getOptionOrDefault('end_of_log');

        return $result;
    }
}
