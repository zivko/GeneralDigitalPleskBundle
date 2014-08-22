<?php

/*
 * This file is part of the GeneralDigital\PleskBundle
*
* (c) Zivko Sudarski <zivko@generaldigital.co.nz>
*
* This source file is subject to the MIT license that is bundled
* with this source code in the file LICENSE.
*/

namespace GeneralDigital\PleskBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Command for list ftp users
 *
 * @author Zivko Sudarski <zivko@generaldigital.co.nz>
 */
class ListFTPUsersCommand extends ContainerAwareCommand
{
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::configure()
     */
    protected function configure()
    {
        $this
            ->setName('plesk:users:list')
            ->setDescription('List Plesk FTP users account')
            ->setHelp("The <info>plesk:users:list</info> command list a plesk FTP users account.");
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $api = $this->getContainer()->get('general_digital_plesk.api');
        $list = $api->listFTPUsers();

        $crawler = new Crawler();
        $crawler->addXmlContent($list['result']);

        $results = $crawler->filterXPath('//result');

        foreach ($results as $result) {

           $nameNode = $result->getElementsByTagName('name');
           $statusNode = $result->getElementsByTagName('status');
           $homeNode = $result->getElementsByTagName('home');

           $output->writeln(sprintf('Status <comment>%s</comment>', $statusNode->item(0)->nodeValue));

           if (!is_null($nameNode->item(0))) {
               $output->writeln(sprintf('Name <comment>%s</comment>', $nameNode->item(0)->nodeValue));
           }

           if (!is_null($homeNode->item(0))) {
               $output->writeln(sprintf('Home Directory <comment>%s</comment>', $homeNode->item(0)->nodeValue));
           }
        }

    }
}