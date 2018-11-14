<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod;

use Kod\Formatters\AbstractFormatter;
use Kod\Formatters\JsonFormatter;
use Kod\Handlers\AbstractHandler;
use Kod\Handlers\StreamHandler;
use Psr\Log\LogLevel;

/**
 * Class LoggerFactory
 * @package Kod
 */
class LoggerFactory
{
    /**
     * Initializes a Message instance from the logger configuration.
     * Message configuration must be set in an array with the key "message".
     *
     * @param array $config     Logger configuration
     * @return Message
     */
    public static function getMessage(array $config = []): Message
    {
        $message = Message::class;
        $msgConf = $config['message'] ?? [];
        if (!empty($config['message']['instance'])) {
            $message = $config['message']['instance'];
            unset($config['message']['instance']);
        }
        $message = new $message($msgConf);

        return $message;
    }

    /**
     * Initializes an array of Channel's instances from the logger configuration.
     * Channels settings are declared with the key "chanells".
     * @see static::getChannel for Channel settings.
     *
     * @param array|\ArrayAccess $config    Logger configuration
     * @return Channel[]
     * @throws \InvalidArgumentException
     */
    public static function getChannels($config = []): array
    {
        $channels = [];
        if (empty($config['channels'])) {
            $channels[] = static::getChannel();
            return $channels;
        }

        foreach ($config['channels'] as $channel) {
            $channels[] = ($channel instanceof Channel) ? $channel : static::getChannel($channel);
        }

        return $channels;
    }

    /**
     * Initializes a Channel instance.
     *
     * @param array $config     Channel settings
     * @return Channel
     * @throws \InvalidArgumentException
     */
    public static function getChannel(array $config = []): Channel
    {
        $channel = new Channel();
        $channel->setEnabled(LoggerFactory::getEnableStatus($config));
        $channel->setHandler(static::getHandler($config['handler'] ?? []));
        $channel->setFormatter(static::getFormatter($config['formatter'] ?? []));

        return $channel;
    }

    /**
     * Initializes a handler instance.
     * If the configuration is empty a default handler is returned.
     * All settings except 'instance' are passed to the handler constructor.
     * If declared, an 'instance' value must be a fully qualified class name.
     *
     * @param array $config     Handler settings
     * @return AbstractHandler
     */
    public static function getHandler(array $config = []): AbstractHandler
    {
        if (!empty($config['instance'])) {
            $handler = $config['instance'];
            unset($config['instance']);
        } else {
            $handler = StreamHandler::class;
        }
        return new $handler($config);
    }

    /**
     * Initializes a formatter instance.
     * If the configuration is empty a default formatter is returned.
     * All settings except 'instance' are passed to the formatter constructor.
     * If declared, an 'instance' value must be a fully qualified class name.
     *
     * @param array $config     Formatter settings
     * @return AbstractFormatter
     */
    public static function getFormatter(array $config = []): AbstractFormatter
    {
        if (!empty($config['instance'])) {
            $formatter = $config['instance'];
            unset($config['instance']);
        } else {
            $formatter = JsonFormatter::class;
        }

        return new $formatter($config);
    }

    /**
     * @param array|\ArrayAccess $config        Logger configuration
     * @param array $default                    Default mapping
     * @return array
     */
    public static function getLevelCodeMapping($config, array $default): array
    {
        if (!empty($config['levelCode'])) {
            return $config['levelCode'];
        }
        return $default;
    }

    /**
     * Initializes a minimal log level priority.
     * Converts a named level to integer value.
     * This value must be declared with the key 'levelPriorityMin' and must be one of LogLevel constants.
     * @see LogLevel
     * @example 'levelPriorityMin' => 'info'
     *
     * @param array|\ArrayAccess $config    Logger configuration
     * @return int|null
     */
    public static function getMinPriority($config = [])
    {
        if (!empty($config['levelPriorityMin'])) {
            if (!LogLevelPriority::isDefined($config['levelPriorityMin'])) {
                throw new \InvalidArgumentException(
                    sprintf('levelPriorityMin "%s" is not declared in level priorities', $config['levelPriorityMin'])
                );
            }
            return LogLevelPriority::getValue($config['levelPriorityMin']);
        }
        return null;
    }

    /**
     * Initializes a maximum log level priority.
     * Converts a named level to integer value.
     * This value must be declared with the key 'levelPriorityMax' and must be one of LogLevel constants.
     * @see LogLevel
     * @example 'levelPriorityMax' => 'notice'
     *
     * @param array|\ArrayAccess $config    Logger configuration
     * @return int|null
     */
    public static function getMaxPriority($config = [])
    {
        if (!empty($config['levelPriorityMax'])) {
            if (!LogLevelPriority::isDefined($config['levelPriorityMax'])) {
                throw new \InvalidArgumentException(
                    sprintf('levelPriorityMax "%s" is not declared in level priorities', $config['levelPriorityMax'])
                );
            }
            return LogLevelPriority::getValue($config['levelPriorityMax']);
        }
        return null;
    }

    /**
     * Get 'enabled' keyword  value from the configuration.
     * Any type different from boolean will result in true value.
     *
     * @param array|\ArrayAccess $config
     * @return bool
     */
    public static function getEnableStatus($config = []): bool
    {
        return isset($config['enabled']) && is_bool($config['enabled']) ? $config['enabled'] : true;
    }
}
