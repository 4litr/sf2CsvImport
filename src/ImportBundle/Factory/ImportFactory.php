<?php

/**
 * Created by PhpStorm.
 * User: Sergey Folitar
 * Date: 7/21/16
 * Time: 11:43 AM
 */
namespace ImportBundle\Factory;

use Ddeboer\DataImport\Step\MappingStep;
use Ddeboer\DataImport\Writer\DoctrineWriter as DdeboerDoctrineWriter;
use Ddeboer\DataImport\Writer\ConsoleTableWriter as DdeboerTableWriter;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use ImportBundle\Constraints\ConstraintsInterface;

class ImportFactory
{
    const EXT_CSV = 'csv';

    /**
     * @var DdeboerDoctrineWriter
     */
    protected $doctrineWriter;

    /**
     * @var DdeboerTableWriter
     */
    protected $tableWriter;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var ConstraintsInterface
     */
    protected $constraints;

    /**
     * @var MappingStep
     */
    protected $converter;

    /**
     * ImportFactory constructor.
     * @param DdeboerDoctrineWriter $doctrineWriter
     * @param DdeboerTableWriter $tableWriter
     * @param ValidatorInterface $validator
     * @param MappingStep $converter
     * @param ConstraintsInterface $constraints
     */
    public function __construct(
        DdeboerDoctrineWriter $doctrineWriter,
        DdeboerTableWriter $tableWriter,
        ValidatorInterface $validator,
        MappingStep $converter,
        ConstraintsInterface $constraints
    )
    {
        $this->doctrineWriter = $doctrineWriter;
        $this->tableWriter = $tableWriter;
        $this->validator = $validator;
        $this->converter = $converter;
        $this->constraints = $constraints;
    }

    public function createImporter($fileFormat, $testMode)
    {
        switch ($fileFormat) {
            case self::EXT_CSV:
                $importer = new CsvImporter();
                break;
            default:
                throw new \Exception('Format not found');
        }
        $this->setUp($importer, $testMode);
        return $importer;
    }

    /**
     * @param Importer $importer
     * @param $testMode
     */
    protected function setUp(Importer $importer, $testMode)
    {
        if ($testMode) {
            $importer->setWriter($this->tableWriter);
        } else {
            $importer->setWriter($this->doctrineWriter);
        }
        $importer->setConstraints($this->constraints);
        $importer->setValidator($this->validator);
        $importer->setConverter($this->converter);
    }
}