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
     * @ORM\Column(name="strProductName", type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="Product name is empty!")
     */
    private $strProductName;

    /**
     * @var string
     * @ORM\Column(name="strProductDesc", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Product desc is empty!");
     */
    private $strProductDesc;

    /**
     * @var string
     * @ORM\Column(name="strProductCode", type="string", length=10, nullable=false)
     * @Assert\NotBlank(message="Product code is empty!")
     */
    private $strProductCode;

    /**
     * @var \DateTime
     * @ORM\Column(name="dtmAdded", type="datetime", nullable=true)
     */
    private $dtmAdded;

    /**
     * @var \DateTime
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
     * @Assert\Range(
     *      min = 0,
     *      max = 1000,
     *      minMessage = "Item cost must be at least {{ limit }}",
     *      maxMessage = "Item cost cannot be taller than {{ limit }}")
     * @ORM\Column(name="fltCost", type="float", options={"unsigned"=true})
     */
    private $cost;

    /**
     * @var bool
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
     * @param $strProductCode
     * @return $this
     */
    public function setStrProductCode($strProductCode)
    {
        $this->strProductCode = $strProductCode;

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
     * @return $this
     */
    public function setDtmAdded()
    {
        $this->dtmAdded = new \DateTime();

        return $this;
    }

    /**
     * @return $this
     */
    public function setDtmDiscontinued()
    {
        if ($this->discontinued) {
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
        return $this;
    }
}
