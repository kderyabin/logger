<?php
/**
 * Created on 2018-10-19
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
