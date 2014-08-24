<?php

/*
 * This file is part of the GeneralDigital\PleskBundle
*
* (c) Zivko Sudarski <zivko@generaldigital.co.nz>
*
* This source file is subject to the MIT license that is bundled
* with this source code in the file LICENSE.
*/

namespace GeneralDigital\PleskBundle\Tests\Services;

use GeneralDigital\PleskBundle\Services\Plesk;

class PleskTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test Plesk constructor
     *
     * @covers GeneralDigital\PleskBundle\Services\Plesk::__construct
     */
    public function testPleskConstructor()
    {
        $plesk = new Plesk('host.com', 'zivko', 'sudars123');

        $this->assertEquals('host.com', $plesk->getHost());
        $this->assertEquals('zivko', $plesk->getUser());
        $this->assertEquals('sudars123', $plesk->getPassword());
    }
}
