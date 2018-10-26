<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Formatter;

use Kod\OptionsTrait;

/**
 * AbstractFormatter
 */
abstract class AbstractFormatter
{
    use OptionsTrait;

    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }
    /**
     * @param array $data
     * @return string
     */
    abstract public function format($data);
}
