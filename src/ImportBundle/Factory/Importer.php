<?php
/**
 * Created by PhpStorm.
 * User: Sergey Folitar
 * Date: 7/22/16
 * Time: 6:18 PM
 */

namespace ImportBundle\Factory;

use Ddeboer\DataImport\Writer as Writer;
use Ddeboer\DataImport\Step\MappingStep;
use Ddeboer\DataImport\Result as DdeboerResult;
use ImportBundle\Constraints\Constraints;
use ImportBundle\Constraints\ConstraintsInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use ImportBundle\ImportResult\ImportResult;

abstract class Importer
{
    /**
     * @var Writer
     */
    protected $writer;

    /**
     * @var bool
     */
    protected $testMode;

    /**
     * @var MappingStep
     */
    protected $converter;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var Constraints
     */
    protected $constraints;

    /**
     * @param Writer $writer
     */
    public function setWriter(Writer $writer)
    {
        $this->writer = $writer;
    }

    /**
     * @return MappingStep
     */
    public function getConverter()
    {
        return $this->converter;
    }

    /**
     * @param MappingStep $converter
     * @return $this
     */
    public function setConverter(MappingStep $converter)
    {
        $this->converter = $converter;

        return $this;
    }

    /**
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * @param ValidatorInterface $validator
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @return Constraints
     */
    public function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * @param ConstraintsInterface $constraints
     */
    public function setConstraints(ConstraintsInterface $constraints)
    {
        $this->constraints = $constraints;
    }

    /**
     * @param $reader
     * @return array
     */
    protected function getParseErrors($reader)
    {
        $result = [];

        if (method_exists($reader, 'hasErrors') && $reader->hasErrors()) {
            $result = array_keys($reader->getErrors());
        }

        return $result;
    }

    /**
     * @param DdeboerResult$importResult
     * @param $reader
     * @return ImportResult
     */
    public function getResult($importResult, $reader)
    {
        $result = new ImportResult();
        $result->setEndTime($importResult->getEndTime()->format('Y-m-d h:m:s'));
        $result->setExceptions($importResult->getExceptions());
        $result->setErrors($this->getParseErrors($reader));
        $result->setSuccessCount($importResult->getSuccessCount());

        return $result;
    }
}