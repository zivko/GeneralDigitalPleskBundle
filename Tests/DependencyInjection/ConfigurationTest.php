<?php

/*
 * This file is part of the GeneralDigital\PleskBundle
*
* (c) Zivko Sudarski <zivko@generaldigital.co.nz>
*
* This source file is subject to the MIT license that is bundled
* with this source code in the file LICENSE.
*/

namespace GeneralDigital\PleskBundle\Tests\DependencyInjection;

use GeneralDigital\PleskBundle\DependencyInjection\Configuration;
use Symfony\Component\Yaml\Parser;

/**
 * Test the configuration tree
 *
 * @author Zivko Sudarski <zivko@generaldigital.co.nz>
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test config tree builder
     */
    public function testGetConfigTreeBuilder()
    {
        $config = new Configuration();
        $tree = $config->getConfigTreeBuilder()->buildTree();
        $this->assertEquals('general_digital_plesk', $tree->getName());
        $children = $tree->getChildren();

        $this->assertEquals(3, count($children));
        $this->assertArrayHasKey('host', $children);
        $this->assertArrayHasKey('user', $children);
        $this->assertArrayHasKey('password', $children);
    }

    /**
     * Test yaml config service file
     */
    public function testYamlFile()
    {
        $yaml = new Parser();
        $config = $yaml->parse(file_get_contents(realpath(__DIR__ . '/../../Resources/config/services.yml')));
        //validate config
        $this->assertArrayHasKey('parameters', $config);
        $this->assertArrayHasKey('services', $config);
        $this->assertArrayHasKey('general_digital_plesk.api', $config['services']);
        $this->assertArrayHasKey('class', $config['services']['general_digital_plesk.api']);
    }
}
