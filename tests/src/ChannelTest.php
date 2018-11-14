<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Tests;

use Kod\Channel;
use Kod\Formatters\JsonFormatter;
use Kod\LoggerFactory;
use Kod\Tests\Mocks\TestHandler;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class ChannelTest extends TestCase
{
    /**
     * @testdox Should apply min priority bound
     */
    public function testMinPriorityBound()
    {
        $channel = new Channel([
            'levelPriorityMin' => LogLevel::INFO,
        ]);
        $handler = new TestHandler();
        $channel->setHandler($handler)->setFormatter(new JsonFormatter());
        $data = ['message' => 'hello'];

        $this->assertFalse(
            $channel->deliver(LogLevel::DEBUG, $data)
        );
        $this->assertEmpty($handler->log);
        $this->assertTrue(
            $channel->deliver(LogLevel::INFO, $data)
        );
        $this->assertNotEmpty($handler->log);
    }

    /**
     * @testdox Should apply max priority bound
     */
    public function testMaxPriorityBound()
    {
        $channel = new Channel([
            'levelPriorityMax' => LogLevel::ERROR,
        ]);
        $handler = new TestHandler();
        $channel->setHandler($handler)->setFormatter(new JsonFormatter());
        $data = ['message' => 'hello'];

        $this->assertFalse(
            $channel->deliver(LogLevel::CRITICAL, $data)
        );
        $this->assertEmpty($handler->log);
        $this->assertTrue(
            $channel->deliver(LogLevel::INFO, $data)
        );
        $this->assertNotEmpty($handler->log);

    }

    /**
     * @testdox Should apply min and max priority bounds
     */
    public function testMinMaxPriorityBounds()
    {
        $channel = new Channel([
            'levelPriorityMax' => LogLevel::ERROR,
            'levelPriorityMin' => LogLevel::INFO,
        ]);

        $handler = new TestHandler();
        $channel->setHandler($handler)->setFormatter(new JsonFormatter());
        $data = ['message' => 'hello'];

        $this->assertFalse(
            $channel->deliver(LogLevel::DEBUG, $data)
        );
        $this->assertEmpty($handler->log);

        $this->assertFalse(
            $channel->deliver(LogLevel::CRITICAL, $data)
        );
        $this->assertEmpty($handler->log);

        $this->assertTrue(
            $channel->deliver(LogLevel::INFO, $data)
        );
        $this->assertNotEmpty($handler->log);
        $handler->log = '';

        $this->assertTrue(
            $channel->deliver(LogLevel::WARNING, $data)
        );
        $this->assertNotEmpty($handler->log);
    }

    /**
     * @testdox Should not deliver if disabled
     */
    public function testDisabled()
    {
        $channel = LoggerFactory::getChannel([
            'enabled' => false
        ]);

        $this->assertFalse($channel->deliver(LOG_ERR, ['message' => 'Log it']));
    }

}
