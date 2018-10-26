<?php
/**
 * Created on 2018-10-24
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
