<?php
/**
 * Created by PhpStorm.
 * User: Sergey Folitar
 * Date: 7/22/16
 * Time: 6:40 PM
 */

namespace ImportBundle\Constraints;

interface ConstraintsInterface
{
    /**
     * @param $entity
     * @return mixed
     */
    public function getConstraint($entity);
}