<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
