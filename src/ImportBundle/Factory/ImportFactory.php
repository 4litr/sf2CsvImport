<?php

/**
 * Created by PhpStorm.
 * User: Sergey Folitar
 * Date: 7/21/16
 * Time: 11:43 AM
 */
namespace ImportBundle\Factory;

use Ddeboer\DataImport\Reader;
use Ddeboer\DataImport\Reader\CsvReader;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class ImportFactory
{
    /**
     * returns file reader(currently only csv supported)
     * @param $fileFormat
     * @param $filePath
     * @return Reader
     */
    public static function getReader($fileFormat, $filePath)
    {
        $readerInstance = null;

        switch ($fileFormat) {
            case 'csv':
            default:
            $readerInstance = self::getCsvFileReader($filePath);
                break;
        }

        return $readerInstance;
    }

    /**
     * @param $filePath
     * @return CsvReader
     * @throws \Exception
     */
    protected static function getCsvFileReader($filePath)
    {
        try{
            $file = new \SplFileObject($filePath);
            $readerInstance = new CsvReader($file);

            //Tell the reader that the first row in the CSV file contains column headers
            $readerInstance->setHeaderRowNumber(0);
            $readerInstance->setStrict(false);
            return $readerInstance;
        } catch (\Exception $ex) {
            throw new FileNotFoundException;
        }
    }

}