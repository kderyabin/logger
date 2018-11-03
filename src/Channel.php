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
    use PriorityLevelTrait;
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
     *
     * @param array|\ArrayAccess $config    Channel configuration
     */
    public function __construct($config = [])
    {
        $this->priorityMin = LoggerFactory::getMinPriority($config);
        $this->priorityMax = LoggerFactory::getMaxPriority($config);
    }

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
        if (!$this->canLog($level)) {
            return $this->isDelivered;
        }
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
