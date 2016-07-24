<?php
namespace ImportBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Ddeboer\DataImport\Exception\ValidationException;
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
        $start = date('d-m-Y (G:i:s)');
        $filePath = $input->getArgument('file_path');
        $testMode = $input->getOption('test_run');
        if (!$testMode) {
            $output->writeln("<info>Started: {$start}</info>");
        } else {
            $output->writeln("<fg=red>[TEST_MODE_ENABLED]</><info>Started: {$start}</info>");
        }

        //switching on import services...
        $importService = $this->getContainer()->get('import.service');
        $importResult = $importService->startImport($filePath, $testMode);
        $end = date('d-m-Y (G:i:s)');
        $errors = $importResult->getExceptions();
        $dateEnd = $importResult->getEndTime();
        $fileParsingErrors = $importResult->getErrors();
        $errorsAmt = $importResult->getCountErrors();

        if ($errorsAmt) {
            $output->writeln("Import has been finished " . $end . " <error>Warning!!! Source Import File Contains Errors:</error>");
            $output->writeln("<info>Total Errors Amount:" . $errorsAmt . "</info>");

            foreach ($errors as $error) {
                if ($error instanceof ValidationException) {
                    $violations = $error->getViolations();
                    $lineNumber = $error->getLineNumber();
                    $aErrors = [];

                    foreach ($violations as $violation) {
                        $aErrors[] = $violation->getMessage();
                    }

                    $output->writeln('Errors: ' . implode(', ', $aErrors) . ' - row Nr\. ' . $lineNumber);
                } else {
                    $output->writeln('Error: ' . $error->getMessage());
                }
            }
            $output->writeln('<info>' . $dateEnd . '</info> Validated items: ' . $importResult->getSuccessCount() . ', Failed items: ' . $errorsAmt);
        } else {
            $output->writeln($dateEnd . ' <fg=black;bg=green>File data has been successfully imported!!!</>');
        }

    }
}
