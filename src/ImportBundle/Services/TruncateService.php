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

    protected $class;

    public function __construct(EntityManager $entityManager, $class)
    {
        $this->entityManager = $entityManager;
        $this->class = $class;
    }

    public function truncateTable()
    {
        $tableName = $this->entityManager->getClassMetadata($this->class)->table['name'];
        $connection = $this->entityManager->getConnection();
        $query = $connection->getDatabasePlatform()->getTruncateTableSQL($tableName, true);
        $connection->executeQuery($query);
    }
}
