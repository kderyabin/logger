<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Handlers;

use Kod\PriorityLevelTrait;
use Kod\OptionsTrait;

/**
 * Formatters
 */
abstract class AbstractHandler
{
    use OptionsTrait;

    /**
     * AbstractHandler constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * @param string $log
     * @return bool
     */
    abstract public function handle(string $log);
}
