<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stock.
 *
 * @ORM\Table(name="stock")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StockRepository")
 */
class Stock
{
    /**
     * @var string
     *
     * @ORM\Column(name="stock_ean", type="string", length=13, nullable=false)
     * @ORM\Id
     */
    private $ean;

    /**
     * @var int
     *
     * @ORM\Column(name="stock_qte", columnDefinition="INT(5) NOT NULL DEFAULT '0'")
     */
    private $quantity;

    /**
     *
     * Constructor.
     *
     */
    function __construct() 
    {
        
    }

    /**
     * Set ean
     *
     * @param string $ean
     *
     * @return Stock
     */
    public function setEan($ean)
    {
        $this->ean = $ean;

        return $this;
    }

    /**
     * Get ean
     *
     * @return string
     */
    public function getEan()
    {
        return $this->ean;
    }

    /**
     * Set quantity
     *
     * @param string $quantity
     *
     * @return Stock
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
}
