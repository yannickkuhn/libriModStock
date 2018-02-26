<?php

namespace HikashopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HikashopPrice
 *
 * @ORM\Table(name="c0syc_hikashop_product_category")
 * @ORM\Entity(repositoryClass="HikashopBundle\Repository\HikashopProductCategoryRepository")
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

    /**
     *
     * setFromLibrisoft.
     *
     */
    function setFromLibrisoft($categoryId, $productId, $ordering = 1) 
    {
        $this->categoryId = $categoryId;
        $this->productId = $productId;
        $this->ordering = $ordering;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set categoryId
     *
     * @param string $categoryId
     *
     * @return HikashopProductCategory
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return string
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set productId
     *
     * @param string $productId
     *
     * @return HikashopProductCategory
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return string
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set ordering
     *
     * @param string $ordering
     *
     * @return HikashopProductCategory
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;

        return $this;
    }

    /**
     * Get ordering
     *
     * @return string
     */
    public function getOrdering()
    {
        return $this->ordering;
    }
}
