<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Handlers;

use Kod\OptionsTrait;

/**
 * Class AbstractHandler
 * @package Kod\Handlers
 */
abstract class AbstractHandler
{
    use OptionsTrait;

    /**
     * Handle a log message.
     *
     * @param string $level
     * @param string $log
     * @return bool
     */
    abstract public function handle(string $level, string $log): bool;

    /**
     * AbstractHandler constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }
}
