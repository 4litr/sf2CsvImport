<?php
/**
 * Created by PhpStorm.
 * User: Sergey Folitar
 * Date: 7/22/16
 * Time: 6:21 PM
 */
namespace ImportBundle\Factory;

interface ImporterInterface
{
    public function import($file);
}