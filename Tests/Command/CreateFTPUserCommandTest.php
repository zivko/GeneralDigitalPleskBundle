<?php

/*
 * This file is part of the GeneralDigital\PleskBundle
*
* (c) Zivko Sudarski <zivko@generaldigital.co.nz>
*
* This source file is subject to the MIT license that is bundled
* with this source code in the file LICENSE.
*/

namespace GeneralDigital\PleskBundle\Tests\Command;

use GeneralDigital\PleskBundle\Command\CreateFTPUserCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Test Command for create Plesk FTP user
 *
 * @author Zivko Sudarski <zivko@generaldigital.co.nz>
 */
class CreateFTPUserCommandTest extends KernelTestCase
{

    /**
     * SetUp kernel
     */
    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
    }

    /**
     * Test Plesk Create FTP User command - password length
     *
     * @covers GeneralDigital\PleskBundle\Command\CreateFTPUserCommand::execute
     */
    public function testPasswordlength()
    {
        $application = new Application(static::$kernel);
        $application->add(new CreateFTPUserCommand());
        $application->setAutoExit(false);

        $command = $application->find('plesk:user:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array('command' => $command->getName(), 'username' => 'zika', 'password' => 'sud'));

       $this->assertRegExp('/The password length should be from 5 to 14 characters in length/', $commandTester->getDisplay());

    }

    /**
     * Test Plesk Create FTP User command - password complexity
     *
     * @covers GeneralDigital\PleskBundle\Command\CreateFTPUserCommand::execute
     */
    public function testPasswordoComplexity()
    {
        $application = new Application(static::$kernel);
        $application->add(new CreateFTPUserCommand());
        $application->setAutoExit(false);

        $command = $application->find('plesk:user:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array('command' => $command->getName(), 'username' => 'zika', 'password' => '00iiuu'));

        $this->assertRegExp('/Your password is not complex enough/', $commandTester->getDisplay());
    }

}
