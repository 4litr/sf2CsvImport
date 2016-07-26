<?php
/**
 *  * Created by PhpStorm.
 * User: litr4
 * Date: 23.7.16
 * Time: 19.21
 */

namespace ImportBundle\Filters;

use Ddeboer\DataImport\Exception\WriterException;

class UniqueProductFilter extends Filter
{
    /**
     * checks for duplications
     *
     * @return \Closure
     */
    public function getCallable()
    {
        return function ($data) {
            $this->checkValue($data);

            if ($this->isExists($data)) {
                throw new WriterException(
                    'Duplication productCode:[' . $this->getValue($data) . ']'
                );
            } else {
                $this->addValue($data);
            }

            return true;
        };
    }
}