<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Tests;

use Kod\Channel;
use Kod\Formatter\JsonFormatter;
use Kod\Handlers\OutputHandler;
use Kod\Tests\Mocks\TestHandler;
use Kod\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class LoggerTest extends TestCase
{
    /**
     * @testdox Should initialize a logger with default configuration
     */
    public function testInitializationFromConfig()
    {
        $channel = new Channel();
        $handler = new TestHandler();
        $channel->setHandler($handler)->setFormatter(new JsonFormatter());
        $msg = 'Log message';
        $logger = new Logger([
            'channels' => [$channel]
        ]);

        $message = 'Info message';
        $logger->info($message);

        $this->assertNotEmpty($handler->log);
        $this->assertContains($message, $handler->log);
    }
    /**
     * @testdox Should apply min priority bound
     */
    public function testMinPriorityBound()
    {
        $channel = new Channel();
        $handler = new TestHandler();
        $channel->setHandler($handler)->setFormatter(new JsonFormatter());
        $msg = 'Log message';
        $logger = new Logger([
            'levelPriorityMin' => LogLevel::INFO,
            'channels' => [$channel]
        ]);
        $logger->debug($msg);
        $this->assertEmpty($handler->log);
        $logger->info($msg);
        $this->assertNotEmpty($handler->log);
    }

    /**
     * @testdox Should apply max priority bound
     */
    public function testMaxPriorityBound()
    {
        $channel = new Channel();
        $handler = new TestHandler();
        $channel->setHandler($handler)->setFormatter(new JsonFormatter());
        $msg = 'Log message';
        $logger = new Logger([
            'levelPriorityMax' => LogLevel::ERROR,
            'channels' => [$channel]
        ]);

        $logger->critical($msg);
        $this->assertEmpty($handler->log);

        $logger->info($msg);
        $this->assertNotEmpty($handler->log);

    }

    /**
     * @testdox Should apply min and max priority bounds
     */
    public function testMinMaxPriorityBounds()
    {
        $channel = new Channel();
        $handler = new TestHandler();
        $channel->setHandler($handler)->setFormatter(new JsonFormatter());
        $msg = 'Log message';
        $logger = new Logger([
            'levelPriorityMax' => LogLevel::ERROR,
            'levelPriorityMin' => LogLevel::INFO,
            'channels' => [$channel]
        ]);

        $msg = 'Log message';

        $logger->debug($msg);
        $this->assertEmpty($handler->log);

        $logger->critical($msg);
        $this->assertEmpty($handler->log);

        $logger->info($msg);
        $this->assertNotEmpty($handler->log);
        $handler->log = '';

        $logger->warning($msg);
        $this->assertNotEmpty($handler->log);
    }
}