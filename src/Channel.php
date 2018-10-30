<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod;

use Kod\Formatters\AbstractFormatter;
use Kod\Handlers\AbstractHandler;

/**
 * Class Channel
 * @package Kod
 */
class Channel
{
    /**
     * @var AbstractHandler
     */
    protected $handler;
    /**
     * @var AbstractFormatter
     */
    protected $formatter;
    /**
     * Delivered log status
     * @var bool
     */
    protected $isDelivered  = false;

    /**
     * Delivers a log data to some destination (handler).
     *
     * @param string $level
     * @param array $data
     * @return bool
     */
    public function deliver(string $level, array $data): bool
    {
        $this->isDelivered = false;
        return $this->isDelivered = $this->handler->handle($level, $this->formatter->format($data));
    }

    /**
     * @param AbstractHandler $handler
     * @return $this
     */
    public function setHandler(AbstractHandler $handler)
    {
        $this->handler = $handler;
        return $this;
    }

    /**
     * @param AbstractFormatter $formatter
     * @return $this
     */
    public function setFormatter(AbstractFormatter $formatter)
    {
        $this->formatter = $formatter;
        return $this;
    }

    /**
     * @return AbstractHandler
     */
    public function getHandler(): AbstractHandler
    {
        return $this->handler;
    }

    /**
     * @return AbstractFormatter
     */
    public function getFormatter(): AbstractFormatter
    {
        return $this->formatter;
    }

    /**
     * @return bool
     */
    public function isDelivered(): bool
    {
        return $this->isDelivered;
    }
}
