<?php

namespace AndyTruong\Bible\Command;

use AndyTruong\App\Command;
use AndyTruong\Bible\Helper\ImportHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Use `progressbar` when have free time
 *  http://symfony.com/doc/current/components/console/helpers/progressbar.html
 */
class ImportCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('bible:import')
            ->setDescription('Strart importing…')
            ->addOption('restart', null, InputOption::VALUE_OPTIONAL, 'Restart importing process', false)
            ->addOption('limit', null, InputOption::VALUE_OPTIONAL, 'Limit importing commands', 100)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = (int) $input->getOption('limit');
        $helper = new ImportHelper($this->getApplication());

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
