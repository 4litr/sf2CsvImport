<?php

namespace ImportBundle\Factory;

use Ddeboer\DataImport\Reader;
use Ddeboer\DataImport\Writer\DoctrineWriter as Doctrine;
use Ddeboer\DataImport\Step\FilterStep;
use Ddeboer\DataImport\Step\MappingStep;
use Ddeboer\DataImport\Workflow\StepAggregator;
use Ddeboer\DataImport\Writer\ConsoleProgressWriter;
use Doctrine\ORM\Mapping\Entity;
use ImportBundle\Entity\ProductItem;
use Symfony\Component\Console\Output\ConsoleOutput;
use ImportBundle\ImportResult\ImportResult;
use ImportBundle\Filters as Filters;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;


class CsvImporter extends Importer implements ImporterInterface
{
    /**
     * @param string $file
     * @return Reader\CsvReader
     */
    public function getReader($file)
    {
        try {
            $file = new \SplFileObject($file);
        } catch (\Exception $ex ) {
            throw new FileNotFoundException();
        }
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
        $this->converter->map('[Product Code]', '[productCode]')
            ->map('[Product Name]', '[productName]')
            ->map('[Product Description]', '[productDesc]')
            ->map('[Stock]', '[stock]')
            ->map('[Cost in GBP]', '[cost]')
            ->map('[Discontinued]', '[dateDiscontinued]');

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

        $filterStep->add((new Filters\UniqueProductFilter('productCode'))->getCallable(), 100);
        $filterStep->add((new Filters\ConditionsFilter('productCode', function ($data) {
                return $data['cost'] >= Filters\ConditionsFilter::COST_MIN_TRESHOLD || $data['stock'] >= Filters\ConditionsFilter::STOCK_MIN_TRESHOLD;
            }, 'Items cost is less than ' . Filters\ConditionsFilter::COST_MIN_TRESHOLD .' and stock value is less than ' . Filters\ConditionsFilter::STOCK_MIN_TRESHOLD))
                ->getCallable(), 90);

        $filterStep->add(
            (new Filters\AssertsFilter($this->validator, $this->constraints, ProductItem::class))
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
            //$this->writer->disableTruncate();
            $progressWriter = new ConsoleProgressWriter(new ConsoleOutput() ,$reader, 'normal', 5);
            $workflow->addWriter($progressWriter);

            //Field names mappings to csv headers names...
            $workflow->addStep($this->getConverter());
            $workflow->addStep($this->getFilter());
        }
        $workflow->addWriter($this->writer);

        return $workflow;
    }
}
