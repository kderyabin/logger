<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Formatters;

/**
 * Class JsonFormatter
 * @package Kod\Formatters
 */
class JsonFormatter extends AbstractFormatter
{
    /**
     * Default options
     * @var array
     */
    protected $default = [
        'encodeOptions' => \JSON_ERROR_NONE | \JSON_UNESCAPED_SLASHES | \JSON_PRETTY_PRINT,
        'endLog' => PHP_EOL,
    ];

    /**
     * Converts a log data to json
     * @param array $data
     * @return string
     */
    public function format(array $data)
    {
        return json_encode($data, $this->getOptionOrDefault('encodeOptions')) . $this->getOptionOrDefault('endLog');
    }
}
