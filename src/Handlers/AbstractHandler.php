<?php
/**
 * Created on 2018-10-17
 */


namespace Kod\Handlers;

use Kod\PriorityLevelTrait;
use Kod\OptionsTrait;

/**
 * Formatters
 */
abstract class AbstractHandler
{
    use OptionsTrait;

    /**
     * AbstractHandler constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * @param string $log
     * @return bool
     */
    abstract public function handle(string $log);
}
