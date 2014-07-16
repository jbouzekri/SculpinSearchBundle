<?php

namespace Jb\Bundle\SearchBundle\Command;

use Sculpin\Bundle\SculpinBundle\Command\GenerateCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * SearchGenerateCommand
 *
 * @author Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 */
class SearchGenerateCommand extends GenerateCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $prefix = $this->isStandaloneSculpin() ? '' : 'sculpin:';

        parent::configure();

        $this
            ->setName($prefix.'generate-search')
            ->setDescription('Generate a site from source and control indexation.')
            ->addOption('no-index', null, InputOption::VALUE_NONE, 'Disable indexation when generating the site.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $indexEnabled =
            $input->getOption('no-index') ? false : $this->getContainer()->getParameter('jb_sculpin.search.enabled');

        $this
            ->getContainer()
            ->get('jb_sculpin.search.indexation.listener')
            ->setEnabled($indexEnabled);

        return parent::execute($input, $output);
    }
}
