<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

/**
 * Class Logger
 * @package Kod
 */
class Logger extends AbstractLogger
{
    use PriorityLevelTrait;
    /**
     * @var Message
     */
    protected $message;
    /**
     * @var Channel[]
     */
    protected $channels = [];

    /**
     * Log level to level code mapping
     */
    protected $levelCode = [
        LogLevel::EMERGENCY => LOG_EMERG,
        LogLevel::ALERT => LOG_ALERT,
        LogLevel::CRITICAL => LOG_CRIT,
        LogLevel::ERROR => LOG_ERR,
        LogLevel::WARNING => LOG_WARNING,
        LogLevel::NOTICE => LOG_NOTICE,
        LogLevel::INFO => LOG_INFO,
        LogLevel::DEBUG => LOG_DEBUG,
    ];

    /**
     * Logger constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->levelCode = LoggerFactory::getLevelCodeMapping($config, $this->levelCode);
        $this->message = LoggerFactory::getMessage($config);
        $this->channels = LoggerFactory::getChannels($config);
        $this->priorityMin = LoggerFactory::getMinPriority($config);
        $this->priorityMax = LoggerFactory::getMaxPriority($config);
    }
    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        if (!$this->canLog($level)) {
            return;
        }
        $levelCode = $this->levelCode[$level];
        $data = $this->message->process($levelCode, $level, $message, $context);
        foreach ($this->channels as $channel) {
            $channel->deliver($level, $data);
        }
    }
}
