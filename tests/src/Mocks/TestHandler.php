<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Tests\Mocks;


use Kod\Handlers\AbstractHandler;

class TestHandler extends AbstractHandler
{
    public $log = '';

    public function handle(string $level, string $log): bool
    {
        $this->log= $log;
        return !empty($log);
    }
}
