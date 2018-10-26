<?php
/**
 * Created by IntelliJ IDEA.
 * User: Konstantin
 * Date: 20/10/2018
 * Time: 00:11
 */

namespace Kod;

trait PriorityLevelTrait
{
    /**
     * Minimal log level
     * @var int
     */
    protected $priorityMin;
    /**
     * Maximal log level
     * @var int
     */
    protected $priorityMax;

    /**
     * @param int $priority
     * @return bool true if passed in parameter log level can be logged otherwise false
     */
    public function canLog(int $priority)
    {
        // level filter is not set -> we can log
        if ($this->priorityMin === null && $this->priorityMax === null) {
            return true;
        } elseif ($this->priorityMax !== null && $this->priorityMin !== null) {
            return $priority >= $this->priorityMin && $priority <= $this->priorityMax;
        } elseif ($this->priorityMax === null) {
            return $priority >= $this->priorityMin;
        }
        return $priority <= $this->priorityMax;
    }
}
