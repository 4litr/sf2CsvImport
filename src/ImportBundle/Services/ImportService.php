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
use Ddeboer\DataImport\Writer;
use Ddeboer\DataImport\Filter;



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

    public function importProductsWorkflow(Reader $reader, $test = false) {
        $workflow = new StepAggregator($reader);


    }

    //truncating table...
    public function truncateTable() {
        $this->entityManager->createQuery('DELETE FROM ImportBundle:ProductItem')->execute();
    }

}