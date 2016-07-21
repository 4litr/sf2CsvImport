<?php

namespace ImportBundle\Command;

use ImportBundle\Factory\ImportFactory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Helper\ProgressBar;
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

        //switching on our services...
        $services = $this->getContainer()->get('import.csv');

        //check for Truncating Products table request
        if ($input->getOption('truncate'))
        {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion('This will truncate your product items table. Process anyway?', false);
            if (!$helper->ask($input, $output, $question)) {
                return;
            }

            //truncating products table...
            $services->truncateTable();
        }

        if ($input->getOption('test_run')) {
            echo "test=================";
        }


        try {
            $reader = ImportFactory::getReader($fileFormat, $filePath);
        } catch (FileNotFoundException $ex) {
            $output->writeln("<error>{$ex->getMessage()}</error>");
            return;
        }

        $progress = new ProgressBar($output);
        var_dump($reader->getFields());
        $progress->start();
        $services->importProductsWorkflow($reader, $input->getOption('test_run'));





        $progress->finish();
        $output->writeln('');
        $output->writeln('Command result.');
    }


//    protected function import(InputInterface $input, OutputInterface $output)
//    {
//        // Getting php array of data from CSV
//        $data = '';
//
//        // Getting doctrine manager
//        $em = $this->getContainer()->get('doctrine')->getManager();
//        // Turning off doctrine default logs queries for saving memory
//        $em->getConnection()->getConfiguration()->setSQLLogger(null);
//
//        // Define the size of record, the frequency for persisting the data and the current index of records
//        $size = count($data);
//        $batchSize = 20;
//        $i = 1;
//
//        // Starting progress
//        $progress = new ProgressBar($output, $size);
//        $progress->start();
//
//        // Processing on each row of data
//        foreach($data as $row) {
//
//            $user = $em->getRepository('AcmeAcmeBundle:User')
//                ->findOneByEmail($row['email']);
//
//            // If the user doest not exist we create one
//            if(!is_object($user)){
//                $user = new User();
//                $user->setEmail($row['email']);
//            }
//
//            // Updating info
//            $user->setLastName($row['lastname']);
//            $user->setFirstName($row['firstname']);
//
//            // Do stuff here !
//
//            // Persisting the current user
//            $em->persist($user);
//
//            // Each 20 users persisted we flush everything
//            if (($i % $batchSize) === 0) {
//
//                $em->flush();
//                // Detaches all objects from Doctrine for memory save
//                $em->clear();
//
//                // Advancing for progress display on console
//                $progress->advance($batchSize);
//
//                $now = new \DateTime();
//                $output->writeln(' of users imported ... | ' . $now->format('d-m-Y G:i:s'));
//
//            }
//
//            $i++;
//
//        }
//
//        // Flushing and clear data on queue
//        $em->flush();
//        $em->clear();
//
//        // Ending the progress bar process
//        $progress->finish();
//    }
}
