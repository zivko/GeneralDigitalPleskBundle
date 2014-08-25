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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Command for create Plesk Subdomain
 *
 * @author Zivko Sudarski <zivko@generaldigital.co.nz>
 */
class CreateSubdomainCommand extends ContainerAwareCommand
{
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::configure()
     */
    protected function configure()
    {
        $this
            ->setName('plesk:subdomain:create')
            ->setDescription('Create Plesk Subdomain')
            ->setDefinition(array(
                  new InputArgument('name', InputArgument::REQUIRED, 'subdomain name'),
              ))
            ->setHelp("The <info>plesk:subdomain:create</info> command create a plesk subdomain.");
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        $api = $this->getContainer()->get('general_digital_plesk.api');
        $response = $api->addSubdomain($name);

        $crawler = new Crawler();
        $crawler->addXmlContent($response['result']);

        $status = $crawler->filter('result > status')->text();

        if ($status == 'error') {
            $errorCode = $crawler->filter('result > errcode')->text();
            $errorText = $crawler->filter('result > errtext')->text();
            $output->writeln("<error>$errorCode</error>");
            $output->writeln("<error>$errorText</error>");
        } else {
            $output->writeln(sprintf('<info>Created Subdomain</info> <comment>%s</comment>', $name.'.'.$api->getHost()));
        }
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::interact()
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('name')) {
          $name = $this->getHelper('dialog')->askAndValidate(
              $output,
              'Please choose a Subdomain name:',
              function ($name) {
                if (empty($name)) {
                     throw new \Exception('Subdomain name can not be empty');
                }

              return $name;

              }
          );
          $input->setArgument('name', $name);
        }
    }
}
