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
class StreamHandler extends AbstractHandler
{
    protected $default = [
        'path' => 'php://stderr'
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
     * @throws \Exception
     */
    public function open()
    {
        $path = $this->getOption('path', $this->getDefault('path'));
        $this->resource = @fopen($path, 'ab');
        if (!is_resource($this->resource)) {
            throw new \Exception(
                sprintf('Failed to open a resource at "%s"', $path)
            );
        }
    }

    /**
     * Close a resource
     */
    public function close()
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
            $this->open();
        }
        return fwrite($this->resource, $log) !== false;
    }
}
