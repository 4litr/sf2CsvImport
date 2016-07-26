<?php

/**
 * Created by PhpStorm.
 * User: Sergey Folitar
 * Date: 20.7.16
 * Time: 23.45
 */
namespace ImportBundle\Services;

use Doctrine\ORM\EntityManager; //for truncate table service
use Ddeboer\DataImport\Reader as Reader;
use ImportBundle\Factory\ImportFactory;
use Ddeboer\DataImport\Filter;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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
     * @param bool|false $testRun
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
     * @param $testRun
     * @return \ImportBundle\ImportResult\ImportResult
     */
    public function startImport($filePath, $testRun) {
        $fileExtension = $this->getFileExtension($filePath);
        $importer = $this->factory->createImporter($fileExtension, $testRun);
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