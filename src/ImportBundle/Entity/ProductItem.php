<?php

namespace ImportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * ProductItem
 *
 * @ORM\Table(name="tblProductData", uniqueConstraints={@ORM\UniqueConstraint(name="strProductCode", columns={"strProductCode"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class ProductItem
{
    /**
     * @var integer
     *
     * @ORM\Column(name="intProductDataId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $intProductDataId;

    /**
     * @var string
     * @ORM\Column(name="strProductCode", type="string", length=10, nullable=false)
     * @Assert\NotBlank(message="Product code is empty!")
     */
    private $productCode;

    /**
     * @var string
     * @ORM\Column(name="strProductName", type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="Product name is empty!")
     */
    private $productName;

    /**
     * @var string
     * @ORM\Column(name="strProductDesc", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Product desc is empty!");
     */
    private $productDesc;

    /**
     * @var \DateTime
     * @ORM\Column(name="dtmAdded", type="datetime", nullable=true)
     */
    private $dateAdded;

    /**
     * @var \DateTime
     * @ORM\Column(name="dtmDiscontinued", type="datetime", nullable=true)
     */
    private $dateDiscontinued;

    /**
     * @var int
     * @Assert\Type(type="numeric", message="Property stock should be of type numeric")
     * @Assert\NotBlank(message="Property stock is blank")
     * @ORM\Column(name="intStock", type="integer")
     */
    private $stock;

    /**
     * @var float
     * @Assert\Type(type="numeric", message="Property cost should be of type numeric")
     * @Assert\NotBlank(message="Property cost is blank")
     * @Assert\Range(
     *      min = 0,
     *      max = 1000,
     *      minMessage = "Item cost must be at least {{ limit }}",
     *      maxMessage = "Item cost cannot be higher than {{ limit }}")
     * @ORM\Column(name="fltCost", type="float", options={"unsigned"=true})
     */
    private $cost;

    /**
     * @var \DateTime
     * @ORM\Column(name="stmTimestamp", type="datetime", nullable=false)
     */
    private $timestamp = 'CURRENT_TIMESTAMP';

    /**
     * @param string $productName
     * @return ProductItem
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;
        return $this;
    }

    /**
     * @param string $productDesc
     * @return ProductItem
     */
    public function setProductDesc($productDesc)
    {
        $this->productDesc = $productDesc;
        return $this;
    }

    /**
     * @param $productCode
     * @return $this
     */
    public function setProductCode($productCode)
    {
        $this->productCode = $productCode;
        return $this;
    }

    /**
     * @param $stock
     * @return $this
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
        return $this;
    }

    /**
     * @param $cost
     * @return $this
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setDateAdded()
    {
        $this->dateAdded = new \DateTime();
        return $this;
    }

    public function setDateDiscontinued($isDiscontinued)
    {
        $this->dateDiscontinued = ($isDiscontinued === 'yes') ? new \DateTime() : null;
        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setTimestamp()
    {
        $this->timestamp = new \DateTime();
        return $this;
    }
}
