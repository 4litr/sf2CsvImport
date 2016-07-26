<?php

namespace ImportBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class TruncateProductsTableCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('import:truncate')
            ->setDescription('Purges all Products');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('This will truncate your product items table. Process anyway?<fg=yellow>[y/n]</>', false);
        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        $output->write('truncating products table...');
        $services = $this->getContainer()->get('truncate.service');
        $services->truncateTable();
        $output->writeln('');
        $output->writeln('Done!!!');
    }
}
