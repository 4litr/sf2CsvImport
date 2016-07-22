<?php

/**
 * Created by PhpStorm.
 * User: Sergey Folitar
 * Date: 20.7.16
 * Time: 23.45
 */
namespace ImportBundle\Services;

use Ddeboer\DataImport\Step\FilterStep;
use Ddeboer\DataImport\Workflow\StepAggregator;
use Doctrine\ORM\EntityManager; //for truncate table service
use Ddeboer\DataImport\Reader as Reader;
use Ddeboer\DataImport\Writer\ConsoleTableWriter;
use Symfony\Component\Console\Helper\Table;
use Ddeboer\DataImport\Writer\DoctrineWriter;
use Ddeboer\DataImport\Filter;
use Ddeboer\DataImport\Writer\ConsoleProgressWriter;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use \Ddeboer\DataImport\Filter\ValidatorFilter;
use Symfony\Component\Validator\Constraints as Assert;


class ImportService
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function importProductsWorkflow(Reader $reader, $output, $test = false) {
        $workflow = new StepAggregator($reader);
        $progressWriter = new ConsoleProgressWriter($output,$reader);
        $workflow->addWriter($progressWriter);


        if ($test) {
            $table = new Table($output);
            $workflow->addWriter(new ConsoleTableWriter($output, $table));
        } else {
            $doctrineWriter = new DoctrineWriter($this->entityManager, 'ImportBundle:ProductItem');
            $workflow->addWriter($doctrineWriter);
            //Validation
            $filter = new ValidatorFilter($this->validator);
            //
            //var_dump($filter);die;
            $filter->add('stock', new Assert\NotBlank());
            $filterStep = new FilterStep($filter);

            $workflow->addStep($filterStep);


        }

        $workflow->process();
    }

    //truncating table...
    public function truncateTable() {
        $this->entityManager->clear('ImportBundle:ProductItem');
    }

}