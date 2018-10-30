<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Formatters;

use Kod\OptionsTrait;

/**
 * Class AbstractFormatter
 * @package Kod\Formatters
 */
abstract class AbstractFormatter
{
    use OptionsTrait;

    /**
     * AbstractFormatter constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }
    /**
     * Format a log data according to custom or default options.
     *
     * @param array $data
     * @return string
     */
    abstract public function format(array $data);
}
