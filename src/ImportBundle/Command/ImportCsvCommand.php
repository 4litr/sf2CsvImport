<?php

namespace ImportBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

class ImportCsvCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('import:csv')
            ->setDescription('Imports data from CSV files to mysql database')
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'Enter file path: '
            )
            ->addOption(
                'test',
                null,
                InputOption::VALUE_NONE,
                'Enables test mode'
            )
            ->addOption(
                'truncate',
                null,
                InputOption::VALUE_NONE,
                'Truncates Mysql table'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = $input->getArgument('file');
        $size = 10;
        $progress = new ProgressBar($output, $size);
        $progress->start();

        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
        if ($input->getOption('test')) {
            echo "test";
        }

        $output->writeln('Command result.');
    }


    protected function import(InputInterface $input, OutputInterface $output)
    {
        // Getting php array of data from CSV
        $data = $this->get($input, $output);

        // Getting doctrine manager
        $em = $this->getContainer()->get('doctrine')->getManager();
        // Turning off doctrine default logs queries for saving memory
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        // Define the size of record, the frequency for persisting the data and the current index of records
        $size = count($data);
        $batchSize = 20;
        $i = 1;

        // Starting progress
        $progress = new ProgressBar($output, $size);
        $progress->start();

        // Processing on each row of data
        foreach($data as $row) {

            $user = $em->getRepository('AcmeAcmeBundle:User')
                ->findOneByEmail($row['email']);

            // If the user doest not exist we create one
            if(!is_object($user)){
                $user = new User();
                $user->setEmail($row['email']);
            }

            // Updating info
            $user->setLastName($row['lastname']);
            $user->setFirstName($row['firstname']);

            // Do stuff here !

            // Persisting the current user
            $em->persist($user);

            // Each 20 users persisted we flush everything
            if (($i % $batchSize) === 0) {

                $em->flush();
                // Detaches all objects from Doctrine for memory save
                $em->clear();

                // Advancing for progress display on console
                $progress->advance($batchSize);

                $now = new \DateTime();
                $output->writeln(' of users imported ... | ' . $now->format('d-m-Y G:i:s'));

            }

            $i++;

        }

        // Flushing and clear data on queue
        $em->flush();
        $em->clear();

        // Ending the progress bar process
        $progress->finish();
    }

    protected function get(InputInterface $input, OutputInterface $output)
    {
        // Getting the CSV from filesystem
        $fileName = 'web/uploads/import/users.csv';

        // Using service for converting CSV to PHP Array
        $converter = $this->getContainer()->get('import.csv');
        $data = $converter->convert($fileName, ';');

        return $data;
    }

}
