<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod;

use Kod\Formatter\AbstractFormatter;
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
     * Delivers a log data to the destination (handler)
     * @param array $data
     * @return bool
     */
    public function deliver(array $data)
    {
        return $this->handler->handle(
            $this->formatter->format($data)
        );
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
}
