<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Handlers;

/**
 * OutputHandler
 */
class StdoutHandler extends StreamHandler
{
    protected $default = [
        'path' => 'php://stdout'
    ];
}
