<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HikashopPrice
 *
 * @ORM\Table(name="hikashop_tax")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HikashopTaxRepository")
 */
class HikashopTax
{
    /**
     * @var int
     *
     * @ORM\Column(name="tax_namekey", columnDefinition="VARCHAR(255) NOT NULL PRIMARY KEY")
     * @ORM\Id
     */
    private $namekey;

    /**
     * @var string
     *
     * @ORM\Column(name="tax_rate", type="decimal", precision=17, scale=5, nullable=false, options={"default":0, "unsigned"=true})
     */
    private $rate;
}
