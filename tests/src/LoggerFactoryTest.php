<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Tests;


use Kod\Channel;
use Kod\Formatters\AbstractFormatter;
use Kod\Formatters\JsonFormatter;
use Kod\Handlers\AbstractHandler;
use Kod\Handlers\StreamHandler;
use Kod\Tests\Mocks\TestHandler;
use Kod\LoggerFactory;
use Kod\Message;
use Kod\Tests\Mocks\MonologMessage;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class LoggerFactoryTest extends TestCase
{
    public function testMessageFactory()
    {
        $config = [
            'message' => [
                'instance' => MonologMessage::class,
                'fields' => [
                    'channel' => 'test',
                ],
                'setters' => [
                    // set some data in extra field
                    'extra' => function ($extra) {
                        $extra['ip'] = '127.0.0.1';
                        return $extra;
                    }
                ],
            ]
        ];

        $message = LoggerFactory::getMessage($config);
        $data = $message->process(100, LogLevel::DEBUG, 'log message', ['arg' => 'var1']);

        $this->assertTrue($message instanceof MonologMessage);
        $this->assertEquals(array_keys($message->getFields()), array_keys($data));
    }

    public function testLevelCodeMappingDefaults()
    {

        $default = [
            LogLevel::EMERGENCY => LOG_EMERG,
            LogLevel::ALERT => LOG_ALERT,
            LogLevel::CRITICAL => LOG_CRIT,
            LogLevel::ERROR => LOG_ERR,
            LogLevel::WARNING => LOG_WARNING,
            LogLevel::NOTICE => LOG_NOTICE,
            LogLevel::INFO => LOG_INFO,
            LogLevel::DEBUG => LOG_DEBUG,
        ];

        $result = LoggerFactory::getLevelCodeMapping([], $default);
        $this->assertEquals($default, $result);
    }

    public function testLevelCodeMappingOverrideDefaults()
    {
        $custom = [
            LogLevel::EMERGENCY => 600,
            LogLevel::ALERT => 550,
            LogLevel::CRITICAL => 500,
            LogLevel::ERROR => 400,
            LogLevel::WARNING => 300,
            LogLevel::NOTICE => 250,
            LogLevel::INFO => 200,
            LogLevel::DEBUG => 100,
        ];
        $result = LoggerFactory::getLevelCodeMapping(['levelCode' => $custom], []);

        $this->assertEquals($custom, $result);
    }

    /**
     * @testdox Must throw an error if levelPriorityMin/levelPriorityMax is not valid
     */
    public function testLevelPriorityErrors()
    {
        $levelPriority = [
            LogLevel::EMERGENCY => 7,
            LogLevel::ALERT => 6,
            LogLevel::CRITICAL => 5,
            LogLevel::ERROR => 4,
            LogLevel::WARNING => 3,
            LogLevel::NOTICE => 2,
            LogLevel::INFO => 1,
            LogLevel::DEBUG => 0,
        ];
        try {
            LoggerFactory::getMinPriority( ['levelPriorityMin' => 'mylevel'] );
            $this->fail('levelPriorityMin: should throw an InvalidArgumentException ');
        } catch (\Throwable $error) {
            $this->assertTrue($error instanceof \InvalidArgumentException);
        }
        try {
            LoggerFactory::getMaxPriority( ['levelPriorityMax' => 'mylevel']);
            $this->fail('levelPriorityMinx: should throw an InvalidArgumentException');
        } catch (\Throwable $error) {
            $this->assertTrue($error instanceof \InvalidArgumentException);
        }
    }

    public function testDefaultChannelsFactory()
    {
        $channels = LoggerFactory::getChannels();
        /**
         * @var Channel $channel
         */
        $channel = array_shift($channels);
        $this->assertInstanceOf(Channel::class, $channel);
        $this->assertInstanceOf(AbstractHandler::class, $channel->getHandler());
        $this->assertInstanceOf(AbstractFormatter::class, $channel->getFormatter());
    }


    public function testChannelsFactory()
    {
        $config = [
            'channels' => [
                [
                    'handler' => [
                        'instance' => TestHandler::class
                    ],
                    'formatter' => [
                        'instance' => JsonFormatter::class
                    ],
                ],
            ],
        ];

       $channels = LoggerFactory::getChannels($config);
        /**
         * @var Channel $channel
         */
       $channel = array_shift($channels);
       $this->assertInstanceOf(Channel::class, $channel);
       $this->assertInstanceOf(TestHandler::class, $channel->getHandler());
    }
}
