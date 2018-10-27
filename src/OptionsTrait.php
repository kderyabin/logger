<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod;

/**
 * Trait OptionsTrait
 * @package Kod
 */
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
     * Get an option value by name or return some arbitrary value.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getOption($name, $default = null)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }

    /**
     * Get a default option value by name.
     *
     * @param string $name
     * @return mixed
     */
    public function getDefault($name)
    {
        return $this->default[$name];
    }

    /**
     * Get an option value by name or a default option with the same name.
     *
     * @param $name
     * @return mixed
     */
    public function getOptionOrDefault($name)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $this->getDefault($name);
    }
}
