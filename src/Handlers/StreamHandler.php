<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Handlers;

use Kod\OptionsTrait;

/**
 * Class StreamHandler
 * @package Kod\Handlers
 */
class StreamHandler extends AbstractGateHandler
{
    protected $default = [
        // logs destination
        'path' => 'php://stderr',
        // resource created with stream_context_create() function
        'context' => null,
    ];
    /**
     * Resource where logs will be written.
     *
     * @var resource
     */
    protected $resource;

    public function __destruct()
    {
        $this->close();
    }

    /**
     * Open a log destination.
     * @return bool
     */
    public function open(): bool
    {
        $path = $this->getOptionOrDefault('path');
        if (is_resource($path)) {
            $this->resource = $path;
            return true;
        }

        $context = $this->getOptionOrDefault('context');
        if(is_resource($context)){
            $this->resource = @fopen($path, 'ab', false, $context);
        } else {
            $this->resource = @fopen($path, 'ab');
        }

        return is_resource($this->resource);
    }

    /**
     * Close a resource
     */
    public function close(): void
    {
        if (is_resource($this->resource)) {
            fclose($this->resource);
        }
    }

    /**
     * Write a log message.
     *
     * @param string $level
     * @param string $log
     * @return bool
     * @throws \Exception
     */
    public function handle(string $level, string $log): bool
    {
        if (!is_resource($this->resource)) {
            $status = $this->open();
            if (!$status) {
                throw new \Exception(
                    sprintf('Failed to open a resource "%s"', $this->getOptionOrDefault('path'))
                );
            }
        }
        return fwrite($this->resource, $log) !== false;
    }
}
