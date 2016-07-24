<?php
/**
 *  * Created by PhpStorm.
 * User: litr4
 * Date: 23.7.16
 * Time: 19.21
 */

namespace ImportBundle\Filters;

use Symfony\Component\Config\Definition\Exception\DuplicateKeyException;

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
                //TODO: check if item already exists in database..
                throw new DuplicateKeyException(
                    sprintf('Duplication product code - %s', $this->getValue($data))

                );
            } else {
                $this->addValue($data);
            }

            return true;
        };
    }
}