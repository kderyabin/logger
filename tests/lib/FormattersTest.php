<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Tests\Formmaters;

use Kod\Formatter\LinePatternFormatter;
use Kod\Formatter\TextFormatter;
use Kod\Handlers\OutputHandler;
use Kod\Logger;
use Kod\Message;
use PHPUnit\Framework\TestCase;

class FormattersTest extends TestCase
{
    public function testTextFormatter()
    {
        $endOfLog = PHP_EOL;
        $logger = new Logger([
            'channels' => [
                [
                    'handler' => [
                        'instance' => OutputHandler::class
                    ],
                    'formatter' => [
                        'instance' => TextFormatter::class,
                        'allow_line_breaks' => false,
                        'separator' => '|',
                        'start_of_log' => '',
                        'end_of_log' => $endOfLog,
                    ]
                ]
            ]
        ]);
        $message = new Message();
        $fields = array_merge(array_keys($message->getFields()), ['error', 'args']);
        $regex = sprintf('/\[(%s)\]/', implode('|', $fields));
        ob_start();
        $logger->info('Info message', [
            'error' => new \Exception('Info message'),
            'args' => ['var1' => 100, 'var2' => new \stdClass(),]
        ]);
        $log = ob_get_clean();

        $this->assertNotFalse(preg_match_all($regex, $log, $logFields));
        $this->assertEquals(count($fields), count($logFields[0]));
    }

    public function testLinePatternFormatter()
    {
        $endOfLog = PHP_EOL . '***' . PHP_EOL;
        $logger = new Logger([
            'channels' => [
                [
                    'handler' => [
                        'instance' => OutputHandler::class
                    ],
                    'formatter' => [
                        'instance' => LinePatternFormatter::class,
                        'end_of_log' => $endOfLog,
                    ]
                ]
            ]
        ]);

        $message = new Message();
        $fields = array_merge(array_keys($message->getFields()));
        ob_start();
        $logger->info('Info message', [
            'error' => new \Exception('Info message'),
            'args' => ['var1' => 100, 'var2' => new \stdClass(),]
        ]);
        $log = ob_get_clean();

        $this->assertNotContains('error', $log);
        $this->assertNotContains('args', $log);
        $this->assertNotContains('%', $log);
        $this->assertContains($endOfLog, $log);
        $this->assertRegExp('@[^\s]+ \w+\(\d+\): .+@', $log);
    }
}
