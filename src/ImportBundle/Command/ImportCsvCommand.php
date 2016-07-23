<?php
namespace ImportBundle\Command;

use ImportBundle\Factory\ImportFactory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
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
            ->addOption(
                'test_run',
                null,
                InputOption::VALUE_NONE,
                'Initiates test run without database inserts'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = date('d-m-Y(G:i:s)');
        $filePath = $input->getArgument('file_path');
        $output->writeln("<info>Started: {$now}</info>");

        //switching on import services...
        $importService = $this->getContainer()->get('import.service');

//        try {
//            $reader = ImportFactory::getReader($fileFormat, $filePath);
//        } catch (FileNotFoundException $ex) {
//            $output->writeln("<error>{$ex->getMessage()}</error>");
//            return;
//        }

        $importService->import($filePath);

        $output->writeln('---');
        $output->writeln('Finished!!!');
    }
}
