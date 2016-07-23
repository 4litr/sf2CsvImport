<?php
/**
 * Created by PhpStorm.
 * User: litr4
 * Date: 23.7.16
 * Time: 19.23
 */

namespace ImportBundle\Filters;

use \Ddeboer\DataImport\Filter\ValidatorFilter as DdeboerValidator;
use ImportBundle\Constraints\ConstraintsInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AssertsFilter extends Filter
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;
    /**
     * @var string
     */
    protected $entityClassName;
    /**
     * @var ConstraintsInterface
     */
    protected $constraintHelper;

    /**
     * ValidatorFilter constructor.
     * @param ValidatorInterface $validator
     * @param ConstraintsInterface $constraintHelper
     * @param string $entityClassName
     */
    public function __construct($validator, $constraintHelper, $entityClassName)
    {
        $this->validator = $validator;
        $this->constraintHelper = $constraintHelper;
        $this->entityClassName = $entityClassName;
    }

    /**
     * checks for Doctrine Asserts
     *
     * @return DdeboerValidator
     */
    public function getCallable()
    {
        $validator = new DdeboerValidator($this->validator);

        $arrayOfConstraints = $this->constraintHelper->getConstraint(new $this->entityClassName());
        foreach ($arrayOfConstraints as $value) {
            $validator->add($value['field'], $value['constraint']);
        }

        $validator->throwExceptions();
        $validator->setStrict(false);

        return $validator;
    }
}
