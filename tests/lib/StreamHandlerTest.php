<?php
/**
 * Created on 2018-10-25
 */


namespace Kod\Tests;


use Kod\Formatter\TextFormatter;
use Kod\Handlers\StreamHandler;
use Kod\Logger;
use PHPUnit\Framework\TestCase;

class StreamHandlerTest extends TestCase
{
    public function testMultipleLoggerWriting()
    {
        $logger = new Logger([
            'channels' => [
                [
                    'handler' => [
                        'path' => 'test.log'
                    ],
                    'formatter' => [
                        'instance' => TextFormatter::class,
                    ]
                ]
            ]
        ]);
        $partner = new Logger([
            'message' => [
                'fields' => [
                    'partner' => 'PARTNER'
                ]
            ],
            'channels' => [
                [
                    'handler' => [
                        'path' => 'test.log'
                    ],
                    'formatter' => [
                        'instance' => TextFormatter::class
                    ]
                ]
            ]
        ]);
        $loggerMsg = 'Logger message ';
        $partnerMsg = 'Partner message ';

        $logger->debug($loggerMsg . 1);
        $logger->debug($loggerMsg . 2);
        $logger->debug($loggerMsg . 3);
        $logger->debug($loggerMsg. 4);
        $logger->debug($loggerMsg. 5);
        $logger->debug($loggerMsg. 6);
        $logger->debug($loggerMsg. 7);
        $logger->debug($loggerMsg. 8);
        $logger->debug($loggerMsg. 9);
        $logger->debug($loggerMsg. 10);

        $partner->info($partnerMsg. 1);
        $partner->info($partnerMsg. 2);
        $partner->info($partnerMsg. 3);
        $partner->info($partnerMsg. 4);

        $logger->debug($loggerMsg. 11);
        $logger->debug($loggerMsg. 12);
        $logger->debug($loggerMsg. 13);

    }
}
