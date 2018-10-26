<?php
/**
 * Created on 2018-10-24
 */


namespace Kod\Handlers;


/**
 * OutputHandler
 */
class OutputHandler extends StreamHandler
{
    protected $default = [
        'path' => 'php://output'
    ];
}
