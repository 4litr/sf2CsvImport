<?php

/**
 * Created by PhpStorm.
 * User: Sergey Folitar
 * Date: 7/21/16
 * Time: 11:43 AM
 */
namespace ImportBundle\Factory;

use Ddeboer\DataImport\Reader;
use Ddeboer\DataImport\Step\MappingStep;
use Ddeboer\DataImport\Writer as DdeboerWriter;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use ImportBundle\Constraints\ConstraintsInterface;

class ImportFactory
{
    const EXT_CSV = 'csv';

    /**
     * @var DdeboerWriter
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
     * @param DdeboerWriter $writer
     * @param ValidatorInterface $validator
     * @param MappingStep $converter
     * @param ConstraintsInterface $constraints
     */
    public function __construct(
        DdeboerWriter $writer,
        ValidatorInterface $validator,
        MappingStep $converter,
        ConstraintsInterface $constraints
    )
    {
        $this->writer = $writer;
        $this->validator = $validator;
        $this->converter = $converter;
        $this->helper = $constraints;
    }

    public function createImporter($fileFormat)
    {
        echo(123);die;

        switch ($fileFormat) {
            case self::EXT_CSV:
                $importer = new CsvImporter();
                break;
            default:
                throw new \Exception('Format not found');
        }

        $this->setUp($importer);
        return $importer;
    }

    /**
     * @param Importer $importer
     * @return $this
     */
    protected function setUp(Importer $importer)
    {
        $importer->setWriter($this->writer);
        $importer->setConstraints($this->helper);
        $importer->setValidator($this->validator);
        $importer->setConverter($this->converter);

        return $this;

    }
}