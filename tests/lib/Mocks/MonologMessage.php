<?php
/**
 * Created on 2018-10-22
 */


namespace Kod\Tests\Mocks;

use Kod\Message;


/**
 * MonologMessage
 */
class MonologMessage extends Message
{
    public $fields = [
        'message' => '',
        'level' => 0,
        'level_name' => '',
        'context' => [],
        'channel' => '',
        'datetime' => '',
        'extra' => [],
    ];

    protected function getData($code, $level, $message, $context = [])
    {
        return array_merge(
            $this->fields,
            [
                'message' => $message,
                'level' => $code,
                'level_name' => $level,
                'context' => $context
            ]
        );
    }
}
