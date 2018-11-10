<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Handlers;

use Psr\Log\LogLevel;

/**
 * Class SyslogHandler
 * @package Kod\Handlers
 */
class SyslogHandler extends AbstractGateHandler
{
    /**
     * Map Psr logger levels to system levels
     * @var array
     */
    protected $sysLevel = [
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
     * @var array
     * @see openlog() for configuration
     */
    protected $default = [
        // the string 'sysIdent' is added to each message
        'sysIdent' => '',
        // 'sysOptions' argument is used to indicate what logging options
        // will be used when generating a log message.
        'sysOptions' => LOG_ODELAY | LOG_PID,
        // 'sysFacility' argument is used to specify what type of program is logging the message
        'sysFacility' => LOG_USER
    ];

    /**
     * Open a connection to system logger.
     *
     * @return bool
     */
    public function open(): bool
    {
        return openlog(
            $this->getOptionOrDefault('sysIdent'),
            $this->getOptionOrDefault('sysOptions'),
            $this->getOptionOrDefault('sysFacility')
        );
    }

    /**
     * Close connection
     * @return void
     */
    public function close(): void
    {
        closelog();
    }

    /**
     * @param string $level
     * @param string $log
     * @return bool
     * @throws \Exception
     */
    public function handle(string $level, string $log): bool
    {
        if (!$this->open()) {
            throw new \Exception('Failed to open a connection to system logger');
        }
        $status = syslog($this->getSystemLevel($level), $log);
        $this->close();

        return $status;
    }

    /**
     * @param string $level
     * @return int
     */
    public function getSystemLevel(string $level): int
    {
        return $this->sysLevel[$level];
    }
}
