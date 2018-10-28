<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Tests\Handlers;

use Kod\Channel;
use Kod\Formatter\JsonFormatter;
use Kod\Formatter\LinePatternFormatter;
use Kod\Handlers\SyslogHandler;
use Kod\Logger;
use Kod\LoggerFactory;
use PHPUnit\Framework\TestCase;

class SyslogHandlerTest extends TestCase
{
    /**
     * @testdox Should instantiate SyslogHandler and write a system log
     */
    public function testSystemLogWriting()
    {
        $channel = LoggerFactory::getChannel( [
            'handler' => [
                'instance' => SyslogHandler::class,
                'sys_ident' => 'phpunit',
                'sys_option' => LOG_ODELAY,
                'sys_facility' => LOG_USER,
            ],
            'formatter' => [
                'instance' => JsonFormatter::class,
                'json_encode' => \JSON_ERROR_NONE,
            ]
        ]);
        $logger = new Logger([
            'channels' => [$channel]
        ]);

        $logger->alert('Syslog alert message');
        $handler = $channel->getHandler();

        $this->assertInstanceOf(SyslogHandler::class, $handler);
        $this->assertTrue($channel->isDelivered());
    }

    /**
     * @testdox Should throw an Exception if failed to open a connection to system logger
     */
    public function testOpeningFailure()
    {
        $handler = new class() extends SyslogHandler {
            // Simulate the failure result
            public function open(): bool {
                return false;
            }
        };

        $channel = new Channel();
        $channel->setHandler($handler)->setFormatter(new JsonFormatter());

        $logger = new Logger([
            'channels' => [$channel]
        ]);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageRegExp('/Failed to open a connection.*/i');

        $logger->alert('Syslog alert message');
    }
}
