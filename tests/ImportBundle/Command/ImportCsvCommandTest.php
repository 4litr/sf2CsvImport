<?php
/**
 * Created by PhpStorm.
 * User: litr4
 * Date: 24.7.16
 * Time: 20.41
 */

namespace Tests\ImportBundle\Command;

use ImportBundle\Command\ImportCsvCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;

/**
 * Class ImportCsvCommandTest
 * @package Tests\ImportBundle\Command
 */
class ImportCsvCommandTest extends KernelTestCase
{
    private $commandTester;

    public function setUp()
    {
        $kernel = $this->createKernel();
        $kernel->boot();
        $app = new Application($kernel);
        $app->add(new ImportCsvCommand());
        $command = $app->find('import:start');
        $this->commandTester = new CommandTester($command);
    }

    /**
     * Invalid File format
     */
    public function testExecuteWithBadFormat()
    {
        $this->commandTester->execute(
            array(
                'file_path' => __DIR__ . '/../Fixtures/stock.csvs',
                '--test_run'  => true,
            )
        );

        $this->assertRegexp('/csvs format not found/', $this->commandTester->getDisplay());

    }

    /**
     * Testing how command execute with invalid file
     */
    public function testExecuteWithBadFile()
    {

        $this->commandTester->execute(
            array(
                'file_path' => __DIR__. '/../Fixtures/stockd.csv',
                '--test_run'  => true,
            )
        );
//        $this->assertEquals('File could not be found.' . PHP_EOL, $this->commandTester->getDisplay());
        $this->assertRegexp('/File could not be found./', $this->commandTester->getDisplay());

    }

    /**
     * Testing how command execute with valid format and file
     */
    public function testExecute()
    {
        $this->commandTester->execute(
            array(
                'file_path' => __DIR__. '/../Fixtures/stock.csv',
                '--test_run'  => true,
            )
        );

        $this->assertRegexp('/Validated items/', $this->commandTester->getDisplay());
    }
}
