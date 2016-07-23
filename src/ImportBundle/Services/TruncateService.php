<?php
/**
 * Created by PhpStorm.
 * User: litr4
 * Date: 23.7.16
 * Time: 12.59
 */
namespace ImportBundle\Services;


use Doctrine\ORM\EntityManager;

class TruncateService
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function truncateTable() {
        $this->entityManager->createQuery(
            'DELETE ImportBundle:ProductItem p'
        )
            ->getResult();
    }
}