<?php
/**
 * Created by PhpStorm.
 * User: Sergey Folitar
 * Date: 7/22/16
 * Time: 6:18 PM
 */

namespace ImportBundle\Factory;

use Ddeboer\DataImport\Reader;
use Ddeboer\DataImport\Writer;
use Ddeboer\DataImport\Step\MappingStep;
use Ddeboer\DataImport\Result;
use ImportBundle\Constraints\Constraints;
use ImportBundle\Constraints\ConstraintsInterface;
use ImportBundle\ImportResult;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class Importer
{
    /**
     * @var Writer
     */
    protected $writer;

    /**
     * @var bool
     */
    protected $testRun;

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
     * @var int
     */
    protected $cost;
    /**
     * @var int
     */
    protected $stock;

    /**
     * @param Writer $writer
     */
    public function setWriter(Writer $writer)
    {
        $this->writer = $writer;
    }

    /**
     * @param boolean $testOption
     */
    public function setTestOption($testOption)
    {
        $this->testOption = $testOption;
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
    public function getConstraintHelper()
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
     * @return int
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param int $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    /**
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
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
     * @param $importResult
     * @param $reader
     * @return ImporterResult
     */
    public function getResult($importResult, $reader)
    {
        $result = new Result();
        $result->setEndTime($importResult->getEndTime()->format('Y-m-d h:m:s'));
        $result->setExceptions($importResult->getExceptions());
        $result->setErrors($this->getParseErrors($reader));
        $result->setSuccessCount($importResult->getSuccessCount());

        return $result;
    }
}