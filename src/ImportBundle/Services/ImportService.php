<?php

/**
 * Created by PhpStorm.
 * User: Sergey Folitar
 * Date: 20.7.16
 * Time: 23.45
 */
namespace ImportBundle\Services;

use Ddeboer\DataImport\Workflow\StepAggregator;
use Doctrine\ORM\EntityManager; //for truncate table service
use Ddeboer\DataImport\Reader as Reader;
use Ddeboer\DataImport\Writer\ConsoleTableWriter;
use Symfony\Component\Console\Helper\Table;
use Ddeboer\DataImport\Writer\DoctrineWriter;
use Ddeboer\DataImport\Filter;
use Ddeboer\DataImport\Writer\ConsoleProgressWriter;
use Symfony\Component\Console\Output\ConsoleOutput;



class ImportService
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function importProductsWorkflow(Reader $reader, $output, $test = false) {
        $workflow = new StepAggregator($reader);
        $progressWriter = new ConsoleProgressWriter($output,$reader);
        $workflow->addWriter($progressWriter);


        if ($test) {
            $table = new Table($output);
            //$table->setStyle('compact');
            $workflow->addWriter(new ConsoleTableWriter($output, $table));
        } else {
            $doctrineWriter = new DoctrineWriter($this->entityManager, 'ImportBundle:ProductItem');
            $workflow->addWriter($doctrineWriter);
        }

        $workflow->process();
    }

    //truncating table...
    public function truncateTable() {
        $this->entityManager->clear('ImportBundle:ProductItem');
    }

}