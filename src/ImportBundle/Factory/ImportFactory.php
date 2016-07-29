<?php

/**
 * Created by PhpStorm.
 * User: Sergey Folitar
 * Date: 7/21/16
 * Time: 11:43 AM
 */
namespace ImportBundle\Factory;

use Ddeboer\DataImport\Step\MappingStep;
use Ddeboer\DataImport\Writer as Writer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use ImportBundle\Constraints\ConstraintsInterface;
use Ddeboer\DataImport\Exception\ReaderException;

class ImportFactory
{
    const EXT_CSV = 'csv';

    /**
     * @var Writer
     */
    protected $writer;

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
     * @param Writer $writer
     * @param ValidatorInterface $validator
     * @param MappingStep $converter
     * @param ConstraintsInterface $constraints
     */
    public function __construct(
        Writer $writer,
        ValidatorInterface $validator,
        MappingStep $converter,
        ConstraintsInterface $constraints
    ) {
        $this->writer = $writer;
        $this->validator = $validator;
        $this->converter = $converter;
        $this->constraints = $constraints;
    }

    public function createImporter($fileFormat, $testRun)
    {
        switch ($fileFormat) {
            case self::EXT_CSV:
                $importer = new CsvImporter();
                break;
            default:
                throw new ReaderException($fileFormat.' format not found');
        }
        $this->setUp($importer, $testRun);
        return $importer;
    }

    /**
     * @param Importer $importer
     * @param $testMode
     */
    protected function setUp(Importer $importer, $testMode)
    {
        $importer->setTestMode($testMode);
        $importer->setWriter($this->writer);
        $importer->setConstraints($this->constraints);
        $importer->setValidator($this->validator);
        $importer->setConverter($this->converter);
    }
}
