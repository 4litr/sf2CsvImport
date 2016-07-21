<?php

namespace ImportBundle\Command;

use ImportBundle\Factory\ImportFactory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;


class ImportCsvCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('import:start')
            ->setDescription('Imports data from files with defined formats (csv at the moment) to mysql database')
            ->addArgument(
                'file_path',
                InputArgument::REQUIRED,
                'Enter file path: '
            )
            ->addArgument(
                'file_format',
                InputArgument::OPTIONAL,
                'Enter file format(default:csv): ',
                'csv'
            )
            ->addOption(
                'test_run',
                null,
                InputOption::VALUE_NONE,
                'Initiates test run without database inserts'
            )
            ->addOption(
                'truncate',
                null,
                InputOption::VALUE_NONE,
                'Truncates Mysql table before import'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = date('d-m-Y(G:i:s)');
        $filePath = $input->getArgument('file_path');
        $fileFormat = $input->getArgument('file_format');
        $output->writeln("<info>Started: {$now}</info>");

        //switching on import services...
        $services = $this->getContainer()->get('import.csv');

        //check for Truncating Products table request
        if ($input->getOption('truncate')) {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion('This will truncate your product items table. Process anyway?<info>[y/n]</info>>', false);
            if (!$helper->ask($input, $output, $question)) {
                return;
            }

            //TODO: how to pass param
            $output->write('truncating products table...');
            $services->truncateTable();
        }

        try {
            $reader = ImportFactory::getReader($fileFormat, $filePath);
        } catch (FileNotFoundException $ex) {
            $output->writeln("<error>{$ex->getMessage()}</error>");
            return;
        }

        $services->importProductsWorkflow($reader, $output, $input->getOption('test_run'));

        $output->writeln('');
        $output->writeln('Finished!!!');
    }
}
