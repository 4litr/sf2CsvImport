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
use ImportBundle\Factory\ImporterInterface;
use ImportBundle\Factory\ImportFactory;
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
     * @var ImportFactory
     */
    protected $factory;
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @param ImportFactory $factory
     * @param EntityManager $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(
        ImportFactory $factory,
        EntityManager $entityManager,
        ValidatorInterface $validator
    )
    {
        $this->factory = $factory;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @param $filePath
     * @param $testMode
     * @return \ImportBundle\ImportResult\ImportResult
     */
    public function startImport($filePath, $testMode) {
        $fileExtension = $this->getFileExtension($filePath);
        $importer = $this->factory->createImporter($fileExtension, $testMode);
        $result = $importer->import($filePath);

        return $result;
    }

    /**
     * @param string $filePath
     * @return string
     */
    protected function getFileExtension($filePath)
    {
        return substr(strrchr($filePath, '.'), 1);
    }
}