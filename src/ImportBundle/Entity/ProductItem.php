<?php

namespace ImportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
//use Symfony\Component\Validator\Constraints\DateTime;


/**
 * ProductItem
 *
 * @ORM\Table(name="tblProductData", uniqueConstraints={@ORM\UniqueConstraint(name="strProductCode", columns={"strProductCode"})})
 * @ORM\Entity
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
     *
     * @ORM\Column(name="strProductName", type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="Product name is empty!")
     */
    private $strProductName;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductDesc", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Product desc is empty!");
     */
    private $strProductDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductCode", type="string", length=10, nullable=false)
     * @Assert\NotBlank(message="Product code is empty!")
     */
    private $strProductCode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dtmAdded", type="datetime", nullable=true)
     */
    private $dtmAdded;

    /**
     * @var \DateTime
     * @Assert\Type(type="numeric", message="Property stock should be of type numeric")
     * @Assert\NotBlank(message="Property stock is blank")
     * @ORM\Column(name="dtmDiscontinued", type="datetime", nullable=true)
     */
    private $dtmDiscontinued;

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
     * @ORM\Column(name="fltCost", type="float", options={"unsigned"=true})
     */
    private $cost;

    /**
     * @var string|null
     */
    private $discontinued;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="stmTimestamp", type="datetime", nullable=false)
     */
    private $stmTimestamp = 'CURRENT_TIMESTAMP';





    /**
     * Set strProductName
     *
     * @param string $strProductName
     *
     * @return ProductItem
     */
    public function setStrProductName($strProductName)
    {
        $this->strProductName = $strProductName;

        return $this;
    }

    /**
     * Get strProductName
     *
     * @return string
     */
    public function getStrProductName()
    {
        return $this->strProductName;
    }

    /**
     * Set strProductDesc
     *
     * @param string $strProductDesc
     *
     * @return ProductItem
     */
    public function setStrProductDesc($strProductDesc)
    {
        $this->strProductDesc = $strProductDesc;

        return $this;
    }

    /**
     * Get strProductDesc
     *
     * @return string
     */
    public function getStrProductDesc()
    {
        return $this->strProductDesc;
    }

    /**
     * Set strProductCode
     *
     * @param string $strProductCode
     *
     * @return ProductItem
     */
    public function setStrProductCode($strProductCode)
    {
        $this->strProductCode = $strProductCode;

        return $this;
    }

    /**
     * Get strProductCode
     *
     * @return string
     */
    public function getStrProductCode()
    {
        return $this->strProductCode;
    }

    /**
     * Set dtmAdded
     *
     * @param \DateTime $dtmAdded
     *
     * @return ProductItem
     */
    public function setDtmAdded($dtmAdded)
    {
        $this->dtmAdded = $dtmAdded;

        return $this;
    }

    /**
     * Get dtmAdded
     *
     * @return \DateTime
     */
    public function getDtmAdded()
    {
        return $this->dtmAdded;
    }

    /**
     * Set dtmDiscontinued
     *
     * @param \DateTime $dtmDiscontinued
     *
     * @return ProductItem
     */
    public function setDtmDiscontinued()
    {
        if ($this->discontinued === 'yes') {
            $this->dtmDiscontinued = new \DateTime();
        }

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setStmTimestamp()
    {
        $this->stmTimestamp = new \DateTime();
    }

    /**
     * Get dtmDiscontinued
     *
     * @return \DateTime
     */
    public function getDtmDiscontinued()
    {
        return $this->dtmDiscontinued;
    }

    /**
     * Get stmTimestamp
     *
     * @return \DateTime
     */
    public function getStmTimestamp()
    {
        return $this->stmTimestamp;
    }

    /**
     * Get intProductDataId
     *
     * @return integer
     */
    public function getIntProductDataId()
    {
        return $this->intProductDataId;
    }

    /**
     * @Assert\IsFalse(message="Product cost less than 5 and product stock less than 10")
     * @return bool
     */
    public function isProductLessCostAndStock()
    {
        return ($this->cost < 5 && $this->stock < 10);
    }

    /**
     * @Assert\IsFalse(message="Product cost over than 1000")
     * @return bool
     */
    public function isProductOverCost()
    {
        return ($this->cost > 1000);
    }
}
