<?php
/**
 * Created by IntelliJ IDEA.
 * User: Konstantin
 * Date: 19/10/2018
 * Time: 23:18
 */

namespace Kod;

use Kod\Formatter\AbstractFormatter;
use Kod\Formatter\JsonFormatter;
use Kod\Handlers\AbstractHandler;
use Kod\Handlers\StreamHandler;

/**
 * Class LoggerFactory
 * @package Kod
 */
class LoggerFactory
{
    /**
     * @param array $config
     * @return Message
     */
    public static function getMessage(array $config): Message
    {
        $message = Message::class;
        $msgConf = !empty($config['message']) ? $config['message'] : [];
        if (!empty($config['message']['instance'])) {
            $message = $config['message']['instance'];
            unset($config['message']['instance']);
        }
        $message = new $message($msgConf);

        return $message;
    }

    /**
     * @param array $config
     * @return array|\ArrayAccess
     * @throws \InvalidArgumentException
     */
    public static function getChannels(array $config = []): array
    {
        $channels = [];
        if (empty($config['channels'])) {
            $channels[] = static::getChannel([]);
            return $channels;
        }

        foreach ($config['channels'] as $channel) {
            $channels[] = ($channel instanceof Channel) ? $channel : static::getChannel($channel);
        }

        return $channels;
    }

    /**
     * @param array $config
     * @return Channel
     * @throws \InvalidArgumentException
     */
    protected static function getChannel(array $config = []): Channel
    {
        if (!empty($config['instance'])) {
            $channel = new $config['instance'];
            unset($config['instance']);
        } else {
            $channel = new Channel();
        }
        /*
         * @var Channel $channel
         */
        $channel->setHandler(static::getHandler(empty($config['handler']) ? [] : $config['handler']));
        $channel->setFormatter(static::getFormatter(empty($config['formatter']) ? [] : $config['formatter']));


        return $channel;
    }

    /**
     * @param array $config
     * @return AbstractHandler
     */
    public static function getHandler(array $config): AbstractHandler
    {
        if( !empty($config['instance'])){
            $handler = $config['instance'];
            unset($config['instance']);
        } else {
            $handler = StreamHandler::class;
        }
        return new $handler($config);
    }

    /**
     * @param array $config
     * @return AbstractFormatter
     */
    public static function getFormatter(array $config): AbstractFormatter
    {
        /**
         * @var AbstractHandler $handler
         */

        if( !empty($config['instance'])){
            $formatter = $config['instance'];
            unset($config['instance']);
        } else {
            $formatter = JsonFormatter::class;
        }

        return new $formatter($config);
    }

    /**
     * @param array $config
     * @param array $default
     * @return array
     */
    public static function getLevelCodeMapping(array $config, array $default): array
    {
        if (!empty($config['levelCode'])) {
            return $config['levelCode'];
        }
        return $default;
    }

    /**
     * @param array $priorities
     * @param array|\ArrayAccess $config
     * @return int|null
     */
    public static function getMinPriority(array $priorities, $config = [])
    {
        if (!empty($config['levelPriorityMin'])) {

            if (!isset($priorities[$config['levelPriorityMin']])) {
                throw new \InvalidArgumentException(
                    sprintf('levelPriorityMin "%s" is not declared in level priorities', $config['levelPriorityMin'])
                );
            }
            return $priorities[$config['levelPriorityMin']];

        }
        return null;
    }

    /**
     * @param array $priorities
     * @param array|\ArrayAccess $config
     * @return int|null
     */
    public static function getMaxPriority(array $priorities, $config = [])
    {
        if (!empty($config['levelPriorityMax'])) {
            if (!isset($priorities[$config['levelPriorityMax']])) {
                throw new \InvalidArgumentException(
                    sprintf('levelPriorityMax "%s" is not declared in level priorities', $config['levelPriorityMax'])
                );
            }
            return $priorities[$config['levelPriorityMax']];
        }
        return null;
    }
}
