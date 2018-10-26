<?php
/**
 * Created on 2018-10-17
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
