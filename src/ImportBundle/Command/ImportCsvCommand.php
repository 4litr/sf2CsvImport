<?php
namespace ImportBundle\Command;

use Ddeboer\DataImport\Exception\ReaderException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Ddeboer\DataImport\Exception\ValidationException;
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
        $start = date('d-m-Y (G:i:s)');
        $filePath = $input->getArgument('file_path');
        $testRun = $input->getOption('test_run');
        if (!$testRun) {
            $output->writeln('');
            $output->writeln('<info>Started: ' . $start . '</info>');
        } else {
            $output->writeln('<fg=red>[TEST_MODE_ENABLED]</><info>Started: ' . $start . '</info>');
        }

        //switching on import service...
        $importService = $this->getContainer()->get('import.service');

        try {
            $importResult = $importService->startImport($filePath, $testRun);
        } catch (ReaderException $ex) {
            $output->writeln('');
            $output->writeln('<fg=red;bg=black>' . $ex->getMessage() . '</>');
            return;
        } catch (FileNotFoundException $ex) {
            $output->writeln('');
            $output->writeln('<fg=red;bg=black>'. $ex->getMessage() .'</>');
            return;
        }

        $end = date('d-m-Y (G:i:s)');
        $errors = $importResult->getExceptions();
        $dateEnd = $importResult->getEndTime();
        $fileParsingErrors = $importResult->getErrors();
        $errorsAmt = $importResult->getCountErrors();
        $output->writeln('');
        $output->writeln('Import has been finished ' . $end);

        if ($errorsAmt) {
            $output->writeln('');
            $output->writeln('<fg=red;bg=white>Warning!!! Source Import File Contains Errors:</>');
            $output->writeln('Total Errors Amount:<error>' . $errorsAmt . '</error>');

            foreach ($errors as $error) {
                if ($error instanceof ValidationException) {
                    $violations = $error->getViolations();
                    $aErrors = [];

                    foreach ($violations as $violation) {
                        $aErrors[] = $violation->getMessage();
                        $productRoot = $violation->getRoot();
                    }

                    $output->writeln('<error>Errors: ' . implode(', ', $aErrors) . ' - productCode:[' . $productRoot['productCode'] . ']</error>');
                } else {
                    $output->writeln('<error>Error: ' . $error->getMessage() . '</error>');
                }
            }
            if ($fileParsingErrors) {
                foreach ($fileParsingErrors as $parseError) {
                    $output->writeln('<error>Parse Error line: ' . $parseError . '</error>');
                }
            }
                $output->writeln('<fg=red;bg=white>' . $dateEnd . ' Validated items: ' . $importResult->getSuccessCount() . ', Failed items: ' . $errorsAmt . '</>');
        } else {
            if($testRun) {
                $output->writeln($dateEnd . ' <fg=black;bg=green>File data has been successfully parsed!!!</>');
            } else {
                $output->writeln($dateEnd . ' <fg=black;bg=green>File data has been successfully imported!!!</>');
            }

        }

    }
}
