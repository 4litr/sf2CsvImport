<?php
/**
 * Created by PhpStorm.
 * User: Sergey Folitar
 * Date: 8/1/16
 * Time: 1:04 PM
 */
namespace Tests\ImportBundle\Factory;

use Ddeboer\DataImport\Reader\Factory\CsvReaderFactory;
use Ddeboer\DataImport\Reader\CsvReader;

class CsvReaderFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetReader()
    {
        $factory = new CsvReaderFactory();
        $reader = $factory->getReader(new \SplFileObject(__DIR__.'/../Fixtures/stock.csv'));

        $this->assertInstanceOf('\Ddeboer\DataImport\Reader\CsvReader', $reader);
        $this->assertCount(30, $reader);

        $factory = new CsvReaderFactory(0);
        $reader = $factory->getReader(new \SplFileObject(__DIR__.'/../Fixtures/stock.csv'));

        $this->assertCount(29, $reader);
    }

    /**
     * Checks if Csv reader asserts empty csv headers
     */
    public function testReadCsvFileWithoutColumnHeaders()
    {
        $file = new \SplFileObject(__DIR__.'/../Fixtures/no_headers_stock.csv');
        $csvReader = new CsvReader($file);

        $this->assertEmpty($csvReader->getColumnHeaders());
    }
}