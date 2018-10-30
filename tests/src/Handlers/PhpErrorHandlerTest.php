<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Tests\Handlers;

use Kod\Channel;
use Kod\Formatters\LinePatternFormatter;
use Kod\Handlers\PhpErrorHandler;
use Kod\Logger;
use PHPUnit\Framework\TestCase;

class PhpErrorHandlerTest extends TestCase
{
    protected static $logFile = TEST_DIR . '/var/php_error_handler.log';
    protected static $previousLogSetting = '';

    /**
     * Create temporary log file
     */
    public static function setUpBeforeClass()
    {
        static::$previousLogSetting = ini_get('error_log');
        $tmpDir = TEST_DIR . '/var';
        if(!is_dir(TEST_DIR. '/var')){
            mkdir($tmpDir);
        }
        if(!is_file(static::$logFile)) {
            touch(static::$logFile);
        }
        ini_set('error_log', static::$logFile);
    }

    /**
     * Destroy temporary log file
     */
    public static function tearDownAfterClass()
    {
        unlink(static::$logFile);
        rmdir( TEST_DIR . '/var');
        ini_set('error_log', static::$previousLogSetting);
    }

    /**
     * @testdox Should log into a destination defined in php ini 'error_log' setting.
     */
    public function testWriteWithPhpErrorHandler()
    {
        $channel = new Channel();
        $channel->setHandler(new PhpErrorHandler())
            ->setFormatter(new LinePatternFormatter(['end_of_log' => '']));
        $logger = new Logger([
            'channels' => [$channel]
        ]);
        $msg = 'PhpErrorHandler message';
        $logger->critical($msg);
        $content = file_get_contents(static::$logFile);

        $this->assertTrue($channel->isDelivered());
        $this->assertContains($msg, $content);
    }
}
