<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Formatter;

/**
 * JsonAbstractFormatter
 */
class JsonFormatter extends AbstractFormatter
{
    /**
     * @var array
     */
    protected $default = [
        'json_encode' => \JSON_ERROR_NONE | \JSON_UNESCAPED_SLASHES | \JSON_PRETTY_PRINT
    ];

    /**
     * @param array $data
     * @return string
     */
    public function format($data)
    {
        return json_encode($data, $this->getOption('json_encode', $this->getDefault('json_encode')));
    }
}
