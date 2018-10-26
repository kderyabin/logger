<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod;

trait OptionsTrait
{
    /**
     * Default options
     * @var array
     */
    protected $default = [];
    /**
     * Configurable options
     *
     * @var array
     */
    protected $options = [];

    /**
     * @param array $options
     */
    protected function setOptions(array $options = [])
    {
        if ($options) {
            $this->options = array_merge($this->options, $options);
        }
    }
    /**
     * @param $name
     * @param null $default
     * @return null
     */
    public function getOption($name, $default = null)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }

    /**
     * Get default option.
     *
     * @param string $name
     * @return mixed
     */
    public function getDefault($name)
    {
        return $this->default[$name];
    }
}
