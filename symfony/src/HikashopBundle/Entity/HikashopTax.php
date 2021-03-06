<?php

namespace HikashopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HikashopPrice
 *
 * @ORM\Table(name="c0syc_hikashop_tax")
 * @ORM\Entity(repositoryClass="HikashopBundle\Repository\HikashopTaxRepository")
 */
class HikashopTax
{
    /**
     * @var int
     *
     * @ORM\Column(name="tax_namekey", columnDefinition="VARCHAR(255) NOT NULL")
     * @ORM\Id
     */
    private $namekey;

    /**
     * @var string
     *
     * @ORM\Column(name="tax_rate", type="decimal", precision=17, scale=5, nullable=false, options={"default":0, "unsigned"=true})
     */
    private $rate;

    /**
     *
     * setFromLibrisoft.
     *
     */
    function setFromLibrisoft($namekey, $rate) 
    {
        $this->namekey = $namekey;
        $this->rate = $rate;
    }

    /**
     * Set namekey
     *
     * @param string $namekey
     *
     * @return HikashopTax
     */
    public function setNamekey($namekey)
    {
        $this->namekey = $namekey;

        return $this;
    }

    /**
     * Get namekey
     *
     * @return string
     */
    public function getNamekey()
    {
        return $this->namekey;
    }

    /**
     * Set rate
     *
     * @param string $rate
     *
     * @return HikashopTax
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return string
     */
    public function getRate()
    {
        return $this->rate;
    }
}
