<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Formatters;

use Kod\Utils\Stringer;

/**
 * Class TextFormatter
 * @package Kod\AbstractFormatter
 */
class LinePatternFormatter extends TextFormatter
{
    protected $default = [
        // Formatting pattern.
        'format' => '{datetime} {level}({level_code}): {message}',
        'end_of_log' => PHP_EOL,
    ];

    /**
     * @param array $data
     * @return mixed|string
     */
    public function format(array $data)
    {
        $format = $this->getOptionOrDefault('format');
        $result = Stringer::interpolate($format, $data);
        $result = Stringer::removeEndLines($result) . $this->getOptionOrDefault('end_of_log');

        return $result;
    }
}
