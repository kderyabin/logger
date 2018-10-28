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
use Kod\Handlers\StreamHandler;
use Kod\Logger;
use Kod\LoggerFactory;
use PHPUnit\Framework\TestCase;

class StreamHandlerTest extends TestCase
{
    /**
     * @testdox Should throw an Exception if failed to open a destination
     */
    public function testOpeningFailure()
    {
        $handler = new class() extends StreamHandler {
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
        $this->expectExceptionMessageRegExp('/Failed to open a resource.*/i');

        $logger->alert('Syslog alert message');
    }

    /**
     * @testdox Should use an opened resource as a destination
     */
    public function testSettingResourceAsPath()
    {
        $channel = new Channel();
        $stream = fopen('php://memory', 'a');
        $handler = new StreamHandler([
            'path' => $stream
        ]);
        $channel->setHandler($handler)->setFormatter(new JsonFormatter());

        $logger = new Logger([
            'channels' => [$channel]
        ]);
        $msg = 'Alert message';
        $logger->alert($msg);
        $handler = $channel->getHandler();
        // get logged message
        rewind($stream);
        $log = fread($stream, 4096);

        $this->assertTrue($channel->isDelivered());
        $this->assertContains($msg, $log);
    }
}
