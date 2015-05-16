<?php

namespace AndyTruong\Bible\Command;

use AndyTruong\Bible\Helper\ImportHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Use `progressbar` when have free time
 *  http://symfony.com/doc/current/components/console/helpers/progressbar.html
 */
class ImportCommand extends Command
{

    use ContainerAwareTrait;

    protected function configure()
    {
        $this
            ->setName('bible:import')
            ->setDescription('Start importing…')
            ->addOption('restart', null, InputOption::VALUE_OPTIONAL, 'Restart importing process', false)
            ->addOption('limit', null, InputOption::VALUE_OPTIONAL, 'Limit importing commands', 100);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = (int) $input->getOption('limit');

        $helper = new ImportHelper($this->container);

        if ($restart = (bool) $input->getOption('restart')) {
            return $helper->generateQueueItems();
        }

        for ($i = $limit; $i >= 0; $i--) {
            if (!$queue_item = $helper->getQueueItem()) {
                continue;
            }

            $output->writeln('Importing ' . $queue_item->getDescription(), OutputInterface::VERBOSITY_NORMAL);
            $helper->processQueueItem($queue_item);
        }
    }

}
