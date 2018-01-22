<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HikashopPrice
 *
 * @ORM\Table(name="hikashop_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HikashopCategoryRepository")
 */
class HikashopCategory
{
    /**
     * @var int
     *
     * @ORM\Column(name="category_id", columnDefinition="INT(10) UNSIGNED NOT NULL AUTO_INCREMENT")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="category_parent_id", columnDefinition=" INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $parentId;

    /**
     * @var string
     *
     * @ORM\Column(name="category_type", type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="category_name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="category_description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="category_published", columnDefinition="TINYINT(4) NOT NULL DEFAULT '0'")
     */
    private $published;

    /**
     * @var int
     *
     * @ORM\Column(name="category_ordering", columnDefinition="INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $ordering;

    /**
     * @var int
     *
     * @ORM\Column(name="category_left", columnDefinition="INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $left;

    /**
     * @var int
     *
     * @ORM\Column(name="category_right", columnDefinition="INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $right;

    /**
     * @var int
     *
     * @ORM\Column(name="category_depth", columnDefinition="INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $depth;

    /**
     * @var string
     *
     * @ORM\Column(name="category_namekey", type="string", length=255, nullable=false)
     */
    private $namekey;

    /**
     * @var int
     *
     * @ORM\Column(name="category_created", columnDefinition="INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="category_modified", columnDefinition="INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $modifiedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="category_access", type="string", length=255, nullable=false, options={"default":"all"})
     */
    private $accessingBy;

    /**
     * @var int
     *
     * @ORM\Column(name="category_menu", columnDefinition="INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $menu;

    /**
     * @var string
     *
     * @ORM\Column(name="category_keywords", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="category_meta_description", type="string", length=255, nullable=false, options={"default":""})
     */
    private $metaDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="category_layout", type="string", length=255, nullable=false, options={"default":""})
     */
    private $layout;

    /**
     * @var string
     *
     * @ORM\Column(name="category_page_title", type="string", length=255, nullable=false, options={"default":""})
     */
    private $pageTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="category_alias", type="string", length=255, nullable=false, options={"default":""})
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="category_site_id", type="string", length=255, nullable=true, options={"default":""})
     */
    private $siteId;

    /**
     * @var string
     *
     * @ORM\Column(name="category_canonical", type="string", length=255, nullable=false, options={"default":""})
     */
    private $canonical;

    /**
     * @var string
     *
     * @ORM\Column(name="category_quantity_layout", type="string", length=255, nullable=false, options={"default":""})
     */
    private $quantityLayout;






}