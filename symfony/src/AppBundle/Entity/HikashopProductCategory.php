<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HikashopPrice
 *
 * @ORM\Table(name="hikashop_product_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HikashopProductCategory")
 */
class HikashopProductCategory
{
    /**
     * @var int
     *
     * @ORM\Column(name="product_category_id", columnDefinition="INT(255) UNSIGNED NOT NULL AUTO_INCREMENT")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="category_id", columnDefinition=" INT(10) UNSIGNED NOT NULL")
     */
    private $categoryId;

    /**
     * @var int
     *
     * @ORM\Column(name="product_id", columnDefinition=" INT(10) UNSIGNED NOT NULL")
     */
    private $productId;

    /**
     * @var string
     *
     * @ORM\Column(name="ordering", columnDefinition="INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $ordering;

}