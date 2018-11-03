<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod;

use Psr\Log\LogLevel;

/**
 * Class LogLevelPriority
 * @package Kod
 */
class LogLevelPriority
{
    /**
     * Log level to log priority mapping.
     */
    protected static $levelPriority = [
        LogLevel::EMERGENCY => 7,
        LogLevel::ALERT => 6,
        LogLevel::CRITICAL => 5,
        LogLevel::ERROR => 4,
        LogLevel::WARNING => 3,
        LogLevel::NOTICE => 2,
        LogLevel::INFO => 1,
        LogLevel::DEBUG => 0,
    ];

    /**
     * Check if passed as a parameter value is defined in priorities array.
     *
     * @param $level
     * @return bool
     */
    public static function isDefined(string $level): bool
    {
        return isset(static::$levelPriority[$level]);
    }

    /**
     * Get numeric value of the priority.
     *
     * @param string $level
     * @return int
     */
    public static function getValue(string $level): int
    {
        return static::$levelPriority[$level] ?? static::$levelPriority[LogLevel::DEBUG];
    }
}
