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
 * StreamHandler
 */
class StreamHandler extends AbstractHandler
{
    protected $default = [
        'path' => 'php://stderr'
    ];
    /**
     * AbstractHandler fir the
     * @var resource
     */
    protected $resource;

    public function __destruct()
    {
        $this->close();
    }

    /**
     * @throws \Exception
     */
    protected function open()
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
     * Close log resource
     */
    protected function close()
    {
        if (is_resource($this->resource)) {
            fclose($this->resource);
        }
    }

    /**
     * @param string $log
     * @return bool
     * @throws \Exception
     */
    public function handle(string $log)
    {
        if (!is_resource($this->resource)) {
            $this->open();
        }
        return fwrite($this->resource, $log) !== false;
    }
}
