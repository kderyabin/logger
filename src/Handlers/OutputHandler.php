<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Handlers;

/**
 * Class OutputHandler
 * @package Kod\Handlers
 */
class OutputHandler extends StreamHandler
{
    protected $options = [
        'path' => 'php://output'
    ];
}
