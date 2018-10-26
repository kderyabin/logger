<?php
/**
 * Created on 2018-10-17
 */

namespace Kod;

use Kod\Formatter\AbstractFormatter;
use Kod\Handlers\AbstractHandler;

/**
 * Channel
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
