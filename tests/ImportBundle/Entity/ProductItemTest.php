<?php
/**
 * Created by PhpStorm.
 * User: Sergey Folitar
 * Date: 8/1/16
 * Time: 12:32 PM
 */
namespace Tests\ImportBundle\Entity;

use ImportBundle\Entity\ProductItem;

class CvsProductTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Testing empty product item
     */
    public function testGetEmptyProduct()
    {
        $productItem = new ProductItem();

        $this->assertNull($productItem->getDateDiscontinued());
        $this->assertNull($productItem->getCost());
        $this->assertNull($productItem->getDateAdded());
        $this->assertNull($productItem->getStock());
        $this->assertNull($productItem->getProductDataId());
        $this->assertNull($productItem->getProductCode());
        $this->assertNull($productItem->getProductDesc());
        $this->assertNull($productItem->getProductName());
    }

    /**
     * Testing product with data
     */
    public function testGetProductWithData()
    {
        $product = new ProductItem();
        $product->setProductCode('P0001')
            ->setProductName('TV')
            ->setProductDesc('32” Tv')
            ->setStock(10)
            ->setCost(399.99);

        $this->assertEquals('P0001', $product->getProductCode());
        $this->assertEquals('TV', $product->getProductName());
        $this->assertEquals('32” Tv', $product->getProductDesc());
        $this->assertEquals(10, $product->getStock());
        $this->assertEquals(399.99, $product->getCost());
    }
}