<?php
/**
 * Created by IntelliJ IDEA.
 * User: Konstantin
 * Date: 21/10/2018
 * Time: 11:16
 */

namespace Kod\Tests\Mocks;


use Kod\Handlers\AbstractHandler;

class TestHandler extends AbstractHandler
{
    public $log = '';

    public function handle(string $log)
    {
        $this->log= $log;
        return !empty($log);
    }
}