<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Handlers;

/**
 * Class AbstractGateHandler
 * @package Kod\Handlers
 */
abstract class AbstractGateHandler extends AbstractHandler
{
    /**
     * Open a log destination.
     * @return bool     TRUE on success FALSE on failure
     */
    abstract public function open(): bool;

    /**
     * Close log destination
     * @return void
     */
    abstract public function close(): void;
}
