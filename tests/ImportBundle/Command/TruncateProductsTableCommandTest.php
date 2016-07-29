<?php
/**
 * Created by PhpStorm.
 * User: Sergey Folitar
 * Date: 7/29/16
 * Time: 3:02 PM
 */

namespace Tests\ImportBundle\Command;

use ImportBundle\Command\TruncateProductsTableCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;

class TruncateProductsTableCommandTest extends KernelTestCase
{
    /**
     * Class TruncateProductsTableCommand
     * @package Tests\ImportBundle\Command
     */
    private $commandTester;

    private $command;

    public function setUp()
    {
        $kernel = $this->createKernel();
        $kernel->boot();
        $app = new Application($kernel);
        $app->add(new TruncateProductsTableCommand());
        $this->command = $app->find('import:truncate');
        $this->commandTester = new CommandTester($this->command);
    }

    public function testExecuteNotConfirmed()
    {
        //var_dump($this->commandTester->getDisplay());die;
        $dialog = $this->command->getHelper('question');

        // Equals to a user inputting "Test" and hitting ENTER
        // If you need to enter a confirmation, "yes\n" will work
        $dialog->setInputStream($this->getInputStream("n\n"));
        $this->commandTester->execute(array());
        $this->assertRegExp('/This will truncate your product items table. Process anyway?[y\/n]/', $this->commandTester->getDisplay());
        $this->assertRegExp('/Truncate has been cancelled/', $this->commandTester->getDisplay());
    }

    public function testExecuteConfirmed()
    {
        //var_dump($this->commandTester->getDisplay());die;
        $dialog = $this->command->getHelper('question');

        // Equals to a user inputting "Test" and hitting ENTER
        // If you need to enter a confirmation, "yes\n" will work
        $dialog->setInputStream($this->getInputStream("yes\n"));
        $this->commandTester->execute(array());
        $this->assertRegExp('/This will truncate your product items table. Process anyway?[y\/n]/', $this->commandTester->getDisplay());
        $this->assertRegExp('/truncating products table.../', $this->commandTester->getDisplay());
        $this->assertRegExp('/Done!/', $this->commandTester->getDisplay());
    }

    protected function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);
        return $stream;
    }


}
