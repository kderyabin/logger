<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
