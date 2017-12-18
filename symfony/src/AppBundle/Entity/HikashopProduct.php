<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HikashopProduct
 *
 * @ORM\Table(name="hikashop_product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class HikashopProduct
{
    /**
     * @var int
     *
     * @ORM\Column(name="product_id", columnDefinition="INT(11) NOT NULL")
     * @ORM\Id
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="product_parent_id", columnDefinition="INT(11) NOT NULL DEFAULT '0'")
     */
    private $parentId;

    /**
     * @var string
     *
     * @ORM\Column(name="product_name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="product_description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="product_quantity", columnDefinition="INT(11) NOT NULL DEFAULT '-1'")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="product_code", type="string", length=255, nullable=false)
     */
    private $code;

    /**
     * @var int
     *
     * @ORM\Column(name="product_published", columnDefinition="TINYINT(4) NOT NULL DEFAULT '0'")
     */
    private $published;

    /**
     * @var int
     *
     * @ORM\Column(name="product_hit", columnDefinition="INT(11) NOT NULL DEFAULT '0'")
     */
    private $hit;

    /**
     * @var int
     *
     * @ORM\Column(name="product_created", columnDefinition="INT(11) NOT NULL DEFAULT '0'")
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="product_sale_start", columnDefinition="INT(10) NOT NULL DEFAULT '0'")
     */
    private $saleStartAt;

    /**
     * @var int
     *
     * @ORM\Column(name="product_sale_end", columnDefinition="INT(10) NOT NULL DEFAULT '0'")
     */
    private $saleEndAt;

    /**
     * @var int
     *
     * @ORM\Column(name="product_delay_id", columnDefinition="INT(10) NOT NULL DEFAULT '0'")
     */
    private $delayId;

    /**
     * @var int
     *
     * @ORM\Column(name="product_tax_id", columnDefinition="INT(10) NOT NULL DEFAULT '0'")
     */
    private $taxId;

    /**
     * @var string
     *
     * @ORM\Column(name="product_type", type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(name="product_vendor_id", columnDefinition="INT(10) NOT NULL DEFAULT '0'")
     */
    private $vendorId;

    /**
     * @var int
     *
     * @ORM\Column(name="product_manufacturer_id", columnDefinition="INT(10) NOT NULL DEFAULT '0'")
     */
    private $manufacturerId;

    /**
     * @var int
     *
     * @ORM\Column(name="product_url", columnDefinition="INT(10) NOT NULL DEFAULT '0'")
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="product_weight", type="decimal", precision=12, scale=3, nullable=false, options={"default":0})
     */
    private $weight;

    /**
     * @var string
     *
     * @ORM\Column(name="product_keywords", type="text", nullable=false)
     */
    private $keywords;

    /**
     * @var string
     *
     * @ORM\Column(name="product_weight_unit", type="string", length=255, nullable=false, options={"default":'kg'})
     */
    private $weightUnit;

    /**
     * @var int
     *
     * @ORM\Column(name="product_modified", columnDefinition="INT(10) NOT NULL DEFAULT '0'")
     */
    private $modifiedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="product_meta_description", type="string", length=255, nullable=false)
     */
    private $mataDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="product_dimension_unit", type="string", length=255, nullable=false, options={"default":'m'})
     */
    private $dimensionUnit;

    /**
     * @var string
     *
     * @ORM\Column(name="product_width", type="decimal", precision=12, scale=3, nullable=false, options={"default":0})
     */
    private $width;

    /**
     * @var string
     *
     * @ORM\Column(name="product_length", type="decimal", precision=12, scale=3, nullable=false, options={"default":0})
     */
    private $length;

    /**
     * @var string
     *
     * @ORM\Column(name="product_height", type="decimal", precision=12, scale=3, nullable=false, options={"default":0})
     */
    private $height;

    /**
     * @var string
     *
     * @ORM\Column(name="product_max_per_order", columnDefinition="INT(10) NULL DEFAULT '0'")
     */
    private $maxPerOrder;

    /**
     * @var string
     *
     * @ORM\Column(name="product_access", type="string", length=255, nullable=false, options={"default":'all'})
     */
    private $accessingBy;

    /**
     * @var string
     *
     * @ORM\Column(name="product_group_after_purchase", type="string", length=255, nullable=false)
     */
    private $groupAfterPurchase;

    /**
     * @var string
     *
     * @ORM\Column(name="product_min_per_order", columnDefinition="INT(10) NULL DEFAULT '0'")
     */
    private $minPerOrder;

    /**
     * @var string
     *
     * @ORM\Column(name="product_contact", columnDefinition="SMALLINT(5) NOT NULL DEFAULT '0'")
     */
    private $contact;

    /**
     * @var string
     *
     * @ORM\Column(name="product_display_quantity_field", columnDefinition="SMALLINT(5) NOT NULL DEFAULT '0'")
     */
    private $displayQuantityField;

    /**
     * @var string
     *
     * @ORM\Column(name="product_last_seen_date", columnDefinition="INT(10) NULL DEFAULT '0'")
     */
    private $lastSeenDate;

    /**
     * @var string
     *
     * @ORM\Column(name="product_sales", columnDefinition="INT(10) NULL DEFAULT '0'")
     */
    private $sales;

    /**
     * @var string
     *
     * @ORM\Column(name="product_waitlist", columnDefinition="SMALLINT(5) NOT NULL DEFAULT '0'")
     */
    private $waitlist;

    /**
     * @var string
     *
     * @ORM\Column(name="product_layout", type="string", length=255, nullable=false)
     */
    private $layout;

    /**
     * @var string
     *
     * @ORM\Column(name="product_average_score", columnDefinition="FLOAT NOT NULL")
     */
    private $averageScore;

    /**
     * @var string
     *
     * @ORM\Column(name="product_total_vote", columnDefinition="INT(11) NOT NULL DEFAULT '0'")
     */
    private $totalVote;

    /**
     * @var string
     *
     * @ORM\Column(name="product_page_title", type="string", length=255, nullable=false)
     */
    private $pageTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="product_alias", type="string", length=255, nullable=false)
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="product_price_percentage", type="decimal", precision=15, scale=7, nullable=false, options={"default":0})
     */
    private $pricePercentage;

    /**
     * @var string
     *
     * @ORM\Column(name="product_msrp", type="decimal", precision=15, scale=7, nullable=true, options={"default":0})
     */
    private $msrp;

    /**
     * @var string
     *
     * @ORM\Column(name="product_canonical", type="string", length=255, nullable=false)
     */
    private $canonical;

    /**
     * @var string
     *
     * @ORM\Column(name="product_warehouse_id", columnDefinition="INT(10) NOT NULL DEFAULT '0'")
     */
    private $warehouseId;

    /**
     * @var string
     *
     * @ORM\Column(name="product_quantity_layout", type="string", length=255, nullable=false)
     */
    private $quantityLayout;

    /**
     * @var string
     *
     * @ORM\Column(name="product_sort_price", type="decimal", precision=17, scale=5, nullable=false, options={"default":0})
     */
    private $sortPrice;

}



















