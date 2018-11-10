<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Tests;

use Kod\Channel;
use Kod\Formatters\TextFormatter;
use Kod\Logger;
use Kod\Message;
use Kod\Formatters\JsonFormatter;
use Kod\Handlers\StreamHandler;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class MessageTest extends TestCase
{
    /**
     * @testdox Test Message creation from configuration and logged data
     */
    public function testInitialization()
    {
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $message = new Message([
            // declare and initialize fields
            'fields' => [
                'login' => '',
                'session' => '',
                'ip' => $_SERVER['REMOTE_ADDR'],
                'timezone' => ''
            ],
            'dates' => [
                'timezone' => 'T'
            ],
            'setters' => [
                // encrypt login in logs
                'login' => function ($current) {
                    return $current ? md5($current) : $current;
                },
            ],
            'filters' => [
                // do not log empty values
                function (array $fields) {
                    return array_filter($fields, function ($value) {
                        return !empty($value);
                    });
                }
            ]
        ]);
        $log = 'my first message';
        $login = 'account@domain.tld';
        $fields = $message->process(LOG_DEBUG, LogLevel::DEBUG, $log, [
            'login' => $login,
        ]);

        $this->assertArrayHasKey('message', $fields);
        $this->assertArrayHasKey('datetime', $fields);
        $this->assertArrayHasKey('level', $fields);
        $this->assertArrayHasKey('login', $fields);
        $this->assertArrayHasKey('ip', $fields);
        $this->assertArrayHasKey('timezone', $fields);
        $this->assertEquals($log, $fields['message']);
        $this->assertEquals(LogLevel::DEBUG, $fields['level']);
        // check filters are applied
        $this->assertArrayNotHasKey('session', $fields);
        // check setters are applied
        $this->assertEquals(md5($login), $fields['login']);
    }

    /**
     * @testdox Should handle a non string message
     */
    public function testNonStringMessage()
    {
        $message = new Message();
        $data = ['key' => 'value'];
        $fields = $message->process(LOG_DEBUG, LogLevel::DEBUG, $data);

        $this->assertContains('key', $fields['message']);
        $this->assertContains('value', $fields['message']);
    }
    /**
     * @testdox Should handle an exception key in context
     */
    public function testExceptionContext()
    {
        $message = new Message();

        $exception = new \Exception('My exception');
        $data = ['key' => 'value'];
        $fields = $message->process(LOG_DEBUG, LogLevel::DEBUG, $data, [
            'exception' => $exception,
        ]);

        $this->assertEquals((string)$exception, $fields['exception']);
        $this->assertContains('key', $fields['message']);
        $this->assertContains('value', $fields['message']);
    }
}
