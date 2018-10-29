<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Handlers;

/**
 * Class PhpErrorHandler is a wrapper for error_log() function.
 * Sends a message to the web server's error log or to a file according to php settings.
 *
 * @package Kod\Handlers
 * @see http://php.net/manual/function.error-log.php
 * @see http://php.net/manual/errorfunc.configuration.php#ini.error-log
 */
class PhpErrorHandler extends AbstractHandler
{
    /**
     * @param string $level
     * @param string $log
     * @return bool
     */
    public function handle(string $level, string $log): bool
    {
        return error_log($log);
    }
}
