<?php
/**
 * Created on 2018-10-16
 */

namespace Kod\Tests;

use Kod\Channel;
use Kod\Formatter\TextFormatter;
use Kod\Logger;
use Kod\Message;
use Kod\Formatter\JsonFormatter;
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
}
