<?php

/**
 * Created by PhpStorm.
 * User: Sergey Folitar
 * Date: 7/22/16
 * Time: 6:37 PM
 */
namespace ImportBundle\Constraints;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class Constraints implements ConstraintsInterface
{

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $entity
     * @return array
     */
    public function getConstraint($entity)
    {
        $metadata = $this->validator->getMetadataFor($entity);
        $constrainedProperties = $metadata->getConstrainedProperties();

        $result = [];

        foreach($constrainedProperties as $constrainedProperty) {

            $propertyMetadata=$metadata->getPropertyMetadata($constrainedProperty);
            $constraints=$propertyMetadata[0]->constraints;
            foreach($constraints as $constraint)
            {
                $result[] = ['field' => $constrainedProperty, 'constraint' => $constraint];
            }
        }

        return $result;
    }
}