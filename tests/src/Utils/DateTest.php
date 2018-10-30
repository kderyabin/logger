<?php
/**
 * Copyright (c) 2018 Konstantin Deryabin
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kod\Tests\Utils;

use DateTimeZone;
use Kod\Utils\Date;
use PHPUnit\Framework\TestCase;

class DateTest extends TestCase
{
    /**
     * @testdox Should take date.timezone from php.ini for date creation
     */
    public function testShouldTakeDateTimezoneFromPhpIni()
    {
        $utc = \DateTime::createFromFormat('U.u', microtime(true))->format(DATE_RFC3339_EXTENDED);
        ini_set('date.timezone', 'Europe/Paris'); // +0100
        // local date
        $local = Date::getNow(DATE_RFC3339_EXTENDED);

        $utcParts = date_parse($utc);
        $localParts = date_parse($local);
        $this->assertEquals(1, $localParts['hour'] - $utcParts['hour']);
    }
}
