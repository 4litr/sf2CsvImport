<?php

namespace ImportBundle\Factory;

use Ddeboer\DataImport\Reader;
use Ddeboer\DataImport\Writer\DoctrineWriter as Doctrine;
use Ddeboer\DataImport\Step\FilterStep;
use Ddeboer\DataImport\Step\MappingStep;
use Ddeboer\DataImport\Workflow\StepAggregator;
use Ddeboer\DataImport\Writer\ConsoleProgressWriter;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Console\Output\ConsoleOutput;
use ImportBundle\ImportResult\ImportResult;
use ImportBundle\Filters as Filters;




class CsvImporter extends Importer implements ImporterInterface
{
    /**
     * @param string $file
     * @return Reader\CsvReader
     */
    public function getReader($file)
    {
        $file = new \SplFileObject($file);
        $reader = new Reader\CsvReader($file);

        //ignores csv headers row
        $reader->setHeaderRowNumber(0);
        return $reader;
    }

    /**
     * @param MappingStep $converter
     * @return $this
     */
    public function setConverter(MappingStep $converter)
    {
        $this->converter = $converter;
        $this->converter->map('[Product Code]', '[strProductCode]')
            ->map('[Product Name]', '[strProductName]')
            ->map('[Product Description]', '[strProductDesc]')
            ->map('[Stock]', '[intStock]')
            ->map('[Cost in GBP]', '[fltCost]')
            ->map('[Discontinued]', '[dtmDiscontinued]');

        return $this;
    }

    /**
     * @param $filePath
     * @return ImportResult
     */
    public function import($filePath)
    {
        $reader = $this->getReader($filePath);
        $workflow = $this->getWorkflow($reader);

        return $this->getResult($workflow->process(), $reader);
    }

    /**
     * @return FilterStep
     */
    protected function getFilter()
    {
        $filterStep = new FilterStep();

        $filterStep->add((new Filters\UniqueProductFilter('strProductCode'))->getCallable(), 100);
        $filterStep->add((new Filters\ConditionsFilter('strProductCode', function ($data) {
                return $data['fltCost'] >= Filters\ConditionsFilter::COST_MIN_TRESHOLD || $data['intStock'] >= Filters\ConditionsFilter::STOCK_MIN_TRESHOLD;
            }, 'Items cost is less than ' . Filters\ConditionsFilter::COST_MIN_TRESHOLD .' and stock value is less than ' . Filters\ConditionsFilter::STOCK_MIN_TRESHOLD))
                ->getCallable(), 90);

        $filterStep->add(
            (new Filters\AssertsFilter($this->validator, $this->constraints, Entity::class))
                ->getCallable(), 80
        );

        return $filterStep;
    }

    /**
     * @param Reader $reader
     * @return StepAggregator
     */
    public function getWorkflow(Reader $reader)
    {
        $workflow = new StepAggregator($reader);
        $workflow->setSkipItemOnFailure(true);

        if ($this->writer instanceof Doctrine) {
            $this->writer->disableTruncate();
            $progressWriter = new ConsoleProgressWriter(new ConsoleOutput() ,$reader);
            $workflow->addWriter($progressWriter);
            $workflow->addWriter($this->writer);

            //Field names mappings to csv headers names...
            $workflow->addStep($this->getConverter());
            $workflow->addStep($this->getFilter());
        }
        $workflow->addWriter($this->writer);

        return $workflow;
    }
}
