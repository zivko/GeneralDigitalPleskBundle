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
 * Command for delete FTP user
 *
 * @author Zivko Sudarski <zivko@generaldigital.co.nz>
 */
class DeleteFTPUserCommand extends ContainerAwareCommand
{
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::configure()
     */
    protected function configure()
    {
        $this
            ->setName('plesk:user:delete')
            ->setDescription('Delete Plesk FTP user account')
            ->setDefinition(array(
                new InputArgument('username', InputArgument::REQUIRED, 'FTP username'),
              ))
            ->setHelp("The <info>plesk:user:delete</info> command delete a plesk FTP user account.");
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');

        $api = $this->getContainer()->get('general_digital_plesk.api');
        $response = $api->deleteFTPUser($username);

        $crawler = new Crawler();
        $crawler->addXmlContent($response['result']);
        $status = $crawler->filter('result > status')->text();

        if ($status == 'error') {
            $errorCode = $crawler->filter('result > errcode')->text();
            $errorText = $crawler->filter('result > errtext')->text();
            $output->writeln("<error>$errorCode</error>");
            $output->writeln("<error>$errorText</error>");
        } else {
            $output->writeln(sprintf('<info>Deleted FTP user</info> <comment>%s</comment>', $username));
        }
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::interact()
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('username')) {
            $username = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a FTP username:',
                function($username){
                    if (empty($username)) {
                        throw new \Exception('Username can not be empty');
                    }

                 return $username;

                }
            );
           $input->setArgument('username', $username);
        }
    }

}