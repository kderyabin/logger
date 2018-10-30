<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Tests\Formatters;

use Kod\Formatters\LinePatternFormatter;
use Kod\Formatters\TextFormatter;
use Kod\Handlers\OutputHandler;
use Kod\Logger;
use Kod\Message;
use PHPUnit\Framework\TestCase;

class TextFormatterTest extends TestCase
{
    /**
     * @testdox Should log all message fields
     */
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
            'args' => ['var1' => 100, 'var2' => new \stdClass(),],
            'null' => null,
            'bool' => true,
        ]);
        $log = ob_get_clean();

        // match fields
        $this->assertNotFalse(preg_match_all($regex, $log, $logFields));
        $this->assertEquals(count($fields), count($logFields[0]));
        // null representation
        $this->assertContains('NULL', $log);
        // boolean representation
        $this->assertContains('true', $log);
    }
}
